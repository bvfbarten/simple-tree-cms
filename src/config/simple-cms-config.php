<?php

return [
  'simple-cms-db' => [
    'driver' => 'sqlite',
    'database' => database_path('simple-cms.sqlite'),
    'prefix' => '',
    'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
  ],
  'routing' => [
    'cms-routes' => '/{argument?}/{argument2?}/{argument3?}',
    'allows-web-dumps'=> true
  ]
];
