import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { User, _USER_LOGOUT_RESPONSE } from 'angular-backend';

import { Observable, Subject } from 'rxjs';


@Component({
    moduleId: module.id,
    selector: 'header-component',
    templateUrl: 'header.html'
})

export class HeaderComponent implements OnInit {
    constructor(
        public user: User,
        private router: Router
    ) {



    //   var obs = Observable.create( (observer) => {
    //        setTimeout(()=>{
    //            observer.next("after timeout");
    //        }, 100);
    //        setTimeout(() => {
    //            observer.error( new Error('oo'));
    //        }, 1000);
    //        observer.next( 'first value' );
    //    } );
    //    obs
    //    .subscribe( res => {
    //        console.log('res of obs: ', res);
    //    }, e => {
    //        console.log("Got error", e);
    //    });





    }

    onClickLogout() {
        this.router.navigate(['/']);
        this.user.logout().subscribe((res: _USER_LOGOUT_RESPONSE) => {
            console.log(res);
        }, err => {
            this.user.alert(err);
        });
    }

    ngOnInit() { }
}