<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Announcement;
use App\Models\AnnouncementClasses;
use App\Models\AssignTeacherStudent;
use App\Models\Board;
use App\Models\Classes;
use App\Models\User;
use App\Services\CustomErrorMessages;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $users = User::where('role_id', 3)->orderBy('name')->get();
    $boards = Board::orderBy('name')->get();
    $classes = Classes::orderBy('name')->get();
    return view('announcements.index', ['users' => $users,'boards'=>$boards,'classes'=>$classes]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function announcementsAjax(Request $req)
  {
    try {

      $date = $req->date;
      $perPage = $req->perPage;
      $status = $req->status;
      $type = $req->type;
      $posted_by = $req->posted_by;
      $title = $req->title;
      $user_id = null;
      if (Auth::user()->role_id == 3) {
        $user_id = Auth::user()->id;
      }

      $announcements = Announcement::when($date, function ($query) use ($date) {
        $query->whereDate('created_at', $date);
      })
        ->when($status, function ($q) use ($status) {
          $q->where('status', $status);
        })
        ->when($type, function ($q) use ($type) {
          $q->where('type', $type);
        })
        ->when($posted_by, function ($q) use ($posted_by) {
          $q->where('posted_by', $posted_by);
        })
        ->when($title, function ($q) use ($title) {
          $q->where('title', 'like', '%' . $title . '%');
        })
        ->when($user_id, function ($q) use ($user_id) {
          $q->where('posted_by', $user_id);
        })
        ->orderBy('id', 'desc')
        ->paginate($perPage);

      $data = $announcements->map(function ($announcement) {
        if ($announcement->status == 'Unpublished') {
          $status = '<span class="badge rounded bg-label-warning">' . $announcement->status . '</span>';
        } else {
          $status = '<span class="badge rounded bg-label-success">' . $announcement->status . '</span>';
        }
        return [
          'id' => $announcement->id,
          'title' => $announcement->title,
          'board' => $announcement->board->name ?? 'All Boards',
          'status' => $status,
          'user' => $announcement->postedBy->name,
          'date' => Helpers::formatDateTime($announcement->created_at),
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'announcements retrieved successfully',
        'data' => $data,
        'current_page' => $announcements->currentPage(),
        'last_page' => $announcements->lastPage(),
        'per_page' => $announcements->perPage(),
        'total' => $announcements->total(),
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
        'board' => 'required',
        'classes' => 'required',
        'title' => 'required',
        'description' => 'required',
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
          $announcement = new Announcement();
          $announcement->board_id = $request->board == 0 ? null : $request->board;
          $announcement->status = $request->status;
          $announcement->title = $request->title;
          $announcement->description = $request->description;
          $announcement->posted_by = Auth::user()->id;
          $announcement->type = Auth::user()->role_id == 3 ? 'Teacher' : 'Admin';
          $announcement->save();

          foreach ($request->classes as $class) {
            $announcementClasses = new AnnouncementClasses;
            $announcementClasses->announcement_id = $announcement->id;
            $announcementClasses->class_id = $class;
            $announcementClasses->save();
          }
        });

        return response()->json([
          'status' => 'success',
          'message' => 'Announcement Stored Successfully',
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
    $announcement = Announcement::with('board','announcementClasses.class')->find($id);
      if (!$announcement || (Auth::user()->role_id == 3  && Auth::user()->id != $announcement->posted_by)) {
        return response()->json([
          'status' => 'error',
          'message' => 'Invalid Announcement',
        ], 500);
      }
      $classIds = [];
      $classes = '';
      foreach ($announcement->announcementClasses as $announcementClass) {
        if ($announcementClass->class_id) {
          $classIds[] = $announcementClass->class->id;
          $classes = $classes . '<option value="' . $announcementClass->class->id . '" selected>' . $announcementClass->class->name . '</option>';
        }

      }
      $boards = '';
      if ($announcement->board) {
        $boards = $boards . '<option value="' . $announcement->board->id . '" selected>' . $announcement->board->name . '</option>';
      }
      $boards = $boards . '<option value="0" > All </option>';

      $getBoards = Board::where('id','!=',$announcement->board_id)->get();

      foreach ($getBoards as $board) {
        $boards = $boards . '<option value="' . $board->id . '">' . $board->name . '</option>';
      }
      $getClasses = Classes::whereNotIn('id',$classIds)->get();
      foreach ($getClasses as $class) {
        $classes = $classes . '<option value="' . $class->id . '">' . $class->name . '</option>';
      }

      $status = '<option value="' . $announcement->status . '" selected>' . $announcement->status . '</option><option value="Published" >Published</option> </option><option value="Unpublished" >Unpublished</option>';
    return response()->json([
      'status' => 'success',
      'message' => 'Announcement retrieved successfully',
      'status' => $status,
      'announcement' => $announcement,
      'classes' => $classes,
      'boards' => $boards,
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

    $announcement = Announcement::find($id);
      if (!$announcement || (Auth::user()->role_id != 1  && Auth::user()->id != $announcement->posted_by)) {
        return response()->json([
          'status' => 'error',
          'message' => 'Invalid Announcement',
        ], 500);
      }
      $rules = array(
        'update_board' => 'required',
        'update_classes' => 'required',
        'update_status' => 'required',
        'update_title' => 'required',
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
        DB::transaction(function()use($request, $announcement){

          $announcement->board_id = $request->update_board == 0 ? null : $request->update_board;
          $announcement->status = $request->update_status;
          $announcement->title = $request->update_title;
          $announcement->description = $request->update_description;
          $announcement->save();

          AnnouncementClasses::where('announcement_id',$announcement->id)->delete();

          foreach ($request->update_classes as $class) {
            $announcementClasses = new AnnouncementClasses;
            $announcementClasses->announcement_id = $announcement->id;
            $announcementClasses->class_id = $class;
            $announcementClasses->save();
          }
        });

        return response()->json([
          'status' => 'success',
          'message' => 'Announcement Updated Successfully',
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
      $announcement = Announcement::with('announcementsUsers')->find($id);
      if (!$announcement || (Auth::user()->role_id != 1  && Auth::user()->id != $announcement->posted_by)) {
        return response()->json([
          'status' => 'error',
          'message' => 'Invalid Announcement',
        ], 500);
      }

      DB::transaction(function () use ($announcement) {
        $announcement->announcementsUsers()->delete();
        $announcement->delete();
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Announcement Deleted Successfully',
      ]);
    } catch (Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

  public function noticeBoard(){

    try {
      $student = User::find( Auth::user()->id);
      $board_id = $student->board_id;
      $class_id = $student->class_id;
      $teachers = AssignTeacherStudent::where('student_id', Auth::user()->id)->get()->pluck('teacher_id');
      $admin = User::where('role_id',1)->get()->pluck('id');
      $poster = $teachers->merge($admin);

      $announcements = Announcement::
      where([['status','Published']])
      ->whereIn('posted_by',$poster)
      ->where(function($q)use($board_id){
        $q->where([['board_id',$board_id]])
        ->orWhere('board_id',null);
      })
      ->whereHas('announcementClasses',function($q)use($class_id){
        $q->where('class_id',$class_id);
      })->orderBy('id','desc')->paginate(10);

      $data = $announcements->map(function ($announcement) {
        return [
          'id' => $announcement->id,
          'title' => $announcement->title,
          'user' => $announcement->postedBy->name,
          'date' => Helpers::formatDateTime($announcement->created_at),
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'announcements retrieved successfully',
        'data' => $data,
        'current_page' => $announcements->currentPage(),
        'last_page' => $announcements->lastPage(),
        'per_page' => $announcements->perPage(),
        'total' => $announcements->total(),
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
