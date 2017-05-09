import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';



import { AppComponent } from './app.component';
import { HomePage } from './pages/home/home';
import { AboutPage } from './pages/about/about';
import { UserPage } from './pages/user/user';
import { ForumPage } from './pages/forum/forum';


import { HeaderComponent } from './components/header/header';




const appRoutes: Routes = [
  { path: 'forum', component: ForumPage },
  { path: 'users', component: UserPage },
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
    ForumPage
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
