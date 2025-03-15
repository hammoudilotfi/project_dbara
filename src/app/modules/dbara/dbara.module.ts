import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DbaraLiveComponent } from './dbara-live/dbara-live.component';
import { DbaraReelComponent } from './dbara-reel/dbara-reel.component';
import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "../home/home.component";
import {NgxUiLoaderModule} from "ngx-ui-loader";
import {MatDialogModule} from "@angular/material/dialog";
import { ChefsComponent } from './chefs/chefs.component';
import { WishlistComponent } from './wishlist/wishlist.component';
import { AccountComponent } from './account/account.component';
import { JeuxComponent } from './jeux/jeux.component';
import {FormsModule} from "@angular/forms";
import { EditProfileComponent } from './account/edit-profile/edit-profile.component';
import {ToastrModule, ToastrService} from "ngx-toastr";


const routes: Routes = [
  {
    path: 'dbara-live',
    component:DbaraLiveComponent,
  },
  {
    path: 'dbara-reel',
    component:DbaraReelComponent,
  },
  {
    path: 'chef',
    component:ChefsComponent,
  },
  {
    path: 'wishlist',
    component:WishlistComponent,
  },
  {
    path: 'my-account',
    component:AccountComponent,
  },
  {
    path: 'jeux',
    component:JeuxComponent,
  },
  {
    path: 'edit-profile',
    component:EditProfileComponent,
  },

]
@NgModule({
  declarations: [
    DbaraLiveComponent,
    DbaraReelComponent,
    ChefsComponent,
    WishlistComponent,
    AccountComponent,
    JeuxComponent,
    EditProfileComponent
  ],
  imports: [
    RouterModule.forChild(routes),
    ToastrModule.forRoot(),
    CommonModule,
    NgxUiLoaderModule,
    MatDialogModule,
    FormsModule
  ],
  providers:[ToastrService ]

})
export class DbaraModule { }
