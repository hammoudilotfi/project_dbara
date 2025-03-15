import { Component } from '@angular/core';
import {Register} from "../../../model/register";
import {RegisterService} from "../../../services/register.service";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {Router} from "@angular/router";
import {ToastrService} from "ngx-toastr";

@Component({
  selector: 'app-sign-up',
  templateUrl: './sign-in.component.html',
  styleUrls: ['./sign-in.component.css']
})
export class SignInComponent {
  user: Register = new Register();

  constructor(private toastr: ToastrService,private registerService: RegisterService ,private ngxLoader:NgxUiLoaderService,private router: Router) { }

  onRegister() {
    this.ngxLoader.start()
    this.registerService.postUser(this.user).subscribe({
      next: response => {
        this.toastr.success('You are ready ', 'Registered');
        console.log('User registered successfully', response);

      },
      error: err => {
        this.toastr.error('something went wrong ', 'Error');
        console.error('Error registering user', err);
        this.ngxLoader.stop()
      },
      complete: () => {
        this.ngxLoader.stop()
        this.router.navigate(['/']);
      }
    });
  }


}
