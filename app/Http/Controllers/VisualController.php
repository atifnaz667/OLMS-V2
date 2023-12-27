<?php

namespace App\Http\Controllers;

use App\Helpers\DropDownHelper;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Visual;
use App\Services\CustomErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class VisualController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $rules = [
      'perPage' => 'integer|min:1',
      'sort_by' => 'in:description,id',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }
    $perPage = $request->input('perPage', 10);
    $sort = $request->input('sort_by', 'id');
    $sort_order = $request->input('sort_order', 'asc');
    $topicId = $request->input('topic_id');
    $board_id = $request->input('board_id');
    $class_id = $request->input('class_id');
    $book_id = $request->input('book_id');
    $chapter_id = $request->input('chapter_id');
    $visual_type = $request->input('type');
    $searchQuery = $request->input('searchQuery');

    $visuals = Visual::with('topic')->orderBy($sort, $sort_order)
      ->when($topicId, function ($q) use ($topicId) {
        $q->where('topic_id', $topicId);
      })
      ->when($searchQuery, function ($q) use ($searchQuery) {
        $q->where('title', 'like', '%' . $searchQuery . '%');
      })
      ->when($visual_type, function ($q) use ($visual_type) {
        $q->where('visual_type', $visual_type);
      })
      ->when($chapter_id, function ($q) use ($chapter_id) {
        $q->whereHas('topic', function ($q) use ($chapter_id) {
          $q->where('chapter_id', $chapter_id);
        });
      })
      ->when($book_id, function ($q) use ($book_id) {
        $q->whereHas('topic', function ($q) use ($book_id) {
          $q->whereHas('chapter', function ($q) use ($book_id) {
            $q->where('book_id', $book_id);
          });
        });
      })
      ->when($class_id, function ($q) use ($class_id) {
        $q->whereHas('topic', function ($q) use ($class_id) {
          $q->whereHas('chapter', function ($q) use ($class_id) {
            $q->where('class_id', $class_id);
          });
        });
      })
      ->when($board_id, function ($q) use ($board_id) {
        $q->whereHas('topic', function ($q) use ($board_id) {
          $q->whereHas('chapter', function ($q) use ($board_id) {
            $q->where('board_id', $board_id);
          });
        });
      })
      ->paginate($perPage);

    if ($request->check) {
      $data = $visuals->map(function ($visual) {
        if ($visual->visual_type == 'video') {
          $path = '<iframe width="300" height="169" src="' . $visual->path . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
        } else {
          $path = '<img src="' . asset('assets/img/syllabus/' . $visual->path) . '" height="169">';
        }
        return [
          'id' => $visual->id,
          'title' => $visual->title,
          'visual_type' => $visual->visual_type,
          'path' => $path,
          'topic' => $visual->topic->name,
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Visuals retrieved successfully',
        'data' => $data,
        'current_page' => $visuals->currentPage(),
        'last_page' => $visuals->lastPage(),
        'per_page' => $visuals->perPage(),
        'total' => $visuals->total(),
      ]);
    }
    $results = DropDownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('visuals.index', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $results = DropDownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('visuals.add', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {

    $rules = [
      'topic_id' => 'required|exists:topics,id',
      'visual_type' => 'required|array',
      'visual_type.*' => 'in:image,video',
      'title' => 'required|array',
      'title.*' => 'string',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return back()->with(['status' => 'error', 'message' => $validator->errors()->first()]);
    }
    try {
      DB::beginTransaction();
      $visual_type = $request->visual_type;
      $i = 0;
      $j = 0;
      foreach ($request->title as $key => $title) {
        $visual = new Visual();
        $visual->title = $title;
        $visual->visual_type = $visual_type[$key];
        $visual->topic_id = $request->topic_id;
        $path = '';
        $files = $request->file('file');
        if ($visual_type[$key] == 'image') {
          if ($request->hasFile('file')) {
            $reqFile = $files[$i];
            $path = public_path('syllabus');
            $link = $path . '/' . $visual->path;
            if (file_exists($link)) {
              @unlink($link);
            }
            $filename = time() . '-' . $reqFile->getClientOriginalName();
            $reqFile->move(public_path('assets/img/syllabus/'), $filename);
            $path = $filename;
            $i++;
          }
        } else {
          $path = $request->path[$j];
          $j++;
        }
        $visual->path = $path;
        $visual->save();
      }
      DB::commit();
      return redirect('visuals')->with(['status' => 'success', 'message' => 'Visual stored successfully']);
    } catch (\Exception $e) {
      DB::rollBack();
      return $message = $e->getMessage();
      return back()->with(['status' => 'error', 'message' => $message]);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $validator = Validator::make(
      ['id' => $id],
      [
        'id' => 'required|int|exists:visuals,id',
      ]
    );

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 'error',
          'message' => $validator->errors()->first(),
        ],
        400
      );
    }

    $Visual = Visual::findOrFail($id);

    return response()->json(['Visual' => $Visual], 200);
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
  public function update(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'visual_type' => 'required',
      'title' => 'required',
      'visualId' => 'required|exists:visuals,id',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
    }

    try {
      $visual = Visual::findOrFail($request->visualId);
      $visual->title = $request->input('title');
      $visual->visual_type = $request->input('visual_type');
      $path = '';
      if ($request->input('visual_type') == 'image') {
        if ($request->file('path')) {
          $file = $request->file('path');
          $path = public_path('syllabus');
          $link = $path . '/' . $visual->path;
          if (file_exists($link)) {
            @unlink($link);
          }
          $filename = time() . '-' . $file->getClientOriginalName();
          $file->move(public_path('assets/img/syllabus/'), $filename);
          $path = $filename;
        }
      } else {
        $path = $request->input('path');
      }
      $visual->path = $path ?? $visual->path;
      $visual->save();

      return back()->with(['status' => 'success', 'message' => 'Visual updated successfully']);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);
      return back()->with(['status' => 'error', 'message' => $message]);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $visual = Visual::find($id);
    if (!$visual) {
      return response()->json(['status' => 'error', 'message' => 'Invalid Visual']);
    } else {
      // if ($visual->type == 'image') {
      //   if (file_exists(public_path('assets/img/syllabus/'.$visual->path))) {
      //     @unlink(public_path('assets/img/syllabus/'.$visual->path));
      //   }
      //   File::delete(public_path('assets/img/syllabus/'.$visual->path));
      //   if (File::exists(public_path('assets/img/syllabus/'.$visual->path))) {
      // }
      // }
      $visual->delete();
      return response()->json(['status' => 'success', 'message' => 'Visual deleted successfully']);
    }
  }

  public function getVisualsForStudent(Request $req)
  {
    $chapters = $req->chapters ?? [];
    $getTopics = Topic::whereIn('chapter_id', $chapters)->pluck('id')->toArray();
    $reqTopics = $req->topics ?? [];
    $topics = array_merge($getTopics, $reqTopics);
    return view('visuals.list', ['topics' => $topics]);
  }

  public function getVisualsForStudentAjax(Request $req)
  {
    $chapters = Chapter::with(['topics' => function ($q) use ($req) {
      $q->whereHas('visuals', function ($q) use ($req) {
        $q->whereIn('topic_id', $req->topics)
          ->where('visual_type', $req->visual_type);
      })->with(['visuals' => function ($q) use ($req) {
        $q->whereIn('topic_id', $req->topics)
          ->where('visual_type', $req->visual_type);
      }]);
    }])->whereHas('topics', function ($q) use ($req) {
      $q->whereHas('visuals', function ($q) use ($req) {
        $q->whereIn('topic_id', $req->topics)
          ->where('visual_type', $req->visual_type);
      });
    })->get();

    return response()->json([
      'status' => 'success',
      'message' => 'Visuals retrieved successfully',
      'chapters' => $chapters,
    ]);
  }
}
