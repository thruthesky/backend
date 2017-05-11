import { Component } from '@angular/core';
import { Router } from '@angular/router';
@Component({
    templateUrl: 'user-login.html'
})

export class UserLoginPage {
    error;

    constructor(
        public router: Router
    ){}
}


