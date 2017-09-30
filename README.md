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

$app = new \Szenis\Picro\App();

$app->get('/', function() {
    return 'hello world';
});

$app->run();
````

<b>Optional</b><br/>
For debuging purpose add the following to your index.php
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
````

<b>Routing</b>
This package is using the "Simple-PHP-Router", for more information take a look at the documentation https://github.com/stein189/Simple-PHP-Router
