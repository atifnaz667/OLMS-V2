<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuggestionController extends Controller
{

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('suggestions.index');
  }


  public function getSuggestions(Request $request)
  {
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:name,id'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    try {
      $perPage = $request->input('perPage', 10);
      $sort = $request->input('sort_by', 'id');
      $sort_order = $request->input('sort_order', 'asc');
      $table = $request->input('table', 'suggestions');
      $sorting = $table . '.' . $sort;

      $username = $request->input('name');
      $date = $request->input('date');

      $suggestions = Suggestion::with(['user'])
        ->when($username, function ($query) use ($username) {
          $query->whereHas('user', function ($query) use ($username) {
            $query->where('name', $username);
          });
        })
        ->when($date, function ($query) use ($date) {
          $query->whereDate('created_at', $date);
        })
        ->orderBy($sorting, $sort_order)->paginate($perPage);

      $data = $suggestions->map(function ($suggestion) {
        return [
          'id' => $suggestion->id,
          'subject' => $suggestion->subject,
          'message' => $suggestion->message,
          'user' => $suggestion->user->name,
          'date' => $suggestion->created_at,
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Suggestions retrieved successfully',
        'data' => $data,
        'current_page' => $suggestions->currentPage(),
        'last_page' => $suggestions->lastPage(),
        'per_page' => $suggestions->perPage(),
        'total' => $suggestions->total(),
      ]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $rules = [
      'subject' => 'required',
      'message' => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return back()->with([
        'status' => 'error',
        'message' => $validator->errors()->first(),
      ], 400);
    }


    try {
      $suggestion = new Suggestion;
      $suggestion->subject = $request->subject;
      $suggestion->message = $request->message;
      $suggestion->user_id = Auth::user()->id;
      $suggestion->save();

      return back()->with([
        'status' => 'success',
        'message' => 'Suggestion sent to admin successfully',
      ], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return back()->with([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

 /**
   * Display the specified resource.
   */
  public function create()
  {
    return view('suggestions.add');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {

  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $suggestion = Suggestion::find($id);

    // Check if suggestion exists
    if (!$suggestion) {
      return response()->json(['status' => 'error', 'message' => 'Suggestion not found'], 404);
    }

    try {
      // Delete the suggestion
      $suggestion->delete();

      return response()->json(['status' => 'success', 'message' => 'Suggestion deleted successfully'], 200);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }
}
