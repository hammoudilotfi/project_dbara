import {Component, OnInit, ViewEncapsulation} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {CategoriesService} from "../../../services/categories.service";
import {ToastrService} from "ngx-toastr";
import {NgxUiLoaderService} from "ngx-ui-loader";

@Component({
  selector: 'app-recette-by-category',
  templateUrl: './recette-by-category.component.html',
  styleUrls: ['./recette-by-category.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class RecetteByCategoryComponent implements OnInit {
  public id
  public data;

  constructor(private activeRoute: ActivatedRoute, private router: Router, private modalService: NgbModal, private categorieService: CategoriesService, private toastr: ToastrService, private ngxLoader: NgxUiLoaderService) {

  }

  getRecepy() {
    this.ngxLoader.start();
    this.id = this.activeRoute.snapshot.params['id'];
    this.data = []; // Clear the existing data
    this.categorieService.getRecepieBycatId(this.id).subscribe(res => {
      this.data = res;
      this.ngxLoader.stop();
      console.log(this.data);
    });

  }

  ngOnInit() {
    this.activeRoute.params.subscribe(params => {
      // Update the data whenever the route parameter changes
      this.id = params['id'];
      this.getRecepy();
    });
  }
}
