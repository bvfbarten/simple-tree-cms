To install, copy package to a location accessible by composer.  EG  "../laravel-tree-cms-package". add to your composer.json file
<code>
    "repositories": [{
      "type": "path",
      "url": "../laravel-tree-cms-package"
    }],
</code>

Then add to your composer.json file

<code>
    "require": {
      "bvfbarten/simple-tree-cms": "dev-master",
    }
</code>

then run 
<code>
    composer update
    php artisan migrate
</code>

If you want to install default content:

<code>
  php artisan vendor:publish --provider="Bvfbarten\SimpleCms\SimpleCmsServiceProvider"
  php artisan cms:from-dump
</code>

in 'routes/web.php' delete the welcome route.


