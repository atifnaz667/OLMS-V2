<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Board;
use App\Models\Chapter;
use App\Models\Classes;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminTestController extends Controller
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
      $boards = Board::orderBy('name','asc')->get();
      $classes = Classes::orderBy('name','asc')->get();
      $timeOptions = Helpers::getTimeForQuestions();
      return view('test.admin-test.add',['timeOptions'=>$timeOptions,'boards'=>$boards,'classes'=>$classes]);
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


    public function getBooksAjax(Request $req){
      $rules = array(
        'board_id' => 'required|int|exists:boards,id',
        'class_id' => 'required|int|exists:classes,id'
      );
      $validator = Validator::make($req->all(), $rules);
      if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
      }
      try {
        $board_id = $req->board_id;
        $class_id = $req->class_id;
        $getBooks = Chapter::with('book')
        ->whereIn('id', function ($query) use ($board_id, $class_id) {
          $query->selectRaw('MIN(id)')
            ->from('chapters')
            ->where('board_id', $board_id)
            ->where('class_id', $class_id)
            ->groupBy('book_id');
        })
        ->get()
        ->pluck('book');

        $books = '<option value="">Select Book</option>';

        foreach ($getBooks as $book) {
          $books = $books.'<option value="'.$book->id.'">'.$book->name.'</option>';
        }
        return response()->json(['status' => 'success', 'books' => $books], 200);
      } catch (\Exception $e) {
        $message = CustomErrorMessages::getCustomMessage($e);
        return response()->json(['status' => 'error', 'message' => $message], 500);
      }

    }

    public function getChaptersForTest(Request $req){

      $chapters = Chapter::where([['book_id',$req->book_id],['board_id',$req->board_id],['class_id',$req->class_id]])->get();
      $cols = ' <div class="col-12 mb-2"> <input class="form-check-input " style="margin-right:1em" id="select-all" onclick="selectCheckboxes()" type="checkBox"> Select All</div>';
      foreach ($chapters as $chapter) {
        $cols = $cols.' <div class="col-sm-3 col-6 mb-2"> <input style="margin-right:1em" onclick="selectCheckbox()" type="checkBox" name="chapters[]" class="form-check-input checkboxes" value="'.$chapter->id.'"> '.$chapter->name.'</div>';
      }
      if (count($chapters) == 0) {
        $cols = ' <div class="col-12"> <h6>No Chapters found against this book </h6></div>';
      }
      if (!$req->book_id) {
        $cols = ' <div class="col-12"> <h6>Please select book </h6></div>';
      }
      return $cols;
    }
}
