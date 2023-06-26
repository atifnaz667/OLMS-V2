<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $studenMenuJson = file_get_contents(base_path('resources/menu/studentMenu.json'));
    $studenMenuData = json_decode($studenMenuJson);

    $parentMenuJson = file_get_contents(base_path('resources/menu/parentMenu.json'));
    $parentMenuData = json_decode($parentMenuJson);

    $teacherMenuJson = file_get_contents(base_path('resources/menu/teacherMenu.json'));
    $teacherMenuData = json_decode($teacherMenuJson);

    $adminMenuJson = file_get_contents(base_path('resources/menu/adminMenu.json'));
    $adminMenuData = json_decode($adminMenuJson);

    // Share all menuData to all the views
    \View::share('menuData', [$adminMenuData, $teacherMenuData, $parentMenuData, $studenMenuData]);
  }
}
