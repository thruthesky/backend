import { Component } from '@angular/core';
import { Backend } from "angular-backend";
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  constructor(
    private backend: Backend
  ){
    this.backend.setBackendUrl("http://backend.org/index.php");
  }

}
