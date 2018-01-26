# MODX-Slim

A very basic and simple example on how you can use [Slim](https://www.slimframework.com/) today along side of 
your [MODX 2.*](https://modx.com/)  project.

## Why mix MODX and Slim?

Slim helps you to create a modern REST API that handles the URL routing and verbs, POST, GET, DELETE, ect. If you are 
wanting to push/pull data from your MODX site or do an SPA site this will ease development greatly. Another reason is 
that future versions of MODX will use Slim.

## Install:

1. Create a director named rest in the root of your MODX install. 

2. Clone or copy all files into the rest directory

3. Then via terminal or command line tool go to the rest directory and do a ```composer install```  
If you are on MODXCloud follow these steps to [install composer](https://support.modx.com/hc/en-us/articles/221296007-Composer). 
If you are on another provider, check the [composer documentation](https://getcomposer.org/doc/00-intro.md).

4. For production, you must secure your rest directory, see below.

## Secure your REST API 

First open the src/settings.php file and set the $display_errors = false. Do want to send any sensitive info
via an error message.

Then we need to route our web server to use Slim, similar to friendly URLs. And since Slim project files are located 
in the public rest directory they also need to block public access accept for the v1 directory. To do that add nginx rules 
as noted below just above the root location route: ```location / {``. Below are working rules for MODX Cloud and may vary
for you. If you use Apache or another web server you will need to create similar rules. 

```
# Slim API
location /rest/v1/ {
    allow all;
    try_files $uri $uri/ /rest/v1/index.php;
}

# Block all other trafic to slim code
location ^~ /rest/ { 
    deny all; 
}
# End Slim API
```

## Create your custom route

1. Create a new PHP class in the src directory with the same namespace. Review the [src/Users.php](src/Users.php) class
for how to structure it. Organize your classes by function or xPDO object(database table CRUD) for simplicity. For example
you could create a Resources class that would handle the CRUD for all resources.  

2. Make a single method/function per route. For example if you what to get a single resource make a method like getResource().
If you are familiar with Laravel/Lumen you may want to follow their naming convention for methods.

3. Once you have your method/function defined then you need to create a route for it. Open up the [src/config/routes.php](src/config/routes.php)
file and add a new route like ```$app->get('/resource/{id}', \LCI\ModxSlim\Resources::class .':getResource');```. Note to
get the passed ```{id}``` use something like ```$id = $request->getAttribute('id');``` in your getResource method.

4. Now test it! I use [Postman](https://www.getpostman.com/) to do initial testing. Just add in the url like so: 
https://myWebsite.com/rest/v1/resource/2 and review.
