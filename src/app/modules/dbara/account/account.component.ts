import {Component, OnInit} from '@angular/core';

import {ToastrService} from "ngx-toastr";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {AccountService} from "../../../services/account.service";
import {EditProfileComponent} from "./edit-profile/edit-profile.component";
import {PopupPasswordComponent} from "../../sign-in-singn-up/popup-password/popup-password.component";
import {MatDialog} from "@angular/material/dialog";


@Component({
  selector: 'app-account',
  templateUrl: './account.component.html',
  styleUrls: ['./account.component.css']
})
export class AccountComponent implements OnInit{
  public data
  constructor( private accountService: AccountService, private toastr: ToastrService, private ngxLoader: NgxUiLoaderService,private dialog: MatDialog) {

  }


  openPopup(): void {
    const dialogRef = this.dialog.open(PopupPasswordComponent, {
      width: '400px', // Adjust the width as needed
    });

    dialogRef.afterClosed().subscribe((result) => {
      console.log('The dialog was closed');
    });
  }
  ngOnInit() {
    this.ngxLoader.start()
    this.accountService.getUser().subscribe(res=>{
      this.data=res
      this.ngxLoader.stop()
    })
  }
}
