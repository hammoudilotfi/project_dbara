import {Component, OnInit} from '@angular/core';
import {RecetteService} from "../../../services/recette.service";
import {NgxUiLoaderService} from "ngx-ui-loader";

@Component({
  selector: 'app-wishlist',
  templateUrl: './wishlist.component.html',
  styleUrls: ['./wishlist.component.css']
})
export class WishlistComponent  implements OnInit {
  data
  constructor(private recetteService:RecetteService, private ngxLoader:NgxUiLoaderService) {
  }
  getRecettes(){
    this.ngxLoader.start()
    this.recetteService.getWishlist().subscribe({
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
