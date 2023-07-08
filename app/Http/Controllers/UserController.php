<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssignUser;
use Illuminate\Http\Request;
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
    $users = User::with('role')->get();
    return view('users.index', ['users' => $users]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validate the request data
    $validatedData = $request->validate([
      'username' => 'required|unique:users',
      'password' => 'required|min:8',
      'role_id' => 'required',
    ]);

    try {
      // Store the username and hashed password
      $user = new User();
      $user->role_id = $validatedData['role_id'];
      $user->username = $validatedData['username'];
      $user->password = Hash::make($validatedData['password']);
      $user->save();

      // Return success status and message
      return response()->json([
        'status' => 'success',
        'message' => 'Username and password stored successfully.',
      ]);
    } catch (\Exception $e) {
      // Return error status and message
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to store username and password.',
      ]);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
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
}