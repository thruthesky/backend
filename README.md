# backend-0.2
Backend server for Restful APIs


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



