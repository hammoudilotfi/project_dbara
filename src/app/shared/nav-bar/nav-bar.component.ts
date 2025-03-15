import {Component, OnInit, ViewEncapsulation} from '@angular/core';
import {CategoriesService} from "../../services/categories.service";
import {Router} from "@angular/router";
import {MatDialog} from "@angular/material/dialog";
import {PopUpComponent} from "../pop-up/pop-up.component";

@Component({
  selector: 'app-nav-bar',
  templateUrl: './nav-bar.component.html',
  styleUrls: ['./nav-bar.component.css'],

})
export class NavBarComponent implements OnInit{

  data
constructor(private categorieService:CategoriesService,private router:Router,private dialog: MatDialog) {
}

  isUserMenuOpen: boolean = false;
  openPopup(): void {
    const dialogRef = this.dialog.open(PopUpComponent, {
      width: '800px', // Adjust the width as needed
    });

    dialogRef.afterClosed().subscribe((result) => {
      console.log('The dialog was closed');
    });
  }
  toggleUserMenu() {
    this.isUserMenuOpen = !this.isUserMenuOpen;
  }
getAllCat(){
  this.categorieService.getCat().subscribe({
    next: response => {
     this.data = response
      console.log(this.data)
    },
    error: err => {
      console.error('Error registering user', err);
    },
    complete: () => {

    }
  });
}
ngOnInit():void{
    this.getAllCat()
}


  logOut() {
    localStorage.removeItem("jwt_token")
    this.router.navigate(['/'])
  }
}
