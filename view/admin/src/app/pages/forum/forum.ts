import { Component } from '@angular/core';
import {  PostConfig, PostData,
  _POST, _POSTS,
  _LIST, _POST_LIST_RESPONSE, _DELETE_RESPONSE, _CONFIG_EDIT_RESPONSE,
  _CONFIG, _CONFIGS, _CONFIG_CREATE, _CONFIG_EDIT, _CONFIG_CREATE_RESPONSE,
} from 'angular-backend';

import { Subject } from 'rxjs/Subject';
import 'rxjs/add/operator/debounceTime';
import {ActivatedRoute} from "@angular/router";


@Component({
    templateUrl: 'forum.html',
    styleUrls:['./forum.scss']
})

export class ForumPage {

  config_idx: number = null;
  searchPostForm: _POST = <_POST>{};

  posts: _POSTS = [];



  searchConfigForm: _CONFIG = <_CONFIG>{};
  postConfigs: _CONFIGS = [];
  configCreate: _CONFIG_CREATE = <_CONFIG_CREATE>{};

  searchQuery = <_LIST>{};

  no_of_current_page: number = 1;
  no_of_total_items: number = 0;
  no_of_items_in_one_page: number = 4;
  no_of_pages_in_navigator: number = 5;



  searchConfigChangeDebounce = new Subject();
  searchPostChangeDebounce = new Subject();


  constructor(
    private postData: PostData,
    private postConfig: PostConfig,
  ) {

    this.searchQuery['order'] = 'idx DESC';

    this.loadPostConfig();

    this.searchConfigChangeDebounce
      .debounceTime(300) // wait 300ms after the last event before emitting last event
      .subscribe(() => this.onChangedConfigSearch());

  }

  onConfigPageClick( $event ) {
    //console.log('onPageClick::$event',$event);
    //this.pageOption['currentPage'] = $event;
    this.loadPostConfig( $event );
  }


  onClickCreateForum() {
    this.postConfig.create(this.configCreate).subscribe( (res: _CONFIG_CREATE_RESPONSE ) => {
      console.log(res);
    }, err => this.postConfig.alert(err));
  }


  onChangeConfigSearch() {
    this.searchConfigChangeDebounce.next();
  }


  onChangedConfigSearch() {
    //console.log('onChangeSearch', this.searchConfigForm);

    if (this.searchConfigForm.id) {
      if (this.searchConfigForm.id.length < 2) return;
    }
    if (this.searchConfigForm.name) {
      if (this.searchConfigForm.name.length < 2) return;
    }
    if (this.searchConfigForm.description) {
      if (this.searchConfigForm.description.length < 2) return;
    }

    let cond = '';
    let bind = '';

    if (this.searchConfigForm.id) cond += "id LIKE ? ";
    if (this.searchConfigForm.id) bind += `%${this.searchConfigForm.id}%`;

    if (this.searchConfigForm.name) cond += cond ? "AND name LIKE ? " : "name LIKE ?";
    if (this.searchConfigForm.name) bind += bind ? `,%${this.searchConfigForm.name}%` : `%${this.searchConfigForm.name}%`;

    if (this.searchConfigForm.description) cond += cond ? "AND description LIKE ? " : "description LIKE ? ";
    if (this.searchConfigForm.description) bind += bind ? `,%${this.searchConfigForm.description}%` : `%${this.searchConfigForm.description}%`;

    this.searchQuery.where = cond;
    this.searchQuery.bind = bind;
    this.searchQuery.order= 'idx DESC';
    this.loadPostConfig();
  }

  loadPostConfig( page = 1 ) {


    this.postConfigs = [];
    this.searchQuery.page = page;
    this.searchQuery.limit = this.no_of_items_in_one_page;
    this.postConfig.list( this.searchQuery ).subscribe( (res: _POST_LIST_RESPONSE ) => {

      console.log(res);

      this.postConfigs = res.data.configs;
      this.no_of_total_items = parseInt( res.data.total );
      this.no_of_current_page = parseInt(res.data.page);

      this.postConfigs.map( (config: _CONFIG) => {
        config.created = ( new Date( parseInt(config.created) * 1000 ) ).toString();
      });


    }, err => this.postData.alert( err ));
  }

  onClickConfigEdit( config: _CONFIG ) {
    console.log(config);

    let edit: _CONFIG_EDIT = {
      id: config.id,
      name: config.name,
      description: config.description,
      moderators: config.moderators,
      level_list: config.level_list,
      level_view: config.level_view,
      level_write: config.level_write,
      level_comment: config.level_comment
    };
    this.postConfig.edit( edit ).subscribe( (res: _CONFIG_EDIT_RESPONSE ) => {
      console.log("edit response::" ,res);
    }, err => this.postConfig.alert(err));
  }

  onClickConfigDelete( _config ) {
    if( _config.deleted == '1' ) return;
    console.log( _config );
    this.postConfig.delete( _config.id ).subscribe( (res: _DELETE_RESPONSE) => {
      console.log("delete response: ", res);
      if ( res.code == 0 ) {
        _config.deleted = '1';
      }
      //this.postConfigs = this.postConfigs.filter( ( config: _CONFIG ) => config.id != id );
    }, err => this.postConfig.alert( err ) );
  }

}
