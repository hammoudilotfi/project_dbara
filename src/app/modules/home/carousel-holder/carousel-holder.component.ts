import { Component } from '@angular/core';
import {OwlOptions} from "ngx-owl-carousel-o";

@Component({
  selector: 'app-carousel-holder',
  templateUrl: './carousel-holder.component.html',
  styleUrls: ['./carousel-holder.component.css']
})
export class CarouselHolderComponent {
  customOptions: OwlOptions = {
    autoWidth: true,
    center: true,
    startPosition: 1,
    margin: 10,
    mouseDrag: true,
    touchDrag: true,
    pullDrag: true,
    dots: false,
    loop: true,
    autoplay: true,
    autoplayTimeout: 2000, // Adjust this for desired speed
    autoplayHoverPause: true,
    smartSpeed: 2000,      // Adjust for transition speed
    autoplaySpeed: 2000,   // Make this consistent with smartSpeed
    slideTransition: 'linear',   // Ensures it uses the value of smartSpeed
    responsive: {
      0: {
        items: 1
      },
      400: {
        items: 2
      },
      760: {
        items: 2
      },
      1000: {
        items: 5
      }
    },

  }
}
