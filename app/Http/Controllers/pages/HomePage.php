<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePage extends Controller
{
  public function index()
  {
    $role_id = Auth::user()->role_id;
    if ($role_id == 1) {

      return view('dashboards.admin');

    }elseif ($role_id == 4) {

      return view('dashboards.Student');

    }elseif ($role_id == 2) {

      return view('dashboards.Parent');

    }elseif ($role_id == 3) {

      return view('dashboards.Teacher');
    }
  }

}
