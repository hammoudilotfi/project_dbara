import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { RecetteService } from '../../../services/recette.service';
import { ToastrService } from 'ngx-toastr';
import { NgxUiLoaderService } from 'ngx-ui-loader';

@Component({
  selector: 'app-chefs',
  templateUrl: './chefs.component.html',
  styleUrls: ['./chefs.component.css']
})
export class ChefsComponent implements OnInit {
  public data;
  public filteredData;
  public searchQuery = '';

  constructor(
    public dialog: MatDialog,
    private sanitizer: DomSanitizer,
    private activeRoute: ActivatedRoute,
    private router: Router,
    private modalService: NgbModal,
    private recetteService: RecetteService,
    private toastr: ToastrService,
    private ngxLoader: NgxUiLoaderService
  ) {}

  ngOnInit() {
    this.ngxLoader.start();
    this.recetteService.getChef().subscribe((res) => {
      this.data = res;
      this.filteredData = res;
      this.ngxLoader.stop();
    });
  }

  // Function to filter chefs based on searchQuery
  filterChefs() {
    this.filteredData = this.data.filter((chef) =>
      chef.nom.toLowerCase().includes(this.searchQuery.toLowerCase())
    );
    console.log( this.filteredData)
  }
}
