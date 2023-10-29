<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Cache;
use App\Models\AssignUser;
use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use App\Models\Card;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $cards = Card::get();
    $roles = Role::where('id', '!=', 1)->get();
    $users = User::with('role')->get();
    $results = DropdownHelper::getBoardBookClass();
    $classes = $results['Classes'];
    $boards = $results['Boards'];
    return view('users.index', ['users' => $users, 'classes' => $classes, 'boards' => $boards, 'cards' => $cards, 'roles' => $roles]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    if (isset($request->check)) {
      $validatedData = $request->validate([
        'card_no' => 'required|unique:cards',
        'serial_no' => 'required|unique:cards',
        'expiryDate' => 'required',
        // 'validDate' => 'required',
      ]);
      try {
        $card = new Card;
        $card->card_no = $validatedData['card_no'];
        $card->serial_no = $validatedData['serial_no'];
        $card->expiry_date = $validatedData['expiryDate'];
        // $card->valid_date = $validatedData['validDate'];
        $card->save();

        // Return success status and message
        return response()->json([
          'status' => 'success',
          'message' => 'Card stored successfully.',
        ]);
      } catch (\Exception $e) {
        // Return error status and message
        return response()->json([
          'status' => 'error',
          'message' => 'Failed to store Card.',
        ]);
      }
    } else {
      # code...

      // Validate the request data
      $validatedData = $request->validate([
        'username' => 'required|unique:users',
        'password' => 'required',
        'role_id' => 'required',
        'full_name' => 'required',
      ]);

      try {
        // Store the username and hashed password
        $user = new User();
        $user->role_id = $validatedData['role_id'];
        $user->username = $validatedData['username'];
        $user->name = $validatedData['full_name'];
        $user->email = $request->email;
      //  $user->cardno = $validatedData['cardno'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Return success status and message
        return response()->json([
          'status' => 'success',
          'message' => 'Username and password stored successfully.',
        ]);
      } catch (\Exception $e) {
        // Return error status and message

       // dd($e);
        return response()->json([
          'status' => 'error',
          'message' => 'Failed to store username and password.',
        ]);
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function editUser()
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make(
      ['id' => $user_id],
      [
        'id' => 'required|exists:users,id',
      ]
    );

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator->errors()->first());
    }

    try {
      $user = User::with('role')->findOrFail($user_id);
      $results = DropdownHelper::getBoardBookClass();
      $classes = $results['Classes'];
      $boards = $results['Boards'];
      return view('users.edit', ['user' => $user, 'classes' => $classes, 'boards' => $boards]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return redirect()
        ->back()
        ->withErrors($message);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request, $id)
  {
    $validator = Validator::make(
      ['id' => $id],
      [
        'id' => 'required|exists:users,id',
      ]
    );

    if ($validator->fails()) {
      return response()->json(['status' => 'errors', 'message' => $validator->errors()->first()], 422);
    }
    try {
      if (isset($request->check))
        $User = Card::findOrFail($id);
      else
        $User = User::with('role')->findOrFail($id);


      return response()->json([
        'status' => 'success',
        'message' => 'User retrieved successfully',
        'User' => $User,
      ]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json(
        [
          'status' => 'error',
          'message' => $message,
        ],
        500
      );
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    if (isset($request->card_no)) {
      $card = Card::findOrFail($id);
      $card->card_no = $request->card_no;
      $card->serial_no = $request->update_serial_no;
      $card->expiry_date = $request->expiry_date;
      // $card->valid_date = $request->valid_date;
      $card->save();

      return response()->json([
        'status' => 'success',
        'message' => 'Card updated successfully.',
      ]);
    }
    $user = User::findOrFail($id);

    // Validate the request data
    $request->validate([
      'username' => 'required|unique:users,username,' . $id,
      'password' => 'nullable',
    ]);

    // Update the username if it has changed
    if ($request->username !== $user->username) {
      $user->username = $request->username;
    }

    // Update the password if it is provided
    if (!is_null($request->password)) {
      $user->password = Hash::make($request->password);
    }
    if (!is_null($request->board_id)) {
      $user->board_id = $request->board_id;
    }
    if (!is_null($request->name)) {
      $user->name = $request->name;
    }
    if (!is_null($request->email)) {
      $user->email = $request->email;
    }
    if (!is_null($request->class_id)) {
      $user->class_id = $request->class_id;
    }

    // Save the updated user
    $user->save();

    return response()->json([
      'status' => 'success',
      'message' => 'User updated successfully.',
    ]);
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
  /**
   * get the specified resource from storage.
   */
  public function getDropDown()
  {
    $unassignedParents = User::where('role_id', 2)
      ->whereDoesntHave('assignUserAsParent')
      ->get();

    $unassignedStudents = User::where('role_id', 4)
      ->whereDoesntHave('assignUserAsChild')
      ->get();

    // Return the result as needed
    return [
      'unassignedParents' => $unassignedParents,
      'unassignedStudents' => $unassignedStudents,
    ];
  }

  public function assignUser()
  {
    try {
      $validatedData = request()->validate([
        'parent_id' => 'required|integer',
        'student_id' => 'required|integer',
      ]);

      $parentId = $validatedData['parent_id'];
      $studentId = $validatedData['student_id'];

      $assignUser = new AssignUser();
      $assignUser->parent_id = $parentId;
      $assignUser->child_id = $studentId;
      $assignUser->save();

      return [
        'status' => 'success',
        'message' => 'User assigned successfully.',
      ];
    } catch (\Exception $e) {
      return [
        'status' => 'error',
        'message' => 'Failed to assign user. ' . $e->getMessage(),
      ];
    }
  }

  public function liveStatus($user_id)
    {
        // get user data
        $user = User::find($user_id);

        // check online status
        if (Cache::has('user-is-online-' . $user->id))
            $status = 'Online';
        else
            $status = 'Offline';

        // check last seen
        if ($user->last_seen != null)
            $last_seen = "Active " . Carbon::parse($user->last_seen)->diffForHumans();
        else
            $last_seen = "No data";

        // response
        return response()->json([
            'status' => $status,
            'last_seen' => $last_seen,
        ]);
    }
}
