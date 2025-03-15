import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './home.component';
import {RouterModule, Routes} from "@angular/router";
import {CarouselModule} from "ngx-owl-carousel-o";
import { CarouselHolderComponent } from './carousel-holder/carousel-holder.component';
import {ToastrService} from "ngx-toastr";
import {NgxUiLoaderModule} from "ngx-ui-loader";




const routes: Routes = [
  {
    path: 'home',
    component:HomeComponent,
  },

]
@NgModule({
  declarations: [
    HomeComponent,
    CarouselHolderComponent,

  ],
  imports: [
    CarouselModule,
    RouterModule.forChild(routes),
    CommonModule,
    NgxUiLoaderModule,

  ],
  providers:[ToastrService]
})
export class HomeModule { }
