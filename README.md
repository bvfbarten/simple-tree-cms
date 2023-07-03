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
      "bvfbarten/simple-cms": "dev-master",
    }
</code>

then run composer update
