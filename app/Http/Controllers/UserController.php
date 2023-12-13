<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Cache;
use App\Models\AssignUser;
use Illuminate\Http\Request;
use App\Helpers\DropdownHelper;
use App\Models\Card;
use App\Models\Classes;
use App\Models\Note;
use App\Models\Role;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Builder\Class_;

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
          'message' => 'User created successfully.',
        ]);
      } catch (\Exception $e) {
        // Return error status and message

        // dd($e);
        $message = CustomErrorMessages::getCustomMessage($e);
        return response()->json([
          'status' => 'error',
          'message' => $message,
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
  public function updateUserInfo(Request $request)
  {
    $rules = [
      'name' => 'required|min:5',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }

    $user = User::find(Auth::user()->id);

    if (isset($request->oldPassword)) {
      if (Hash::check($request->oldPassword, $user->password)) {
        $rules = [
          'password' => 'required|min:5',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        }
        $user->password = Hash::make($request->password);
      } else {
        return response()->json(['status' => 'error', 'message' => 'Incorrect Old Password'], 400);
      }
    }
    $user->name = $request->name;
    $user->save();

    return response()->json([
      'status' => 'success',
      'message' => 'Info updated successfully.',
    ]);
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
    // if ($request->username !== $user->username) {
    //   $user->username = $request->username;
    // }

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
    // if (!is_null($request->email)) {
    //   $user->email = $request->email;
    // }
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
  public function destroy(Request $request, $id)
  {
    $user = User::find($id);

    if (!$user) {
      return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
    }

    $testsCreatedBy = Test::where('created_by', $user->id)->get();
    if ($testsCreatedBy) {
      foreach ($testsCreatedBy as $test) {
        $test->testChildren()->delete();
      }
      $testsCreatedBy = Test::where('created_by', $user->id)->delete();
    }

    $testsCreatedFor = Test::where('created_for', $user->id)->get();
    if ($testsCreatedFor) {
      foreach ($testsCreatedFor as $test) {
        $test->testChildren()->delete();
      }
      $testsCreatedFor = Test::where('created_for', $user->id)->delete();
    }
    $user->assignUserAsParent()->delete();
    $user->assignUserAsChild()->delete();
    $user->assignUserAsStudent()->delete();
    $user->delete();
    return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
  }

  public function notes()
  {

    $notes = Note::where('user_id', Auth::user()->id)->paginate(15);
    return view('notes.index', ['notes' => $notes]);
  }

  // public function viewNote($id)
  // {

  //   $note = Note::where('id', $id)->first();
  //   return view('notes.view', ['note' => $note]);
  // }

  public function viewNote($id)
  {
    $note = Note::with('user')->find($id);
    if (!$note) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid Notes',
      ], 500);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Notes retrieved successfully',
      'note' => $note,
    ]);
  }

  public function updateNote(Request $request, $id)
  {

    $note = Note::find($id);
    if (!$note) {
      return response()->json([
        'status' => 'error',
        'message' => 'Invalid Notes',
      ], 500);
    }
    $rules = array(
      'update_description' => 'required',
    );
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 500);
    }
    try {
      DB::transaction(function () use ($request, $note) {
        $note->name = $request->note_name;
        $note->note = $request->update_description;
        $note->save();
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Notes Updated Successfully',
      ]);
    } catch (Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }


  // public function updateNote(Request $request)
  // {
  //   $validator = Validator::make($request->all(), [
  //     'note_name' => 'required',
  //     'note' => 'required',
  //   ]);
  //   if ($validator->fails()) {
  //     return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
  //   }
  //   try {
  //     $note = Note::find($request->noteId);

  //     $note->name = $request->note_name;
  //     $note->note = $request->note;
  //     $note->save();
  //     DB::commit();
  //     return response()->json(['status' => 'success', 'message' => 'Note updated successfully'], 201);
  //   } catch (\Exception $e) {
  //     DB::rollBack();

  //     $message = CustomErrorMessages::getCustomMessage($e);

  //     return response()->json(
  //       [
  //         'status' => 'error',
  //         'message' => $message,
  //       ],
  //       500
  //     );
  //   }
  // }

  public function storeNote(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'note_name' => 'required',
      'note' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      DB::beginTransaction();
      $insertData = [
        'user_id' => Auth::user()->id,
        'name' => $request->note_name,
        'note' => $request->note,
      ];
      Note::insert($insertData);

      DB::commit();
      return response()->json(['status' => 'success', 'message' => 'Notes created successfully'], 201);
    } catch (\Exception $e) {
      DB::rollBack();

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

  public function details($id)
  {
    $user = User::with('class')->where('id', $id)->first();
    if ($user) {
      $role = $user->role_id;
      $class = "";
      if ($role == 4) {
        # student
        $details = User::join('assign_users', 'users.id', '=', 'assign_users.parent_id')
          ->where('assign_users.child_id', $id)
          ->pluck('users.name')
          ->first();
        $data = Classes::select('name')->where('id', $user->class_id)->first();
        $class = $data->name;
      } else if ($role == 2) {
        # parent
        $details = User::join('assign_users', 'users.id', '=', 'assign_users.child_id')
          ->where('assign_users.parent_id', $id)
          ->pluck('users.name')
          ->first();
      } else if ($role == 3) {
        # teacher
        $details = User::join('assign_teacher_students', 'users.id', '=', 'assign_teacher_students.student_id')
          ->where('assign_teacher_students.teacher_id', $id)
          ->select('users.name')
          ->get();
      }
    }
    return response()->json(
      [
        'details' => $details,
        'class' => $class,
        'role' => $role,
      ]
    );
    return $details;
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
