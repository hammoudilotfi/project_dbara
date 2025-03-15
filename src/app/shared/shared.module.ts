import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NavBarComponent } from './nav-bar/nav-bar.component';
import { FooterComponent } from './footer/footer.component';
import { PopUpComponent } from './pop-up/pop-up.component';
import {MatDialogModule} from "@angular/material/dialog";
import {MatIconModule} from "@angular/material/icon";
import {AngularFireModule} from "@angular/fire/compat";
import {AngularFireStorageModule} from "@angular/fire/compat/storage";
import {environment} from "../../environment/environment";
import {RouterLink} from "@angular/router";

const firebaseConfig = {
  apiKey: "AIzaSyD5TCCl5j6Ng30_zTM1Ur5zaDPFiad5hZo",
  authDomain: "dbara-c938b.firebaseapp.com",
  projectId: "dbara-c938b",
  storageBucket: "dbara-c938b.appspot.com",
  messagingSenderId: "122799242169",
  appId: "1:122799242169:web:79a8335153ea65ed85d468",
  measurementId: "G-57TMWBRHLP"
};

@NgModule({
  declarations: [
    NavBarComponent,
    FooterComponent,
    PopUpComponent
  ],
  exports: [
    NavBarComponent,
    FooterComponent
  ],
  imports: [
    CommonModule,
    MatDialogModule,
    MatIconModule,
    AngularFireModule.initializeApp(environment.firebaseConfig),
    AngularFireStorageModule,
    RouterLink
  ]
})
export class SharedModule { }
