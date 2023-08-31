<?php

use Bvfbarten\SimpleCms\Console\Commands\ModelFromDump;
use Bvfbarten\SimpleCms\Console\Commands\ModelToDump;
use Bvfbarten\SimpleCms\Http\Controllers\SimpleCmsController;
use App\Http\Controllers\PageTreeContactController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

if( config('simple-tree-cms-config.routing.allows-web-dumps')) {
  Route::get('/to-dump', function(){
    return Artisan::call(ModelToDump::class, [
      '--force' => app('request')->get('force')
    ]); 
  });
  Route::get('/from-dump', function(){
    return Artisan::call(ModelFromDump::class); 
  });
}
Route::any(config('simple-tree-cms-config.routing.cms-routes'), [
  config('simple-tree-cms-config.routing.cms-controller'),
 'show' 
]);
