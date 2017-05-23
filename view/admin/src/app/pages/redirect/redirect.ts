import { Component } from '@angular/core';
import { ActivatedRoute, Router,} from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'redirect-component',
  template: ''
})
export class RedirectComponent {
  url : string = null;
  constructor( activated: ActivatedRoute,
              location: Location
  ) {
    activated.params.subscribe( param => {

      let url = decodeURI( param['url'] );

      // console.log("Reload::constructor::subscribe() : ", url);
      
      location.back();
      
      
    } );
  }
}

