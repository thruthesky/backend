import { Component } from '@angular/core';
import { Subject } from 'rxjs/Subject';
import 'rxjs/add/operator/debounceTime';

import { User,
    _USER_RESPONSE,
    _LIST,
    _USER_LIST_RESPONSE,
    _USER_EDIT, _USER_EDIT_RESPONSE, _DELETE_RESPONSE
} from 'angular-backend';

@Component({
    templateUrl: 'user.html'
})

export class UserPage {

    paginationUsers = <Array<_USER_RESPONSE>> [];
    searchForm = <_USER_RESPONSE>{};
    searchQuery = <_LIST>{};

///search options /////
  no_of_current_page: number = 1;
  no_of_total_items: number = 0;
  no_of_items_in_one_page: number = 4;
  no_of_pages_in_navigator: number = 5;

    searchChangeDebounce = new Subject();
    constructor(
        public user: User
    ) {
        this.onChangedSearch();
        this.searchChangeDebounce
            .debounceTime(300) // wait 300ms after the last event before emitting last event
            .subscribe(() => this.onChangedSearch());
    }

    onPageClick($event) {
        //console.log('onPageClick::$event',$event);
        //this.currentPage = $event;
        this.loadSearchedData( $event );
    }


    onChangeSearch() {
        this.searchChangeDebounce.next();
    }

    onChangedSearch() {
        //console.log('onChangeSearch', this.searchForm);
        if (this.searchForm.id) {
            if (this.searchForm.id.length < 2 ) return;
        }
        if (this.searchForm.name) {
            if (this.searchForm.name.length < 2 ) return;
        }
        if (this.searchForm.email) {
            if (this.searchForm.email.length < 2 ) return;
        }
        if (this.searchForm.mobile) {
          if (this.searchForm.mobile.length < 4 ) return;
        }

        let cond = '';
        let bind = '';
        if (this.searchForm.id) cond += "id LIKE ? ";
        if (this.searchForm.id) bind += `%${this.searchForm.id}%`;

        if (this.searchForm.name) cond += cond ? "AND ( name LIKE ? OR middle_name LIKE ? OR last_name LIKE ? ) " : "( name LIKE ? OR middle_name LIKE ? OR last_name LIKE ? )";
        if (this.searchForm.name) bind += bind ? `,%${this.searchForm.name}%,%${this.searchForm.name}%,%${this.searchForm.name}%` : `%${this.searchForm.name}%,%${this.searchForm.name}%,%${this.searchForm.name}%`;

        if (this.searchForm.email) cond += cond ? "AND email LIKE ? " : "email LIKE ? ";
        if (this.searchForm.email) bind += bind ? `,%${this.searchForm.email}%` : `%${this.searchForm.email}%`;

        if (this.searchForm.mobile) cond += cond ? "AND mobile LIKE ? " : "mobile LIKE ? ";
        if (this.searchForm.mobile) bind += bind ? `,%${this.searchForm.mobile}` : `%${this.searchForm.mobile}`;

        this.searchQuery.where = cond;
        this.searchQuery.bind = bind;
        this.searchQuery.order= 'idx DESC';
        this.loadSearchedData();
    }


    loadSearchedData( page = 1 ) {

        this.paginationUsers = [];
        this.searchQuery.page = page;
        this.searchQuery.limit = this.no_of_items_in_one_page;
        this.user.list(this.searchQuery).subscribe((res: _USER_LIST_RESPONSE) => {
            console.info( 'loadSearchedData', res );
            this.paginationUsers = res.data.users;
            this.no_of_total_items = parseInt(res.data.total);
            this.no_of_current_page = parseInt(res.data.page);
        }, err => this.user.alert(err));
    }



    onClickEdit( user: _USER_RESPONSE ) {
        console.log( user ) ;
        let edit: _USER_EDIT = <_USER_EDIT> {
            id: user.id,
            name: user.name,
            email: user.email,
            gender: user.gender
        };
        this.user.edit( edit ).subscribe( (res: _USER_EDIT_RESPONSE) => {
            console.log("edit response: ", res);
        }, err => this.user.alert( err ) );
    }

    onClickDelete( id: string ) {

        console.log( id );

        this.user.delete( id ).subscribe( (res: _DELETE_RESPONSE) => {
            console.log("delete response: ", res);
            this.paginationUsers = this.paginationUsers.filter( ( user: _USER_RESPONSE ) => user.id != id );
        }, err => this.user.alert( err ) );
    }
}
