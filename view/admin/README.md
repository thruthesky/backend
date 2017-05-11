# Backend Admin Page


# For developers


## Sttings

To put real(remote) web server environment on local desktop machine, follow the steps below.
And when development is done, `git push`


environment.ts )

````
export const environment = {
  backendApi: 'http://backend.org/index.php'
};
````

environment.prod.ts )
````
export const environment = {
  backendApi: '/index.php'
};
````


ng build )

$ ng build --prod --aot --output-path=page --base-href=/view/admin/page/


