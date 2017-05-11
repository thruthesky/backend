import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';
import { AngularBackendModule } from "angular-backend";
import { AngularBackendComponents } from "./angular-backend-components/angular-backend-components.module"


import { AppComponent } from './app.component';
import { HomePage } from './pages/home/home';
import { AboutPage } from './pages/about/about';
import { UserPage } from './pages/user/user';
import { UserLoginPage } from './pages/user-login/user-login';
import { ForumPage } from './pages/forum/forum';
import { HeaderComponent } from './components/header/header';

const appRoutes: Routes = [
  { path: 'forum', component: ForumPage },
  { path: 'users', component: UserPage },
  { path: 'login', component: UserLoginPage },
  { path: 'about', component: AboutPage },
  { path: '', component: HomePage, pathMatch: 'full' },
  { path: '**', component: HomePage }
]

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    HomePage,
    AboutPage,
    UserPage,
    UserLoginPage,
    ForumPage
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    RouterModule.forRoot( appRoutes ),
    AngularBackendModule.forRoot(),
    AngularBackendComponents
  ],
  exports: [],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
