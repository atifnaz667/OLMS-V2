<?php

namespace App\Http\Controllers;

use App\Helpers\DropdownHelper;
use App\Models\AssignTeacherStudent;
use App\Models\BookPdf;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class UploadController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $results = DropdownHelper::getBoardBookClass();
    $books = $results['Books'];
    $boards = $results['Boards'];
    $classes = $results['Classes'];
    return view('upload-book.index', ['books' => $books, 'boards' => $boards, 'classes' => $classes]);
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
  public function uploadBookFile(Request $request)
  {
    $request->validate([
      'book_id' => 'required',
      'board_id' => 'required',
      'class_id' => 'required',
    ]);

    $bookPdf = new BookPdf();

    if ($request->hasFile('file')) {
      if ($request->hasFile('bookIcon')) {
        $uploadedIcon = $request->file('bookIcon');
        $extn = $uploadedIcon->getClientOriginalName();
        $iconName = time() . rand(1, 100) . '.' . $extn;
        $uploadedIcon->move(public_path() . '/files/booksIcon/', $iconName);
        $bookPdf->icon = $iconName;
      }
      $uploadedFile = $request->file('file');
      $ext = $uploadedFile->getClientOriginalName();
      $fileName = time() . rand(1, 100) . '.' . $ext;
      $uploadedFile->move(public_path() . '/files/booksPdf/', $fileName);
      $bookPdf->book_pdf = $fileName;
      $bookPdf->book_id = $request->book_id;
      $bookPdf->board_id = $request->board_id;
      $bookPdf->class_id = $request->class_id;
      $bookPdf->save();

      return response()->json([
        'status' => 'Success',
        'message' => 'File uploaded successfully',
      ]);
    } elseif ($request->filled('pdf_link')) {
      $pdfLink = $request->input('pdf_link');

      try {
        $client = new Client();
        $response = $client->get($pdfLink);
        $downloadedFileName = time() . rand(1, 100) . '.pdf';
        $filePath = 'files/booksPdf/' . $downloadedFileName;
        file_put_contents(public_path($filePath), $response->getBody());
        if ($request->hasFile('bookIcon')) {
          $uploadedIcon = $request->file('bookIcon');
          $extn = $uploadedIcon->getClientOriginalName();
          $iconName = time() . rand(1, 100) . '.' . $extn;
          $uploadedIcon->move(public_path() . '/files/booksIcon/', $iconName);
          $bookPdf->icon = $iconName;
        }
        $bookPdf->book_pdf = $downloadedFileName;
        $bookPdf->book_id = $request->book_id;
        $bookPdf->board_id = $request->board_id;
        $bookPdf->class_id = $request->class_id;
        $bookPdf->save();

        return response()->json([
          'status' => 'Success',
          'message' => 'PDF link uploaded successfully',
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'status' => 'Error',
          'message' => 'Failed to download PDF from the provided link',
        ]);
      }
    }

    return response()->json([
      'status' => 'Error',
      'message' => 'No file or PDF link provided for upload',
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function fetchUploadedBookPdfRecords(Request $request)
  {
    try {
      $bookPdfRecord = BookPdf::with('board', 'book', 'class')->get();

      $data = $bookPdfRecord->map(function ($bookPdfRecord) {
        return [
          'id' => $bookPdfRecord->id,
          'board' => $bookPdfRecord->board->name,
          'book' => $bookPdfRecord->book->name,
          'class' => $bookPdfRecord->class->name,
          'book_pdf' => $bookPdfRecord->book_pdf,
        ];
      });

      return response()->json([
        'status' => 'success',
        'message' => 'Students retrieved successfully',
        'data' => $data,
      ]);
    } catch (\Exception $e) {
      $message = CustomErrorMessages::getCustomMessage($e);

      return response()->json([
        'status' => 'error',
        'message' => $message,
      ], 500);
    }
  }

  public function fetchStudentBookPdfRecords()
  {
    $student_id = Auth::user()->id;
    $resultsArray = [];
    $bookIds = AssignTeacherStudent::where('student_id', $student_id)->get();
    foreach ($bookIds as $book_id) {
      $resultsArray[] = BookPdf::with('book')->where('book_id', $book_id->book_id)->first();
    }
    // return $resultsArray;
    return response()->json($resultsArray);
  }
  /**
   * Show the form for editing the specified resource.
   */
  //   $resultsArray = [
  //     'book_name' => [],
  //     'book_pdf' => [],
  // ];
  // $bookIds = AssignTeacherStudent::where('student_id', $student_id)->get();

  // foreach ($bookIds as $bookId) {
  //     // Assuming the $bookId object has a "book_id" property
  //     $bookPdf = BookPdf::with('book')->where('book_id', $bookId->book_id)->first();

  //     if ($bookPdf) {
  //         $resultsArray['book_name'][] = $bookPdf->book->name;
  //         $resultsArray['book_pdf'][] = $bookPdf->book_pdf;
  //     }
  // }
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
  public function destroy($id)
  {
    $validator = Validator::make(
      ['id' => $id],
      [
        'id' => 'required|int|exists:book_pdfs,id',
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

    try {
      DB::transaction(function () use ($id) {
        BookPdf::findOrFail($id)->delete();
      });

      return response()->json(
        [
          'status' => 'success',
          'message' => 'Book Pdf deleted successfully',
        ],
        200
      );
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
}
