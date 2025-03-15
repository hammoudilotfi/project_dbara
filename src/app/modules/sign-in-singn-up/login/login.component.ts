import {Component, OnInit} from '@angular/core';
import {LoginService} from "../../../services/login.service";
import {Login} from "../../../model/login";
import {Router} from "@angular/router";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {ToastrService} from "ngx-toastr";
import {MatDialog} from "@angular/material/dialog";
import {PopUpComponent} from "../../../shared/pop-up/pop-up.component";
import {PopupPasswordComponent} from "../popup-password/popup-password.component";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  user: Login = new Login();
  public yourJWT: string;
  public rememberMe = false;


  constructor(private ngxLoader:NgxUiLoaderService,private registerService: LoginService,private router: Router, private toastr:ToastrService,private dialog: MatDialog) { }
  openPopup(): void {
    const dialogRef = this.dialog.open(PopupPasswordComponent, {
      width: '400px', // Adjust the width as needed
    });

    dialogRef.afterClosed().subscribe((result) => {
      console.log('The dialog was closed');
    });
  }
  onLogin() {
    this.ngxLoader.start()
    this.registerService.login(this.user).subscribe({
      next: response => {
        console.log('User registered successfully', response['token']);
this.yourJWT = response['token'];
        if (this.rememberMe) {
          localStorage.setItem('jwt_token', this.yourJWT);
          localStorage.setItem('user_email', this.user.username);
          localStorage.setItem('rememberMe', 'true');
        } else {
          sessionStorage.setItem('jwt_token', this.yourJWT);
          localStorage.removeItem('user_email');
          localStorage.removeItem('rememberMe');
        }
      },
      error: err => {
        this.ngxLoader.stop()
        this.toastr.warning("something went wrong","warning")

        console.error('Error registering user', err);
      },
      complete: () => {
        this.ngxLoader.stop()
        this.router.navigate(['/home']);
      }
    });
  }
  ngOnInit() {
    const storedEmail = localStorage.getItem('user_email');
    const storedRememberMe = localStorage.getItem('rememberMe');

    if (storedEmail) {
      this.user.username = storedEmail;
    }

    if (storedRememberMe) {
      this.rememberMe = storedRememberMe === 'true';
    }
  }
}
