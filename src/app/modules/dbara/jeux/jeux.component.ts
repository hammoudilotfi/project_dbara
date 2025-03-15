import {Component, OnInit} from '@angular/core';
import {RecetteService} from "../../../services/recette.service";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {JeuxService} from "../../../services/jeux.service";
import {ToastrService} from "ngx-toastr";

@Component({
  selector: 'app-jeux',
  templateUrl: './jeux.component.html',
  styleUrls: ['./jeux.component.css']
})
export class JeuxComponent implements OnInit {
  data

  selectedValue: number = 1;
  selectedValues: { [key: string]: number } = {};
  voteCounts: { [key: string]: any } = {};
  values: number[] =[1,2,3,4,5,6,7,8,9,10];
  constructor(private jeuxService:JeuxService, private ngxLoader:NgxUiLoaderService,private toaste:ToastrService) {
  }
  getRecettes() {
    this.ngxLoader.start();
    this.jeuxService.getImage().subscribe({
      next: (res) => {
        this.data = res['images'];
        console.log(this.data);

        // Initialize selected values for each jeux item
        this.data.forEach((jeux) => {
          this.selectedValues[jeux.id] = 1;

          this.jeuxService.getVoteCountByImage(jeux.id).subscribe((voteCount) => {
            this.voteCounts[jeux.id] = voteCount['vote_count'];
          });
        });

        this.ngxLoader.stop();
      },
      error: (err) => {
        console.log(err);
      },
    });
  }

  submit(id) {
    this.ngxLoader.start()
let objectToPass={
  note: this.selectedValues[id],
  imageId:id
}
this.jeuxService.addVote(objectToPass).subscribe( res=>{
  this.toaste.success("Vote added successfully")
  this.getRecettes()
  this.ngxLoader.stop()
}
  ,
  (error) => {
    if (error.status === 400 && error.error.message === "You have already rated this image.") {
      this.toaste.warning("You have already voted for this item");
    } else {
      // Handle other error cases here, if needed
      this.toaste.error("An error occurred while processing your vote");
    }
    this.ngxLoader.stop();
  })
    console.log("Selected Value:", this.selectedValue);
    console.log("Selected id:", id);
  }

  ngOnInit() {
    this.getRecettes()
  }
}
