# MODX-Slim

Use [Slim](https://www.slimframework.com/) along side of your [MODX 2.*](https://modx.com/) project.

## Why mix MODX and Slim?

Slim helps you to create a modern REST API that handles the URL routing and verbs, POST, GET, DELETE, ect. If you are 
wanting to push/pull data from your MODX site or do an SPA site this will ease development greatly. Another reason is 
a future version of MODX will use Slim.

## Install:

- First install [Orchestrator](https://github.com/LippertComponents/Orchestrator) using [Local Orchestrator Example](https://github.com/LippertComponents/LocalOrchestrator)
- Open up the composer.json file created above in the core directory and then add
  - `"lci/modx-slim": "^0.1"` to the require 
  - `"lci/modx-slim"` to the extra array
- Run `composer update`

## Nginx rules REST API 

We need to route our web server to use Slim, similar to friendly URLs. To do that add nginx rules 
as noted below just above the root location route: ```location / {``. Below are working rules for MODX Cloud and may vary
for you. If you use Apache or another web server you will need to create similar rules. 

```
# Slim API
location /api/ {
    allow all;
    try_files $uri $uri/ /api/index.php;
}
# End Slim API
```

## Local project

1. Create a PHP class that implements the [LCI\MODX\Slim\Helpers\Package](src/Helpers/Package.php) interface. This is 
where you define your routes and settings.

2. Create a Blend migration file, can generate it like so: `core$ joshgulledge$ php vendor/bin/orchestrator blend:generate -N InstallRoutes`
  - In the up method do something like this:  
```php
$modxSlim = new \LCI\MODX\Slim\App();
$modxSlim->registerPackage('My\Namespace\LocalPackage');
```
  - In the down method do something like this:  
```php
$modxSlim = new \LCI\MODX\Slim\App();
$modxSlim->cancelRegistrationPackage('My\Namespace\LocalPackage');
```

3. Then run the migration to install your routes

## Create a package with custom routes

1. Create a composer package following Orchestrator

2. Do the same as Local project above

## Slim routes

- Example class but no routes are set to it: [src/Users.php](src/Users.php) class
- Make a single method/function per route. For example if you what to get a single resource make a method like getResource().
If you are familiar with Laravel/Lumen you may want to follow their naming convention for methods.
- Once you have your method/function defined then you need to create a route for it. This will go in the PHP Class you created that 
implements the [LCI\MODX\Slim\Helpers\Package](src/Helpers/Package.php) interface. An example route like 
```php
$app->post('/sign-in', \LCI\MODX\Slim\Users::class .':signIn');
$app->get('/sign-out', \LCI\MODX\Slim\Users::class .':signOut');
$app->get('/resource/{id}', \My\Namespace\Resources::class .':getResource');
```
Note to get the passed ```{id}``` use something like ```$id = $request->getAttribute('id');``` in your getResource method.
- Now test it! I use [Postman](https://www.getpostman.com/) to do initial testing. Just add in the url like so: 
https://myWebsite.com/rest/v1/resource/2 and review.

## Updating

The location of the package configuration has changed from v0.2.1 to v0.3.0, it has moved from 
`core/vendor/lci/modx-slim/src/cache/package.php` to `core/config/lci_modx_slim_package.php`. 
Manually copy before running composer update.
 
If the `core/vendor/lci/modx-slim/src/cache/package.php` file got deleted and you don't have a backup then 
do a search in core/vendor/lci directory for all files with: `->registerPackage(` and put all of the classes 
into the array like below.

The contents of the config file `core/config/lci_modx_slim_package.php` file will look similar to the code below.
*Note: prior to v0.3 the config file was in this path `core/vendor/lci/modx-slim/src/cache/package.php`*

```php
<?php 
return array (
  0 => 'LCI\\MODX\\DealerLocator\\Slim\\DealerLocatorPackage',
  1 => 'LCI\\MODX\\SalsifyGallery\\Slim\\SalsifyGalleryPackage',
);

```