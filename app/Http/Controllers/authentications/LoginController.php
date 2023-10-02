<?php

namespace App\Http\Controllers\authentications;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.login-main', ['pageConfigs' => $pageConfigs]);
  }

  public function show($type)
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs, 'type' => $type]);
  }

  public function sigupPage($type)
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.card-signup', ['pageConfigs' => $pageConfigs, 'type' => $type]);
  }

  public function sigup(Request $request)
  {
    $rules = [
      'card_no' => 'required|exists:cards,card_no',
    ];
    $status = "";
    $message = "";
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()
        ->withInput()
        ->with(['status' => 'error', 'message' => $validator->errors()->first()]);
    }
    $pageConfigs = ['myLayout' => 'blank'];
    $card = Card::where('card_no', $request->card_no)->first();
    if ($card->status == "used") {
      $status = "error";
      $message = "Card Already Used.";
    } else if ($card->status == "partial") {
      $user = User::where('card_id', $card->id)->first();
      if ($request->type == "Student") {
        if ($user->role_id == 4) {
          $status = "error";
          $message = "Card Already Used.";
        } else {
          return redirect()->route('pending-user', [
            'pageConfigs' => $pageConfigs,
            'type' => $request->type,
            'card_id' => $card->id,
          ]);
        }
      } else if ($request->type == "Parent") {
        if ($user->role_id == 2) {
          $status = "error";
          $message = "Card Already Used.";
        } else {
          return redirect()->route('pending-user', [
            'pageConfigs' => $pageConfigs,
            'type' => $request->type,
            'card_id' => $card->id,
          ]);
        }
      } else {
        $status = "error";
        $message = "You can't create account with Card.";
      }
    } else if ($card->expiry_date > date('Y-m-d') && $card->status == "pending") {
      return redirect()->route('pending-user', [
        'pageConfigs' => $pageConfigs,
        'type' => $request->type,
        'card_id' => $card->id,
      ]);
    } else {
      $card->status = "expired";
      $card->save();
      $status = "error";
      $message = "Card Expired.";
    }
    return back()
      ->withInput()
      ->with(['status' => $status, 'message' => $message]);
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
        ->with(['status' => 'error', 'message' => $validator->errors()->first()]);
    }
     $user = User::where('username',$req->username)->first();
    if ($user && $user->role->name != $req->type) {
      return back()->with(['status' => 'error', 'message' => 'Wrong Credentials']);
    }
    if ($user && $user->status == 'deactive') {
      return back()
        ->withInput()
        ->with(['status' => 'error', 'message' => 'Your account has been deactivated']);
    }

    
      $credentials = ['username' => $req->username, 'password' => $req->password];


    if (Auth::attempt($credentials)) {
      $user->last_login_at = now();
      $user->save();
      return redirect('home')->with(['status' => 'success', 'message' => 'Welcome..! ' . $user->name]);
    }
    return back()->with(['status' => 'error', 'message' => 'Wrong Credential3s']);
  }

  public function logout()
  {
    Auth::logout();
    return redirect()->to('/');
  }

  public function pendingUser(Request $request)
  {
    $results = DropdownHelper::getBoardBookClass();
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('content.authentications.auth-register-basic', ['pageConfigs' => $request->pageConfigs, 'classes' => $classes, 'boards' => $boards, 'type' => $request->type, 'card_id' => $request->card_id]);
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-register-basic', ['pageConfigs' => $pageConfigs, 'classes' => $classes]);
  }

  public function storePendingUser(Request $request)
  {
    $rules = [
      'fullName' => 'required',
      'username' => 'required|unique:users,username',
      'email' => 'required|unique:users,email',
      // 'board_id' => 'required',
      // 'class_id' => 'required',
      'password' => 'required',
      'user-image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return back()
        ->withInput()
        ->with(['status' => 'error', 'message' => $validator->errors()->first()]);
    }

    $class_id = $request->input('class_id');
    $board_id = $request->input('board_id');
    $password = $request->input('password');
    $user = new User;
    if ($request->type == "Student")
      $user->role_id = 4;
    else
      $user->role_id = 2;
    $user->card_id = $request->card_id;
    $user->name = $request->fullName;
    $user->email = $request->email;
    $user->username = $request->username;
    $user->password = Hash::make($password);
    if ($class_id) {
      $user->board_id = $board_id;
      $user->class_id = $class_id;
    }
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
    $userCount = User::where('card_id', $request->card_id)->count();
    $card = Card::where('id', $request->card_id)->first();
    if ($userCount == 1) {

      $card->status = "partial";
      $card->count = 1;
    } else {
      $card->status = "used";
      $card->count = 2;
    }
    $card->save();
    return redirect('/');
  }
}
