# Backend-0.4.0

Backend server for Restful APIs


# Resources

* The best resource to understand Backend is to read and run the tests.


# TODO

## transaction
* prove pdo transaction is working with race condition.




## add more tests.

* to check table is set.
* to add more tests.



## Post

* secret post

* A post can be linked to other forum or other place.
	* post.linked_with field may be a way.

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


## Gruop

to complete a portal.


## SMS service

To reduce the money, SMS shouldn't send more than 1 or 2 times a day to a user.



## Sample Site

* Simple community site
* Buy and sell / Online shopping mall site.



## Installation

* Once installed, it shouldn't be re-installed unless the user manually removes the database.



## File upload with cosutomizalbe downloda


@done use file index to get file name.
@done if fail to upload, delete it from db.
@done delete old files that were not successfully hooked.
        
@donw delete model + model_idx, idx, model + model_idx + code

@done hook
        // @todo download with filename. ?route=download&size=100x200&quality=100&resize=crop&name=/abcdef.jpg
        // @todo when you get posts, give option of photo si
        ze, and
         other options.
        // @todo count download
        // @todo check/select primary photo among others
        // @todo admin management.
        	show how many unhooked, show many old unhooked.
        	show satistics.
        	
        // @todo add user primary photo idx. so, the. user can see/edit his photo.
        @todo view, edit, delete.

@todo upload file with angular http formdata without file transfer of cordova.

* `file` table will holds the uploaded file information.
* `file.finish` will be 0 until the file is really related to its object(parent).
	* files with `file.finish=0` becomes 24 hours old, then it will be deleted.

* when a file uploaded, it will return `file.idx`, any of file upload form and its related form should keep the `file.idx` and pass it over the parents' form submission. So, 
* You will upload a file without resizing.
* When you download image, you can customize.
* Create thumbnail(optimized image) only on first download or with an option.
* You can choose image type, width, height, quality, resize type.
	* This will help on image optimization.

**for jpeg**
````
?route=download&type=jpeg&width=80&hegiht=120&quality=100&resize=crop
````

**for png**
````
?route=download&type=png&width=100&height=120&resize=center
````

## delete old files that were not successfully finished


## Real Message System

To communiate between users.

* Each room has a configuration in `chat_config` table.
* Each chat message is save in `chat_message` table.
* For the same reason of `data integrity` of post, it does not hold `chat_config.time_last_message`. Do SQL query to get it.
* Users and Chat Rooms are N:M relation for group chatting. So, `chat_relation` table will have the relation ship.
* If a user leaves a chat room, then he will loose all the data.
	* When a last user leaves from a chat room, the cat room will be destroyed.
	* This condition perfectly makes it work like facebook chat or kakaotalk.
* For new message indication, everty time a user gets a message from a room, the time of the message will be recorded in `chat_relation.time_of_last_message`. When a user visits(checks) the chat rooms, any chat room has newer message then the `chat_relation.time_of_last_message`, then the room has a new message that the user didn't read.


# Interface

Is more likey a router.



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



# Installation

## How to install


````
http://localhost/www/backend-0.2/?route=install
````


## How to code on installation

You can put any php script ending "_install.php" under a module folder.

Those files will be run in installation process.




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


**2.** Creata test file.

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
* The input variable `route` can be omitted since all access needs a route.






## HTTP variable type checking

* For security enhancement, types of HTTP variable are checked by the system.

* Since many of HTTP variables and routers have same(similiar) names and types, it may be one good idea to check type of HTTP variables in one place. 

See `check_http_variables_type()` for details.





# Interface

Interfaces are the methods that are directly called by API call.

All interfaces must be recorded in `{module_name}_interface.php`

For instance

````
model/user/user_interface.php
````




# Data Relation and Database


## User

### Anonymous User.

Users who are not logged in with their ID and password will login as anonymous. Meaning, All users are logged in as anonymous when they first visit the site.


* Anonymous is a user who did not log in with his password but treated as logged in.
* Anonymous user cannot login, logout, edit his information.
* But can post/edit/delete with password.

## meta table

Meta table holds meta datas.

It has model, model_idx, code and value fields plus idx, created and updated.

### model

meta.model field is for a big category like "user", "forum", "file".

### model_idx

meta.model_idx is to associate the meta data with the object(entity/record) of the model.


### code
meta.code is a sub-category for the meta. It would be a property of a entity like "facebook address", "google plus address" of a user.



## Post

Post can be served in many ways like forum posts, blog posts, group( cafe ) posts, comments on an image, etc. So, the name of the functionality shouldn't be something like 'forum'. Instead, It should be a simple `post` to serve variety of functionality.



### post_config table

Post categories ( or settings ) are saved in `post_config` table.

* `post_config.moderators` holds forum moderators to manage the forum. It is a string of IDs separated by comma.


* Only admin can create, edit, delete `post_config` but any can read it. So don't put any critical information on it.



### post_data table

Posts are saved in `post_data` table.
Comments are saved in `post_data` table together with post for easy managibility and for easy search.


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




# Meta_Proxy


Meta Prxoy is a handy method to manage mata data of an entity. When `meta()` method of an entity is called, it creates an object of Meta_Injector with the model and model\_idx of the entity and returns the object.


````
user( 'abc' )->meta()->get();        // gets all meta data of user 'abc'
post( 1 )->meta()->get('birthday');  // gets bitrh meta data of post no 1.
user( 'abc' )->meta()->set([...]);   // sets an array of meta to user 'abc'.
config( 33 )->meta()->set( 'title', '...' ); // sets title meta to post config 33.
user( 'def' )->meta()->delete();     // delete all meta of user 'def'
user( 'def' )->meta()->delete( 'birthday' ); // delete birthday meta of user 'def'.
````




# Admin

* `admin id` is set in ./etc/config.php and if you want, you can chage it to any user id and that id becomes admin.
* admin password is the same as `admin id`. so, by default, admin id is `admin` and the password is `admin`. so, you can login as admin with `admin` as ID and `admin` as password.
* You need to change the password of the admin immediately after you install.



# API

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

* When admin edits user information, user's session id may become invalid depending on what data has been changed. see `user::getSessionId()`
* When admin changes user information, admin's session id will be regenerated. And the response data of user edit is admin's information, NOT the user's information.



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





### Hook

To hook image(s) you uploaded to an entity, you just send the file idx(es) in `file_hooks` array of http variables to the server. And the serve will attach those files(images) to the entity.





### Optimazation

There are many image libraries and we could even develop one by by ourselves. But we chose [php-image-resize](https://github.com/eventviva/php-image-resize). This is very simple and clear library to use.

Refer [examples of its github repository](https://github.com/eventviva/php-image-resize/blob/master/test/Test.php).

Refer [API explanation page](https://eventviva.github.io/php-image-resize/class-Eventviva.ImageResize.html)
 