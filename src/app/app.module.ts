import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppComponent } from './app.component';
import {SharedModule} from "./shared/shared.module";
import {RouterModule, RouterOutlet, Routes} from "@angular/router";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";
import {ToastrModule} from "ngx-toastr";
import {AuthInterceptor} from "./auth.interceptor";




const routes: Routes = [
  {
    path: '',
    loadChildren: () => import('./modules/sign-in-singn-up/sign-in-singn-up.module').then(m => m.SignInSingnUpModule)
  },

  {
    path: '',
    loadChildren: () => import('./modules/home/home.module').then(m => m.HomeModule)
  },
  {
    path: '',
    loadChildren: () => import('./modules/recette/recette.module').then(m => m.RecetteModule)
  },
  {
    path: '',
    loadChildren: () => import('./modules/dbara/dbara.module').then(m => m.DbaraModule)
  },


];
@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    ToastrModule.forRoot(),
    RouterModule.forRoot(routes, {
      initialNavigation: 'enabledBlocking',
      scrollPositionRestoration: 'enabled',
      useHash: true

    }),
    BrowserAnimationsModule,
    SharedModule,
    RouterOutlet,HttpClientModule,
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true,
    },
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
