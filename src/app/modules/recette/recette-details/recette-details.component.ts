import {Component, OnInit, TemplateRef, ViewChild, ViewEncapsulation} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {CategoriesService} from "../../../services/categories.service";
import {ToastrService} from "ngx-toastr";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {RecetteService} from "../../../services/recette.service";
import {DomSanitizer, SafeResourceUrl} from "@angular/platform-browser";
import {MatDialog} from "@angular/material/dialog";

@Component({
    selector: 'app-recette-details',
    templateUrl: './recette-details.component.html',
    styleUrls: ['./recette-details.component.css']
})
export class RecetteDetailsComponent implements OnInit {
    public id
    public data

  public otherRecipes=[];
  selectedValue: number = 1;
  selectedValues: { [key: string]: number } = {};
  values: number[] =[1,2,3,4,5,6,7,8,9,10];
  voteCounts
  currentVideoUrl: SafeResourceUrl;
  @ViewChild('videoDialog') videoDialog: TemplateRef<any>;
  constructor(public dialog: MatDialog,private sanitizer: DomSanitizer,private activeRoute: ActivatedRoute, private router: Router, private modalService: NgbModal, private recetteService: RecetteService, private toastr: ToastrService, private ngxLoader: NgxUiLoaderService) {

  }
  addToPerfList(id){
    this.recetteService.addPrefer(id).subscribe({
      next:res=>{
        this.toastr.success("Dbara added successfully to your preferred list")
      }
    })
  }
  getAllRecipes() {
    this.ngxLoader.start()
    this.recetteService.getAll().subscribe((res) => {
      // Filter out the current recipe from the list
      this.otherRecipes = res.filter((recipe) => recipe.id !== this.id).slice(0, 9);
      console.log('otherRecipes',this.otherRecipes)
      this.ngxLoader.stop()
    });
  }
  openYoutubeDialog(videoId: string) {
    this.currentVideoUrl = this.getSafeUrl(videoId);
    this.dialog.open(this.videoDialog, {
      width: '800px',
      height: '500px',
    });
  }
  getSafeUrl(videoId: string): SafeResourceUrl {
    return this.sanitizer.bypassSecurityTrustResourceUrl(`https://www.youtube.com/embed/${videoId}`);
  }
  getRecepy() {
    this.ngxLoader.start();
    this.id = this.activeRoute.snapshot.params['id'];
    this.recetteService.getById(this.id).subscribe(res => {
      this.data = res;
      this.ngxLoader.stop();
      console.log(this.data);
        this.recetteService.getVoteCountByImage(this.id).subscribe((voteCount) => {
          this.voteCounts = voteCount;
        });
    }
    );

  }
    ngOnInit() {
      this.activeRoute.params.subscribe(params => {
        // Update the data whenever the route parameter changes
        this.id = params['id'];
        this.getRecepy();
        this.getAllRecipes();
      });
    }


  submit(id) {
    this.ngxLoader.start()
    let objectToPass={
      note: this.selectedValue,
      dbaretchef_id:id
    }
    this.recetteService.addVote(objectToPass).subscribe( res=>{
      this.toastr.success("Vote added successfully")
      this.getRecepy()
      this.ngxLoader.stop()
    },
      (error) => {
        if (error.status === 400 && error.error.message === "You have already given a note for this dbaretchef") {
          this.toastr.warning("You have already voted for this item");
        } else {
          // Handle other error cases here, if needed
          this.toastr.error("An error occurred while processing your vote");
        }
        this.ngxLoader.stop();
      }
    )
    console.log("Selected Value:", this.selectedValue);
    console.log("Selected id:", id);
  }
}
