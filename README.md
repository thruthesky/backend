# Backend-0.2
Backend server for Restful APIs


# Resources

* The best resource to understand Backend is to read and run the tests.


# TODO


## test on forum config, forum post


## file upload with cosutomizalbe downloda

* You will upload a file without resizing.
* When you download image, you can customize.
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

Test script under module folder will be run on "all test run", "class test" and "method test".

Test script must end with with "_test.php".


##  Run all tests

To run all the tests, just access

````
?route=test
````


## Run a test class



To test class

````
?route=model.class
````


## Run a test method



To test class

````
?route=model.class.method
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




# Database and Data Relation

## meta table

Meta table holds meta datas.

It has model, model_idx, code and value fields plus idx, created and updated.

### model

meta.model field is for a big category like "user", "forum", "file".

### model_idx

meta.model_idx is to associate the meta data with the object(entity/record) of the model.


### code
meta.code is a sub-category for the meta. It would be a property of a entity like "facebook address", "google plus address" of a user.

