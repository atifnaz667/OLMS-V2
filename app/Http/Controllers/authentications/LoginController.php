<?php

namespace App\Http\Controllers\authentications;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $req)
  {
    $rules = [
      'username' => 'required',
      'password' => 'required',
    ];

    $validator = Validator::make($req->all(), $rules);
    if ($validator->fails()) {
      return back()
        ->withInput()
        ->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
    }
    $credentials = ['username' => $req->username, 'password' => $req->password];
    if (Auth::attempt($credentials)) {
      $user = Auth::user();
      if ($user->status == 'deactive') {
        return back()
          ->withInput()
          ->with(['status' => 'danger', 'message' => 'Your account has been deactivated']);
      }
      return redirect('home')->with(['status' => 'success', 'message' => 'Welcome..! ' . $user->name]);
    }
    return back()->with(['status' => 'danger', 'message' => 'Wrong Credentials']);
  }

  public function logout()
  {
    Auth::logout();
    return redirect()->to('/');
  }

  public function pendingUser()
  {
    $results = DropdownHelper::getBoardBookClass();
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-register-basic', ['pageConfigs' => $pageConfigs, 'classes' => $classes]);
  }

  public function storePendingUser(Request $request)
  {
    $rules = [
      'fullName' => 'required',
      'email' => 'required',
      // 'board_id' => 'required',
      // 'class_id' => 'required',
      'user-image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }

    $class_id = $request->input('class_id');
    $board_id = $request->input('board_id');
    $user = User::find(Auth::user()->id);
    $user->name = $request->fullName;
    $user->email = $request->email;
    $user->board_id = $board_id;
    $user->class_id = $class_id;
    if ($request->hasFile('user-image')) {
      $file = $request->file('user-image');
      $ext = $file->getClientOriginalExtension();
      $filename = time() . rand(1, 100) . '.' . $ext;
      $file->move(public_path('files/userImages'), $filename);
      $filePath = $filename;
    } else {
      $filePath = '';
    }
    $user->image = $filePath;

    $user->status = 'active';
    $user->save();

    return redirect('/');
  }
}
