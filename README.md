# Backend-0.2
Backend server for Restful APIs


# Resources

* The best resource to understand Backend is to read and run the tests.



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
* `system` variables are those variables that are used by the interface.
* `required` and `optional` variables will be inserted into database table record, so you will add only state variables that will be saved into database table.






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

