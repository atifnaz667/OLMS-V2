<?php

namespace App\Http\Controllers\pages;

use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomePage extends Controller
{
  public function index()
  {
    $role_id = Auth::user()->role_id;
    if ($role_id == 1) {
      return view('dashboards.admin');
    } elseif ($role_id == 4) {
      $results = DropdownHelper::getBoardBookClass();
      $classes = $results['Classes'];
      $boards = $results['Boards'];
      if (Auth::user()->status == 'pending') {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-register-basic', [
          'pageConfigs' => $pageConfigs,
          'classes' => $classes,
          'boards' => $boards,
        ]);
      } else {
        return view('dashboards.student', [
          'boards' => $boards,
        ]);
      }
    } elseif ($role_id == 2) {
      return view('dashboards.parent');
    } elseif ($role_id == 3) {
      return view('dashboards.teacher');
    }
  }
}
