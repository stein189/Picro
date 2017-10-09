# Picro - PHP micro framework

<h2>Getting started</h2>

<b>Step 1 - .htaccess file</b>
create an .htaccess file in the root of your project and fill it with the code below:
````
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes...
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
````

<b>Step 2 - require szenis/picro</b><br/>
In your terminal execute: ``composer require szenis/picro``

<b>Step 3 - create index.php</b><br/>
Create the file index.php in the root of your project

<b>Step 4 - use the framework</b><br/>

Here is an example of what your index.php could look like

```php
<?php

require './vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new \Szenis\Picro\App();

# It is possible to use the full path to your method
$app->get('/', '\App\CoreBundle\Controller\DefaultController:indexAction');

# Or a Closure
$app->get('/closure', function() {
    return new Response('hello world');
});

# The Picro framework uses the request and response object from Symfony
# You are required to return a instance of the Response object

# The name of the variables in the slug have to match the names of the variable used in the function.
# Because the names of the arguments are the same it doesn't matter in which order they are defined
$app->get('/{n:number}/{w:word}', function($word, $number) {
    return new Response('hello world');
});

# When you need the Request or Response object you can simply inject it just by typehinting the class
$app->get('/admin/{w:word}', function(Request $request, Response $response, $word) {
    $response->setContent('hello world');

    return $response;
});

# After all routes are registerd we can run our application.
$app->run();
````

<b>Optional</b><br/>
For debuging purpose add the following to your index.php
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
````

<b>Routing</b>
This package is using the "Simple-PHP-Router" (v2), for more information take a look at the documentation https://github.com/stein189/Simple-PHP-Router
