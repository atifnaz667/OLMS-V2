<?php

namespace App\Http\Controllers;

use App\Models\AssignTeacherStudent;
use App\Models\Comment;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getStudentComment()
    {
        try {

          $student_id = Auth::user()->id;
          $comments= Comment::with('teacher','book')
            ->where('student_id',$student_id)->get();
    
          return view('students.comment-list',['comments' =>$comments]);
        } catch (\Exception $e) {
          $message = CustomErrorMessages::getCustomMessage($e);
    
          return back()->with('error', $message);
        }
      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function myTeacherList()
    {
        try {

              $student_id = Auth::user()->id;
              $teachers= AssignTeacherStudent::with('teacher','book')
              ->where('student_id',$student_id)->get();
        
              return view('students.teacher-list', ['teachers' => $teachers]);
            } catch (\Exception $e) {
              $message = CustomErrorMessages::getCustomMessage($e);
        
              return back()->with('error', $message);
            }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
