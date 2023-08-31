<?php
return [
  'show_templates' => env('APP_DEBUG'),
  'simple-tree-cms-db' => [
    'driver' => 'sqlite',
    'database' => database_path('simple-tree-cms.sqlite'),
    'prefix' => '',
    'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
  ],
  'routing' => [
    'cms-routes' => '/{argument?}/{argument2?}/{argument3?}',
    'cms-controller' => 'Bvfbarten\SimpleCms\Http\Controllers\SimpleCmsController',
    'contact-controller' => 'Bvfbarten\SimpleCms\Http\Controllers\SimpleCmsContactController',
    'allows-web-dumps'=> true
  ]
];
