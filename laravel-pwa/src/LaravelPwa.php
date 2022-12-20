<?php

namespace Admin\LaravelPwa;

use Illuminate\Support\Facades\Facade;

class LaravelPwa extends Facade
{
  /**
   * Get the binding in the IoC container
   * @return string
   */
  public function getFacadeAccessor()
  {
    return 'Laravel-pwa';
  }
}