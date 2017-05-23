import { Component } from '@angular/core';
import { Backend } from "angular-backend";
import { environment } from './../environments/environment';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  constructor(
    private backend: Backend
  ){
    this.backend.setBackendUrl( environment.backendApi );
    console.log("backend api: ", environment.backendApi );
  }

}
