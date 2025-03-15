import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SignInComponent } from './sign-up/sign-in.component';
import {RouterModule, Routes} from "@angular/router";
import { LoginComponent } from './login/login.component';
import {FormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {NgxUiLoaderModule} from "ngx-ui-loader";
import {ToastrModule, ToastrService} from "ngx-toastr";
import { PopupPasswordComponent } from './popup-password/popup-password.component';
import {MatDialogModule} from "@angular/material/dialog";

const routes: Routes = [
  {
    path: 'register',
    component:SignInComponent,
  },
  {
    path: '',
    component:LoginComponent,
  },
]
@NgModule({
  declarations: [
    SignInComponent,
    LoginComponent,
    PopupPasswordComponent
  ],
    imports: [
        ToastrModule.forRoot(),
        RouterModule.forChild(routes),
        CommonModule,
        FormsModule,
        HttpClientModule,
        NgxUiLoaderModule,
        MatDialogModule
    ],
  providers:[ToastrService ]

})
export class SignInSingnUpModule { }
