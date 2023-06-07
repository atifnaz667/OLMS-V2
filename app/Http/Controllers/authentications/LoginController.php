<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
        $rules = array(
            'username' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return back()->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        $credentials = array('username' => $req->username, 'password' => $req->password);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return redirect('home')->with(['status' => 'success', 'message' => 'Welcome..! ' . $user->name]);
        }
        return back()->with(['status' => 'danger', 'message' => 'Wrong Credentials']);
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->to('/');
    }
}
