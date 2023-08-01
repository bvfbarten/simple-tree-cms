<?php

namespace Bvfbarten\SimpleCms;

use Bvfbarten\SimpleCms\Console\Commands\ModelFromDump;
use Bvfbarten\SimpleCms\Console\Commands\ModelToDump;
use Bvfbarten\SimpleCms\Filament\Resources\SiteResource;
use Bvfbarten\SimpleCms\Filament\Resources\TreePageResource;
//use Illuminate\Support\ServiceProvider;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class SimpleCmsServiceProvider extends PluginServiceProvider
{
  /* filament specific */
  protected array $resources = [
    SiteResource::class,
    TreePageResource::class,
  ];
  public function getResources() : array {
    return [
    SiteResource::class,
    TreePageResource::class,
    ];
  }
  public function configurePackage(Package $package): void
  {
    $package->name('simple-cms');
  }
  /* end Filament specific */

  public function register()
  {
    parent::register();
    $this->mergeConfigFrom(__DIR__ . '/config/simple-cms-config.php', 'simple-cms-config');
    $this->app['config']->set('database.connections.simple_cms', config('simple-cms-config.simple-cms-db'));
    $this->app->booted(function(){
      $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    });
  }
  public function boot()
  {
    parent::boot();
    $this->loadViewsFrom(__DIR__.'/resources/views/', 'simplecms');
    if ($this->app->runningInConsole()) {
      $this->loadMigrationsFrom(__DIR__.'/migrations/2023_06_09_071148_tree_page_v1.php');
      $this->publishes([
        __DIR__.'/config/simple-cms-config.php' => config_path('simple-cms-config.php'),
      ], 'simple-cms-config');
      $this->publishes([
        __DIR__.'/content/' => base_path('/content/'),
        __DIR__.'/Filament/' => base_path('/Filament/'),
      ], 'simple-cms-content');

    }

      $this->commands([
        ModelToDump::class,
        ModelFromDump::class
      ]);
  }

}
