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
