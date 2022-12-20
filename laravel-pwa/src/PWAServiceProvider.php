<?php

namespace Admin\LaravelPwa;

use Illuminate\Support\ServiceProvider;
use Admin\LaravelPwa\commands\PublishPWA;

class PWAServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application service
   * @return void
   */
  public function boot()
  {

  }

  /**
   * Register the application service
   * @return void
   */
  public function register()
  {
    $this->app->singleton('laravel-pwa:publish', function($app) {
      return new PublishPWA;
    });

    $this->commands([
      'laravel-pwa:publish'
    ]);
  }
}