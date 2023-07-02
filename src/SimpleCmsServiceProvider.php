<?php

namespace Bvfbarten\SimpleCms;

use Illuminate\Support\ServiceProvider;

class SimpleCmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}
