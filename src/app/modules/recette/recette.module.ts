import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RecetteComponent } from './recette/recette.component';
import {RouterModule, Routes} from "@angular/router";
import {SignInComponent} from "../sign-in-singn-up/sign-up/sign-in.component";
import {LoginComponent} from "../sign-in-singn-up/login/login.component";
import {NgxUiLoaderModule} from "ngx-ui-loader";
import { RecetteDetailsComponent } from './recette-details/recette-details.component';
import {NgbModule} from "@ng-bootstrap/ng-bootstrap";
import { RecetteByCategoryComponent } from './recette-by-category/recette-by-category.component';
import {MatDialogModule} from "@angular/material/dialog";
import {ToastrService} from "ngx-toastr";
import {FormsModule} from "@angular/forms";


const routes: Routes = [
  {
    path: 'recette',
    component:RecetteComponent,
  },
  {
    path: 'recette/detail/:id',
    component:RecetteDetailsComponent,
  },
  {
    path: 'recette/:id',
    component:RecetteByCategoryComponent,
  },

]
@NgModule({
  declarations: [
    RecetteComponent,
    RecetteDetailsComponent,
    RecetteByCategoryComponent
  ],
  imports: [
    MatDialogModule,
    NgbModule,
    RouterModule.forChild(routes),
    CommonModule,
    NgxUiLoaderModule,
    FormsModule
  ],
  providers:[ToastrService]
})
export class RecetteModule { }
