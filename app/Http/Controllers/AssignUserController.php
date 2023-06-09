<?php

namespace App\Http\Controllers;

use App\Models\AssignUser;
use App\Models\User;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $rules = array(
        'parent_id' => 'required|int|exists:users,id',
        'children' => 'required|array|min:1',
        'children.*' => 'required|int|exists:users,id',
      );
      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
      }
      try {
        $user = User::find($request->parent_id);
        if ($user->role_id != 2 || $user->role_id != 3) {
          return response()->json(['status' => 'error', 'message' => $user->name.' must be parent or teacher']);
        }
        if ($user->role_id == 2 && count($request->children) > 1) {
          return response()->json(['status' => 'error', 'message' => 'You can assign only one child to parent']);
        }
        DB::beginTransaction();
          foreach ($request->children as $child) {
            $user = User::find($child);
            if ($user->role_id != 4) {
              DB::rollBack();
              return response()->json(['status' => 'error', 'message' => $user->name.' must be student']);
            }
            $assignUser = new AssignUser();
            $assignUser->parent_id = $request->parent_id;
            $assignUser->child_id = $child;
            $assignUser->save();
          }
        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Children assigned successfully'], 200);
      } catch (\Exception $e) {
        DB::rollBack();
        $message = CustomErrorMessages::getCustomMessage($e);
        return response()->json(['status' => 'error', 'message' => $message], 500);
      }

    }

    /**
     * Display the specified resource.
     */
    public function show(AssignUser $assignUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssignUser $assignUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssignUser $assignUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssignUser $assignUser)
    {
        //
    }
}
