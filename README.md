# Backend-0.8

Backend Server for Restful APIs


# Changes

* post_data.create/edit interface response with the full post data.
* post_comment create/edit interface response with the full comment data.
* post_data.data interface accepts `extra` property.
* when user logs in, if the user is admin, "admin=1" will be set in response.
* when user related information is responded, at least it has empty array if it has no photos.
* When user has no primary photo, the responded empty value is Array.
* When user has primary photo, the responded value is Object. NOT Array.
* 'created' URL parameter is added on File URL for cache. Since a user deletes a file and upload a new file, then, it has same file.idx so, the browser cached image will be showed again to user..
* default 'no. of items' of a page(list/search page) is '$DEFAULT_NO_OF_PAGE_ITEMS' which is in etc/config.
* search/list api call now accepts 'page' and works as exptected.
* when admin edits user information, session_id will not be returned. so, the session_id will be invalidated after 'admin edit'
    * but, user edits user info, then session_id will be returned.
    * so, admin edits admin and no session_id will be returned, meaning, admin has to login again.
* you cannot update post_config_id. ( consider to make it updatable ).

* @fix: returns-post_config-of-requested-post-config-id-if-no-posts. Apr 11, 2017. When there is no post ( no posts are created. newly created forum ), then the no post-config information is responded. so, if there is no posts, the requested post config id's 'post config' will be responded by default.

* 'first_image_idx' is added on 'post_data' table. it holds the `file.idx' of first image.

* If there is an error while DATABASE query, it prints out error response and exits the script immediately. ( since it is not is to deliver DB error message that is in the bottom part way back up to the top part callers ).

* 'user_idx' is added on meta table.
    so, a meta record is now able to refer to whom it belongs to.
    It is only used for "meta.list" ( search ) and "meta.delete".

* 'link' is added on post_data table to hold link information.
* no 'meta.data' interface? omitted by purposely? because there is no 'meta.update', no 'meta.data'? since meta is not for individual manipulation?



# Bugs

* On macOS Sierra 10.12.3 with SQLite,
    when a user upload primary photo and delete immediately, the file is deleted but after 0.3 seconds it becomes alive with Zero Bytes.
    And it produces an server error since SQLite will use the same file.idx for next photo upload.
    ( delete immediately means, right after, ... like 0.1 second after upload )
    If you give some time delay like 3 seconds, there is no error.



# Resources

* @see tests to understand Backend.


# Work Environment

## Work environment of thruthesky

* http://localhost/www/backend/ for backend restful api access.
* http://backend.org/ is same folder of http://localhost/www/backend
* http://backend-seo.org/ for seo test. ( in this cone, Backend has many of client app files, so it looks a bit dirty )





# TODO

- For next version.
    Create Error Object and return all the error information.
    For instance, if SQL insert error happens, the error message is not delivered to the client.
    

- ADMIN PAGE with Angular 4 on "http://backend.org/view/admin/www/"
    ./view/admin/ is Angular Project for admin.
    


- Error handling after 'db()->row()' or 'db()->rows()'. it should return right error message of DB.
    @see entity()->load() for detail.
- Write usage on category
- Use category for forum.



- check/select primary photo among others in forum post.

- admin file management.
    show how many unhooked, show many old unhooked.
    show satistics.
       
- upload file with angular http formdata without file transfer of cordova.






* Check if file upload responses with right error message. for instance, too big file upload should response 'max-file-upload-size-limit-excedded' or sonmething but the error responseded is like 'cannot create idx'

* Support IE6 ~ IE9 by rendering nice HTML design.
	* Backend checks if the user uses IE6~9, then it only send HTML to browser, NOT angular or jQueryMobile things.


* max images sizes.
	* limit uploaded photo's width, height and size since user may upload too big file.


## Transaction
* prove pdo transaction is working with race condition. It is very important with playing point, user level system.




## add more tests.

* to check table is set.
* to add more tests.
* file proxy test.
* search test.
* file upload for comment, user profile.
* file download.


## Post

* secret post

* A post can be linked to other forum or other place.
	* post.linked_with field may be a way.
	    * but this will be a trouble on search.
	* copy may be one way.

* A forum can be subcategory of others.
	* category taxonomy may be needed.

## Test on post\_config, post\_post

* Not login user becomes anonymous.


````
config('abc')->countAll()
config('abc')->countPost()
config('abc')->countComment()
config('abc')->timeLastPost();
config('abc')->timeFirstPost();
config('abc')->timeLastComment();
config('abc')->timeFirstComment();
````

## Privacy

* is it okay to let api search by id and email? phone numbers? isn't it breaking privacy policy?


## User Level

* There is only one `super admin` who has all the previlegdes. It is set to `admin` by default and you can change it in ./etc/config.php
* `super amdin` can set maximum of 5 users to `sub admin`. `sub admin` can do whatever except setting `sub admin`. `sub admin` can view user private information. They can change user paassword, block user, change user level. They can also create forums and block forums. So be careful to set a user to `sub admin`
	* `sub admin` cannot
		* change `sub admin`
		* delete forum,
		* cannot empty deleted posts

* `manager` is a moderator of a forum or a cafe. They can;
	* manage a forum or a group or any category.
	* create a group and automatically become the `manager` of the cafe.


## Group, Cafe


to complete a portal.


## PUSH Notification.

use external lib



## SMS service

To reduce the money, SMS shouldn't send more than 1 or 2 times a day to a user.



## Sample Site


* Simple community site
* Buy and sell / Online shopping mall site.



## Multi installation - next version

- do it on next version.

* for backend server hosting
* do it based on sub-domain.




## Real Message System

To communicate between users.

* Each room has a configuration in `chat_config` table.
* Each chat message is save in `chat_message` table.
* For the same reason of `data integrity` of post, it does not hold `chat_config.time_last_message`. Do SQL query to get it.
* Users and Chat Rooms are N:M relation for group chatting. So, `chat_relation` table will have the relation ship.
* If a user leaves a chat room, then he will loose all the data.
	* When a last user leaves from a chat room, the cat room will be destroyed.
	* This condition perfectly makes it work like facebook chat or kakaotalk.
* For new message indication, every time a user gets a message from a room, the time of the message will be recorded in `chat_relation.time_of_last_message`. When a user visits(checks) the chat rooms, any chat room has newer message then the `chat_relation.time_of_last_message`, then the room has a new message that the user didn't read.



## LONG-TERM TODO


This section describes what to do in next version.

* move core module in core folder.







# Installation

## Requirement

* php 7.x
* GD
	* GD is used by etc/external/ImageResize.php




## How to install Backend

````
http://localhost/www/backend-0.2/?route=install
````



## How to install SEO functionality

To do SEO, the home page(hosting site) of client app must be in the same place of Backend root folder. So, Backend can patch client app's index.html with SEO codes and delivers to users.


1. Just copy all the resources(all files) of Client app into the Backend root folder.
	* Client app must have `index.html` as its entry.
	Example) How to copy client app files into Backend root folder.
	````
	cp -r ~/apps/community-app/dist/* /Backend-root-foler/
	````
	
2. Configure the server.

	See examples of nginx server configuration in `Nginx Configuration for SEO` section of this document.
	
	* Domain
	* Set the directory index to `index.php`
	* Rewrite to `index.php`

3. Edit ./etc/seo.yaml for detail configuration of SEO





## What would be installed

### User accounts
 
3 account will be created at installation

    * `admin` for admin account. Admin account can be changed. @refer 'Admin' section.
    
    * `anonymous` for anonymous
    
    * `user` for a normal user. this is more like a  test purpose.


All these accounts can be changed on config.php


### Test forum

* A forum with `test` id will be created for a test purpose.
* This forum is heavily used while testing.




## For developer, How to code for installation

You can put any php script ending "_install.php" under a module folder.

Those files will be run in installation process.





# Interface

Is more likely a router.



# Permission

When read/write/delete data, it needs security level to limit/allow who can do 'CRUD' of data.

It is stated in interface.


guest = anonymous, not logged in user, visitor.
anyone = anyone where logged in or not logged in.
owner = who owns the data.
admin = admin level user.


# REST API

Only 'route' method can use 'success()' or 'error()'.

Any methods that are not 'route' should return a value.







# Unit Test

Model test scripts are on the same folder of each model.

* By default, if you access "?route=test", you will run all the tests in the system.

* You can run a test of an indivisual class/model by adding router.


* Test script must end with with "_test.php".


##  Run all tests

To run all the tests, just access

````
?route=test
````


## Run a test of a class/model

**1.** To run a test from a test class, you need to router.

Example code) Creating a router for a test in taxonomy model.

````
add_route('taxonomy.test.run', [
    'path' => "\\model\\taxonomy\\taxonomy_test",
    'method' => 'run'
]);
````


**2.** Create test file.

````
<?php
namespace model\taxonomy;
class Taxonomy_Test extends \model\test\Test {
    public function run() {
        test(1, "taxonomy test begins");
    }
}
````

**3.** Run the route to run the test.

````
http://localhost/index.php?route=taxonomy.test.run
````


## Working with Unit Test

### Running a test file and extending Test class

You need to extend `Test` class to facilitate the test functionality.


Route for a test class


````
route()->add( 'hook.test', [
    'path' => '\\model\\hook\\hook_test',
    'method' => 'run',
    'validator' => function() {
        \model\test\Test::$reload = 1;
    }
]);

````
$


#### Refresh Interval.

You can adjust refresh time in validator.

Test class )

````
<?php
namespace model\hook;
class Hook_Test extends \model\test\Test
{
    public function run( ) {
    }
}
````











# Class Hierarchy


````
Base => Taxonomy
Baes => Taxonomy => Entity
Base => Taxonomy => Entity => User
Base => Taxonomy => Entity => Meta
Base => Taxonomy => Entity => Meta => Config
Base => Taxonomy => Entity => Forum => Config
Base => Taxonomy => Entity => Forum => Post
Base => Taxonomy => Entity => File
````


# Security




# Security is important.

## ./etc/config.php settings.

you can set $MAX_REQUEST_LENGTH to limit max size of user input.


## php.ini settings

Limit max request size and other options.

## database settings.

Limit max packet size.




## HTTP Input Variable Check

The "run_route()" will check the input of the HTTP as stated in the route.

In a route, you might see the `variables`.

````
add_route('register', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'register',
    'variables' => [
        'required' => [ 'id', 'password' ],
        'optional' => [ 'domain', 'name', 'middle_name', 'last_name',
            'nickname', 'email', 'gender', 'birth_year', 'birth_month', 'birth_day', 'landline',
                        'mobile', 'address', 'country', 'province', 'city', 'zipcode' ],
        'system' => [ 'route' ]
    ]
]);
````

The `variables` states what variables the interface accepts.

* If any variables that are not states above has delivered to interface, then the `run_route()` will reject with error.
* The variables of `required` in `variables` are required. If any of the required variables is missing, then `run_route()` will reject the request with error.
* `optional` variables are optional.
* `system` variables are the variables that are used by the system and interface. `system` variables are not related in content like user information, forum data and any kind of data on database. 
* `required` and `optional` variables are related in content( data, database data). Any data like user information, forum posts, logs that shuold be saved in the database must be in one of `required` or `optional` variables.
* The HTTP input variable `route` and `session_id` can be omitted since all access needs a route and most of route needs `session_id`. There is no need to put `session_id` in required variables since it is needed in most route and it is less harmful even it is provided when you don't needed it for one route.
If you really don't want it for a route, reject it in the interface.




## Basic Route(HTTP) variable type checking

* For security enhancement, types of Route(HTTP) variable are checked by the system.
* Since many of Route variables have same(similar) names and types,
Backend does basic type check based on the name of the route vairable.
* See see `Router::validate_route_variables()` for details.




## Route Validation

You can do a deep validation aside from route's variables and basic route variable checking.

The `validator` property of router is anonymous function that will return OK when everything is okay. Or it should return error.

* `validator` cannot return error response. It can only return ERROR CODE or ERROR ARRAY


Example of validator )
````
add_route('register', [
    'path' => "\\model\\user\\user_interface",
    'method' => 'register',
    'variables' => [
        'required' => [ 'id', 'password' ],
        'optional' => [ 'domain', 'name', 'middle_name', 'last_name',
            'nickname', 'email', 'gender', 'birth_year', 'birth_month', 'birth_day', 'landline',
            'mobile', 'address', 'country', 'province', 'city', 'zipcode',
            'meta'
        ],
        'system' => [ 'route', 'file_hooks' ]
    ],
    'validator' => function() {
        if ( currentUser()->logged() ) return ERROR_USER_LOGGED_IN;
        if ( currentUser()->id = 'u' ) return [ 'code'=>-1234, 'message'=> 'error message' ];
        return OK;
    }
]);
````


### Route Validation and Variable Injection


* Return values of route `validator` is not ERROR, then the return value will be passed over the interface.


Example of validator )

````
route()->add( 'post_comment.create', [
    'path' => '\\model\\post\\post_comment_interface',
    'method' => 'create',
    'variables' => [
        'required' => [ 'parent_idx' ],
        'optional' => $_optional
    ],
    'validator' => function() {
        if ( currentUser()->isAnonymous() && empty( in('password') ) ) return [ 'code' => ERROR_PASSWORD_EMPTY, 'message' => "Anonymous must input a password to create a post." ];
        $post = post( in('parent_idx') );
        if ( ! $post->exist() ) return ERROR_POST_NOT_EXIST;
        $config = config()->load( $post->post_config_idx );
        if ( ! $post->exist() ) return ERROR_POST_NOT_EXIST;
        return [ $post, $config ];
    }
]);
````


Example of Interface) See how it gets variable passed from validator.

* If you are using phpStorm, setting param with type hinting will populate intelligence.

````
    /**
     *
     * @param Post_Data $post
     * @param Post_Config $config
     * @return mixed
     *
     */
    public function create( $post=null, $config=null ) // for variable compatibilities.
    {
        // $post-> ...
        // $config-> ...
    }

````






# Interfaces

Interfaces are the methods that are directly called by router/API call.

All interfaces must be recorded in `{module_name}_interface.php` except common interfaces.

For instance

````
model/user/user_interface.php
````


## Common Interfaces

Some interfaces could have same functionality and same routine.

You can create a common interfaces in a parent class ( or parent model ) to share parent's interfaces with its children.

Be sure to name it with ending '_interface'.

for instance

````
public function like_interface( ... ) { }
````



# Data Relation and Database


## User

### Anonymous User.

Users who are not logged in with their ID and password will login as anonymous. Meaning, All users are logged in as anonymous when they first visit the site.


* Anonymous is a user who did not log in with his password but treated as logged in.
* Anonymous user cannot login, logout, edit his information.
* But can post/edit/delete with password.


### Test Users

* There are two test users. one of them is `user` ( whose id is simply `user` ) and the other is `thruthesky` ( whose id is `thruthesjy` )
* These two users are for `tests`.



### User Delete




## meta table

Meta table holds meta datas.

It has model, model_idx, code and value fields plus idx, created and updated.

### model

meta.model field is for a big category like "user", "forum", "file".

### model_idx

meta.model_idx is to associate the meta data with the object(entity/record) of the model.


### code
meta.code is a sub-category for the meta. It would be a property of a entity like "facebook address", "google plus address" of a user.


## Category

`Category` model is to manage tree like data structure. It has a parent and has children and childrent's children hierachical structure. `Category` model has methods like `getParents()`, `loadParents()`, `getBrothers()`, `loadBrothers()`, `getChildren()`, `loadChildren()` dirived fron `Taxonomy` model and will help you to handle tree kinds of data structure. You can use this as category management of `forum` or `shoppmall`.

See category tests to get some sample codes.




## Post

Post can be served in many ways like forum posts, blog posts, group( cafe ) posts, comments on an image, etc. So, the name of the functionality shouldn't be something like 'forum'. Instead, It should be a simple `post` to serve variety of functionality.



### post_config table

Post categories ( or settings ) are saved in `post_config` table.

* `post_config.moderators` holds forum moderators to manage the forum. It is a string of IDs separated by comma.


* Only admin can create, edit, delete `post_config` but any can read it. So don't put any critical information on it.

* When a forum is deleted, the record of `post_config` is NOT deleted. Instead, it is marked as deleted.


### post_data table

Posts are saved in `post_data` table.
Comments are saved in `post_data` table together with post for easy managibility and for easy search.


* `post_data.root_idx` and `post_data.parent_idx` are 0 if it is a post ( not a comment. )

* `depth` begins with 0 that means, first children has 0 of depth.

### Utility properties.

`post_config` table does not has a field like `post_config.no_of_posts` to hold the number of posts due to the `data integrity`. For instance, you can get no of posts easy by querying `SELECT COUNT(*) FROM post_data WHERE idx_config=123` and it is fast enough.

And for the same reason, `post_config` table has no field like `post_config.time_of_last_post` to maintain when was the time that a post created on that post category. You can query it and it is really fast enough.

MySQL/MariaDB has query cache funtionality also.

Having extra fields to keep post information may take extra care and often produce bugs that break `data integrity`.




# Constants

## Okay and ERROR Constant

Among other constant, there is one thing to notice. That's OK and ERROR.

````
define('OK', 0);
define('ERROR', FALSE);
````

OK tells an action was success while ERROR tells the action was an error.

But both have falsy value. Meaning when you code like below

	if ( OK ) { ... }  // this will NOT run.
	else { ... }       // this WILL run.


so, you need to use `===` to compare it was success or error. `is_error()`, `is_success()` handles this nicely.



# Proxy


Proxy is an unified way to manage its meta or other dependency information like post_data files
and it is available on all model since the proxy is existing on `entity` model.



## Meta_Proxy



Meta Prxoy is a handy method to manage mata data of an entity. When `meta()` method of an entity is called, it creates an object of Meta_Injector with the model and model\_idx of the entity and returns the object.


````
user( 'abc' )->meta()->get();        // gets all meta data of user 'abc'
post( 1 )->meta()->get('birthday');  // gets bitrh meta data of post no 1.
user( 'abc' )->meta()->set([...]);   // sets an array of meta to user 'abc'.
config( 33 )->meta()->set( 'title', '...' ); // sets title meta to post config 33.
user( 'def' )->meta()->delete();     // delete all meta of user 'def'
user( 'def' )->meta()->delete( 'birthday' ); // delete birthday meta of user 'def'.
````


# Image Optimization


Speed of Web/WebApp depends on the size of contents that it needs to download to display it in browser.

There are many ways of image optimation.

Mainly there are two ways

1. Resize images. so extra bits will not be downloaded. This is the easiest way to customize. 
2. Compress images.



There are many image libraries and we could even develop one by by ourselves. But we use [php-image-resize](https://github.com/eventviva/php-image-resize). This is very simple and easy to use.

Refer [examples of its github repository](https://github.com/eventviva/php-image-resize/blob/master/test/Test.php).

Refer [API explanation page](https://eventviva.github.io/php-image-resize/class-Eventviva.ImageResize.html)






## Image download option as optimization

### options

`type` can be 'jpg', 'png'. @note 'jpg' works better. so try to use 'jpg' instead of 'png'.

`width` is the number of width in px.

`height` is the number of height in px.

`quality` is the number of quality of image. for jpeg, it is between 1~100, for png, it is between 1-10. `quality` is working only if `type` is set to 'jpg' or 'png'. 

@attention `quality` with `type` png is not working property.

@attention `quality` works only when `type` is specified. so, use `quality` with `type=jpg`.




`resize` can be 'best-fit', 'one-iemension', 'resize', 'crop', 'freecrop'.


`demension` can be 'width', 'height'. `dimension` is only used when `resize` is set to 'one-dimension'.

`x`,`y` is only used when `resize` is set to 'freecrop'



* 'resize' just resizes the size by width and height. This is default. The result will be in SKEW. `width` and `height` are mendatory or else error.
	* http://backend.org/index.php?route=download&idx=572&type=jpg&width=200&height=200&
* 'best-fit' makes the best fit for the max `width` and max `height`. One of dimension will become max size, but the other may be less than the size. For instance, image is 400px, 800px and 'best-fit' of *200px x 200px* may lead *100px x 200px*
	* ?route=download&idx=572&type=jpg&width=200&height=200&resize=best-fit
* 'one-dimension' wil fit to one demonsion only. one of `width` or `height` is needed. if both specified, `width` will take in place.
	* ?route=download&idx=572&type=jpg&width=200&resize=one-dimension
* 'crop' - If an original image is *400px x 600px* and *width, height is 200px x 200px*, 
	* then it first scales down to fit minimum of *200px x 300px* because 'width' is set to 200px and the best fit is *200px 300px'
	* and it crop off top 50px and bottom 50px to make it look good.
	* ?route=download&idx=572&type=jpg&width=200&height=200&resize=crop

* 'freecrop' - 'crop' only crop off based on center. you can set x,y position of 'crop' starting pont.
	* ?route=download&idx=572&width=200&height=200&resize=freecrop&x=100&y=100&type=jpg



`enlarge` is an optional parameta that affects on resizing 'resize', 'best-fit', 'one-dimemsion'. if set to 'Y', then it enlarges. It does not work on 'crop'.




@note if any of `type`, `width` and `height` is not specified, it just gets original sizes.

@note 'scale' is NOT supported since it isn't needed in realy world. Each image has diffent sizes and scaling does not have any point.


@note You will mostly resize with 'crop' simply becase this is the easist way to resize images. so, it has a special parameter `crop`. it takes width and height in "000x000" format. for instance `crop=100x200` means, Backend will crop images in width 100px and height 200px. You can set `quality` on the third number on `crop` like "`width`x`height`x`quality`". If you set `quality`, then the `type` becomes `jpg` automatically.

* Use `crop=11x22x33` as much as you can.
	* Example ) ?route=download&idx=575&crop=500x400x100


* Example) how to use it in Angular Template

````
<img [src]=" file.url + '&crop=100x100x50'  " style="width: 100%;">
````










# Admin

* `admin id` is set in ./etc/config.php and if you want, you can chage it to any user id and that id becomes admin.
* admin password is the same as `admin id`. so, by default, admin id is `admin` and the password is `admin`. so, you can login as admin with `admin` as ID and `admin` as password.
* You need to change the password of the admin immediately after you install.



# API

Clients will use API to communicate with Backend. API is mostly about how to use the routes and interfaces of Backend.


## Common API


### Data

Gets the data of the `model.idx`.

request)

````
?route=data&session_id=xxxx&idx=xxxx
````

response)

````
{
  code: 0,
  data: {
	...
  }
}
````

* all request must have `model.idx`.
* response data varies depending on the model.



### List & Search

request)

````
?route=list&session_id=xxxx&select=xxxxx&from=xxxxx&where=xxxx&bind=xxxxx&order=xxxx&limit=xxxxx&extra=xxxxx
````

* `extra` varies on each model.
* `extra` will be handled on each model's `pre()` method.

Example of list request with `extra` )

````
    let list: LIST = {
        select: 'idx, title, created',
        order: 'idx DESC',
        extra: { file: false, meta: true }
    };
````

* Properties of extra can have one of "true, 1, 'Y'" indicating as positive inquery to get the data.
* You can set falsy values like 'false', '0', '' If you don't want the data of the property. Be careful that 'N' is truthy value.
* If `extra` is omitted, Backend will reponse with all the extra information by default.
* @see `post_list` API section to know more about `post_data` search with `extra` property.



### Delete

Each model has a delete functioanlity and Backend provides one united way to do it on each model.

The request can provide `idx` as model entity idx or `id` as model entity id if the entity has `id` field.

request with `idx`)

````
?route=delete&session_id=xxxx&idx=xxxx
````

response of `request with idx`)

````
{
  code: 0,
  data: {
    idx: number
  }
}
````

When request provide `idx`, then response will return the `idx` of deleted entity.

or


request with `id`)

````
?route=delete&session_id=xxxx&id=xxxx
````


response of `request with id`)

````
{
  code: 0,
  data: {
    id: string
  }
}
````

As the same idea of providing `idx`, when request provide `id`, then the response will return the `id` of deleted entity.




## USER

### Login

#### Request

route : `?route=login`

param : `id=USER_ID&password=USER_PASSWORD`


#### Response

* Regenerated `session_id`
* User ID
* User name
* User email

### Logout


#### Request

route : `?route=logout`

param : `session_id=USER_SESSION_ID`



#### Response

none.


### User Edit

#### Admin can edit user information.

* When admin edits user information, user's session id will become invalid. So the user needs to login again.
* When admin changes user information, admin's session id will be not regenerated. And there will be no session_id returned from the request if admin edits user information.



## File Upload


When you successfully uploaded a photo, you will get a file.idx and you can do whatever you want with the file.idx.


#### Request

route : `?route=upload`

params :

* optional`session_id` - User session id.
* optional `model` - if unset, backend may set.
* optional `model_idx` - if unset, backend may set.
* optional `code` - if unset, backend may set.
* optional `uniqie` - if it is set to 'Y', then all previously uploaded file with same `model`, 'model_idx`, `code` will be deleted.
* optional `finish` - if it is set to 'Y', then backend will mark the uploaded file as finished, meaning the fill will not be deleted by grabage trashing.


When `unique` is set to 'Y' on request, backend will delete previously uploaded files.
**Fitfall with `unique` option** is that, if a user don't submit the form( for instance, if the user stops edit profile and do not submit the edit form ) after primary photo upload with `unique` set to 'Y', then even though the user did not submit the profile edit form, all the primary photo has been deleted already due to `unique` of 'Y'. To avoid this, it is better to handle `unique` option on server side(backend) or send `finish` optional param with 'Y' value together with `unique` so, backend will delete old files and upload new file and mark it as `finish`ed.


#### Response

file idx


### Common pitfalls

#### File uploading

There are many file size restriction conditions on server side and you must pass/update them all to upload a big size file.

* in php.ini
	* post\_max\_size
	* upload\_max\_filesize
	* max\_file\_uploads - for uploading multiple files.
* in nginx configuration
	* client\_max\_body\_size

You may need to change other configurations depending on your server environment.





### File Upload Hook

To hook image(s) you uploaded to an entity, you just send the file idx(es) in `file_hooks` array of http variables to the server. And the serve will attach those files(images) to the entity.








### Image Download

@see Image Optimization




## POST

### POST LIST

* If extra.file of request is set to true, you will get uploaded file information.
* If extra.meta of request is set to true, you will get meta data of the post.
* If extra.comment of request is set to true, you will get comments
* If extra.user of request is set to true, you will get user information.





# Model Initialization

Backend will load and run `*_init.php` scripts under each model. The purpose of this scripts is for initializing model like writing `hooks`, loading some data for the model, etc.


* Be sure that you keep light `*_init.php` scripts.
* `*_init.php` is a good place to write hooks.




# Hooks

Hook is one way to alter the behavior backend. By hooking, you can inject your code deep into Backend and do whatever you want.

* Best cases to write hooks are that you want to change the behavior of Backend but you do not want to touch the core code because

	* your changes will be deleted or have to rewrite when Backend updates.
	* your changes may not be sharable if you change it in Backend core code.

* To write hook codes, create your own model and hook something by putting the code in `*_init.php`

* Hooks are not for altering the output or JSON response. Be careful not to print out anything from hooks.

## How to hook

````
add_hook( 'after_route_load', function() { } );         // Run after route load complete.
add_hook( 'after_route', 'user.register', function() use ( $var1 ) { } );       // Run after user register interface.
````


## Hook List


You can use 'before', 'after' to put the hook 'before' or 'after' an action.

for instance: `before_route_load`, `after_route_load`, `after_route` with additional variable 'user.register'



* `after_route_load` - runs after all route had been loaded.
* `before_route` - runs when route procedure begins.
* `before_interface` - runs before interface is called.
* `after_interface` - runs after interface finish. Note: interface may print out 'success' or 'error' response to browser.
* `after_route` - runs after route finish. This means all the work has been finishd and the script will end.


Note: if you only want to run hook for 'user.register' route, then you need to do conditional exception on your hook that if the current route is not 'user.register', then don't do anything but just return.


Example) Hook that runs only on specific route.

````
hook()->add( 'after_route', function () {
    $route = in('route');
    if ( $route != 'hook.test' ) return; // return if it's not your hook.
    
    test( true, "Do something for hook.test route");
});
````


# SEO


Since Backend works as Restful API Server,

* there is no sitemap that links posts to be indexed by search engine.
* and it cannot be site-previewed by other site like facebook.

So, SEO functionality comes with Backend.

SEO pages can only be readable by machine(robots) for now. It does not support IE6 ~ IE9.

For installation of SEO, see Installaction section.


@note SEO works if index.html exists and it only work on route of 'index' and '/p/....'. It does not work on other routes.



## How it works


* By configuring web server's `directory index` to seo.php, Backend has a separate start script for seo service.
	* XHR/Ajax/Resful Reqeust will access 'index.php'
	* While robots and web browsers will access 'seo.php'

* Save all client files on Backend root folder. ( Add .gitignore to make it easy work. )
* When browsers, robots access, `seo.php` starts and reads 'index.html'
	* it will patch 'SEO' things into 'index.html' based on the request URI.
		* Metas
		* Open Grahps
		* Links to posts.
		* etc ...
* Then, `seo.php` will deliver the payload(HTML) to browser or robots.
	* If browser understands the first payload( it may be angular code ), it will begin to run the Javascript for Single Page App or Client App.
	* If the robot does not understand Javascript in the payload, that's fine. Just index the SEO code inside it.


### Flowchart of SEO

* On front page, it displays links of latest 40 posts including comments.
* On post/comment page, it displays content of the post/comment and displays previous 40 posts including comments.

### Customizing and Testing

[Google Webmaster Tools](https://www.google.com/webmasters/tools/) is known to be the best helpful tool when it comes to 'SEO' related works.



### Nginx Configuration for SEO


Example ) How to configure Nginx for SEO

````
    server {
        listen       80;
        server_name  backend.org;
        root   /Users/thruthesky/www/backend;
        location / {
            index  index.php;
            try_files $uri $uri/ /index.php?$args;
        }
        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }
    }
````







