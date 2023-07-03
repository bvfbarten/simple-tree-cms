<?php

use Bvfbarten\SimpleCms\Console\Commands\ModelFromDump;
use Bvfbarten\SimpleCms\Console\Commands\ModelToDump;
use Bvfbarten\SimpleCms\Http\Controllers\SimpleCmsController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

if( config('simple-cms-config.routing.allows-web-dumps')) {
  Route::get('/to-dump', function(){
    return Artisan::call(ModelToDump::class); 
  });
  Route::get('/from-dump', function(){
    return Artisan::call(ModelFromDump::class); 
  });
}
Route::get(config('simple-cms-config.routing.cms-routes'), [SimpleCmsController::class, 'show']);
