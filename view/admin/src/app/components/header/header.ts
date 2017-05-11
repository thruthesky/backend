import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { User, _USER_LOGOUT_RESPONSE } from 'angular-backend';

@Component({
    moduleId: module.id,
    selector: 'header-component',
    templateUrl: 'header.html'
})

export class HeaderComponent implements OnInit {
    constructor(
        public user: User,
        private router: Router
    ) { }

    onClickLogout() {
        this.router.navigate( [ '/' ] );
        this.user.logout().subscribe((res: _USER_LOGOUT_RESPONSE) => {
            console.log(res);
        }, err => {
            this.user.alert(err);
        });
    }

    ngOnInit() { }
}