<?php

namespace App\Http\Controllers;
use App\Helpers\Helpers;
use App\Models\Announcement;
use App\Models\AnnouncementClasses;
use App\Models\Board;
use App\Models\Classes;
use App\Models\User;
use App\Models\Note;
use App\Services\CustomErrorMessages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

      return view('students.notes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function notesAjax(Request $req)
    {
      try {

        $date = $req->date;
        $perPage = $req->perPage;
        $user_id = Auth::user()->id;


        $notes = Note::when($date, function ($query) use ($date) {
          $query->whereDate('created_at', $date);
        })
          ->when($user_id, function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
          })
          ->orderBy('id', 'desc')
          ->paginate($perPage);

        $data = $notes->map(function ($note) {

          return [
            'id' => $note->id,
            'user' => $note->user->name,
            'note' => $note->note,
            'date' => Helpers::formatDateTime($note->created_at),
          ];
        });

        return response()->json([
          'status' => 'success',
          'message' => 'Notes retrieved successfully',
          'data' => $data,
          'current_page' => $notes->currentPage(),
          'last_page' => $notes->lastPage(),
          'per_page' => $notes->perPage(),
          'total' => $notes->total(),
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
          'note' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
          ], 500);
        }

        try {
          DB::transaction(function()use($request){
            $announcement = new Note();
            $announcement->user_id = Auth::user()->id;
            $announcement->note = $request->note;
            $announcement->save();
          });

          return response()->json([
            'status' => 'success',
            'message' => 'Note Stored Successfully',
          ]);
        } catch (Exception $e) {
          $message = CustomErrorMessages::getCustomMessage($e);

        return response()->json([
          'status' => 'error',
          'message' => $message,
        ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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
          DB::transaction(function()use($request, $note){

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      try {
        $note = Note::find($id);
        if (!$note) {
          return response()->json([
            'status' => 'error',
            'message' => 'Invalid Notes',
          ], 500);
        }

        DB::transaction(function () use ($note) {
          $note->delete();
        });

        return response()->json([
          'status' => 'success',
          'message' => 'Note Deleted Successfully',
        ]);
      } catch (Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);
        return response()->json([
          'status' => 'error',
          'message' => $message,
        ], 500);
      }
    }


}
