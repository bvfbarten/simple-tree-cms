<?php

use Bvfbarten\SimpleCms\Http\Controllers\SimpleCmsController;
use Illuminate\Support\Facades\Route;

Route::get('/hello-world', [SimpleCmsController::class, 'index']);
