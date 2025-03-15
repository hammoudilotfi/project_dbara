import {Component, OnInit} from '@angular/core';
import {RecetteService} from "../../../services/recette.service";
import {NgxUiLoaderService} from "ngx-ui-loader";

@Component({
  selector: 'app-recette',
  templateUrl: './recette.component.html',
  styleUrls: ['./recette.component.css']
})
export class RecetteComponent implements OnInit {
  data
  constructor(private recetteService:RecetteService, private ngxLoader:NgxUiLoaderService) {
  }
  getRecettes(){
    this.ngxLoader.start()
    this.recetteService.getAll().subscribe({
      next: res=> {
        this.data=res
        console.log(this.data)
        this.ngxLoader.stop()
      },
      error:err => {
        console.log(err)
      },

    })
  }

  ngOnInit() {
    this.getRecettes()
  }
}
