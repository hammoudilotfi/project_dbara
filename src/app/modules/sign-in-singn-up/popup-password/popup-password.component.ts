import { Component } from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {LoginService} from "../../../services/login.service";
import {Router} from "@angular/router";
import {ToastrService} from "ngx-toastr";

@Component({
  selector: 'app-popup-password',
  templateUrl: './popup-password.component.html',
  styleUrls: ['./popup-password.component.css'],

})
export class PopupPasswordComponent {
  newPassword
  constructor(public dialogRef: MatDialogRef<PopupPasswordComponent> ,private ngxLoader:NgxUiLoaderService,private registerService: LoginService,private router: Router, private toastr:ToastrService) {
  }

  changePassword(){
    let objectToSend={
      new_password:this.newPassword
    }
    this.ngxLoader.start()
    this.registerService.resetPassword(objectToSend).subscribe( res =>{
      this.toastr.success("password changed successfully")
      this.ngxLoader.stop()
      this.dialogRef.close();
    })
  }
  closeDialog(): void {
    this.dialogRef.close();
  }
}
