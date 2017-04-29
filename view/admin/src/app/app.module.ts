import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';



import { AppComponent } from './app.component';
import { HomePage } from './../pages/home/home';

import { HeaderComponent } from './../components/header/header';




const appRoutes: Routes = [
  { path: '', component: HomePage, pathMatch: 'full' },
  { path: '**', component: HomePage }
]

@NgModule({
  declarations: [
    AppComponent,
    HomePage,
    HeaderComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    RouterModule.forRoot( appRoutes )
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
