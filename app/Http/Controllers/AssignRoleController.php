<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignRole;
use App\Models\User;
use App\Models\Book;
use App\Models\Classes;
use App\Models\Board;
use Illuminate\Support\Facades\Validator;
use App\Services\CustomErrorMessages;

use App\Helpers\DropdownHelper;

class AssignRoleController extends Controller
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
      $users = User::with('role')->get();
      $staffs = User::where('role_id',5)->get();

      $assignRolesUsers = AssignRole::with('staff','book','class','board')->get();

      return view('assign-role.index', ['users' => $users, 'classes' => $classes, 'boards' => $boards,'books' => $books,'staffs' => $staffs,'assignRolesUsers' => $assignRolesUsers]);
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


        try {
            // Validate the request data
            $validatedData = $request->validate([
                'staff_id' => 'required',
                'board_ids' => 'required|array',
                'class_ids' => 'required|array',
                'book_ids' => 'required|array',
            ]);

            $staff_id = $request->input('staff_id');
            $board_ids = $request->input('board_ids');
            $class_ids = $request->input('class_ids');
            $book_ids = $request->input('book_ids');

            for ($i = 0; $i < count($board_ids); $i++) {
                // Check if a record with the same attributes already exists
                $existingRecord = AssignRole::where([
                    'staff_id' => $staff_id,
                    'board_id' => $board_ids[$i],
                    'class_id' => $class_ids[$i],
                    'subject_id' => $book_ids[$i],
                ])->first();

                // If an existing record is not found, create a new one
                if (!$existingRecord) {
                    $assignRole = new AssignRole();
                    $assignRole->staff_id = $staff_id;
                    $assignRole->board_id = $board_ids[$i];
                    $assignRole->class_id = $class_ids[$i];
                    $assignRole->subject_id = $book_ids[$i];
                    $assignRole->save();
                }
            }


            // Return success status and message
            return response()->json([
                'status' => 'success',
                'message' => 'Roles Assign created successfully.',
            ]);
        } catch (\Exception $e) {
            // Return error status and message

           return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
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
            ['id' => 'required|exists:users,id']
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'errors', 'message' => $validator->errors()->first()], 422);
        }

        try {
            $assignRolesUsers = AssignRole::where('staff_id', $id)->with('staff', 'book', 'class', 'board')->get();
            $User = User::with('role')->findOrFail($id);


            $results = DropdownHelper::getBoardBookClass();
            $boards = $results['Boards'];
            $books = $results['Books'];
            $classes = $results['Classes'];

            $staffs = User::where('role_id', 5)->get();
            $selectedStaffId = $id;

            $staffDropdown = '
            <div class="row">
                <div class="col-md">
                    <label class="form-label" for="staff_id">Staff</label>
                    <select id="staff_idd" name="staff_idd" class="select2 form-select" required data-allow-clear="true">
                        <option value="">Select Staff</option>';

            foreach ($staffs as $staff) {
                $selected = ($selectedStaffId == $staff->id) ? 'selected' : '';
                $staffDropdown .= "<option $selected value=\"$staff->id\">$staff->name</option>";
            }

            $staffDropdown .= '
                    </select>
                </div>
            </div>';


            $data = [];
            foreach ($assignRolesUsers as $assignRolesUser) {
                $row = '
                <div class="row mt-3 us_remove">
                    <div class="col-md">
                        <label class="form-label" for="board_idss">Board</label>
                        <select id="board_idss" name="board_idss[]" class="select2 form-select board-selectr" required data-allow-clear="true">
                            <option value="">Select</option>';
                foreach ($boards as $board) {
                    $selected = ($assignRolesUser->board_id == $board->id) ? 'selected' : '';
                    $row .= "<option $selected value=\"$board->id\">$board->name</option>";
                }
                $row .= '
                        </select>
                    </div>
                    <div class="col-md">
                        <label class="form-label" for="class_idss">Class</label>
                        <select id="class_idss" name="class_idss[]" class="select2 form-select class-selectr" required data-allow-clear="true">
                            <option value="">Select</option>';
                foreach ($classes as $classe) {
                    $selected = ($assignRolesUser->class_id == $classe->id) ? 'selected' : '';
                    $row .= "<option $selected value=\"$classe->id\">$classe->name</option>";
                }
                $row .= '
                        </select>
                    </div>
                    <div class="col-md">
                        <label class="form-label" for="book_idss">Book</label>
                        <select id="book_idss" name="book_idss[]" class="select2 form-select book-selectr" required data-allow-clear="true">
                            <option value="">Select</option>';
                foreach ($books as $book) {
                    $selected = ($assignRolesUser->subject_id == $book->id) ? 'selected' : '';
                    $row .= "<option $selected value=\"$book->id\">$book->name</option>";
                }

                $row .= '
                        </select>
                    </div>
                    <div class="col-md">

                        <button type="button" onclick="return removeRow1(this)" class="btn btn-danger delete-row-button" style="
                        margin-top: 21px;
                    ">
                            <i class="ti ti-x ti-xs me-1"></i>
                            <span class="align-middle"></span>
                        </button>
                    </div>
                </div>';
                $data[] = $row;
            }


            return response()->json([
                'status' => 'success',
                'message' => 'User retrieved successfully',
                'User' => $assignRolesUsers,
                'staffDropdown' => $staffDropdown,
                'data' => $data, // Include the generated HTML in the response
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


     public function update(Request $request, $id)
     {
      
         try {
             // Validate the request data
             $validatedData = $request->validate([
                 'staff_idd' => 'required',
                 'board_idss' => 'required|array',
                 'class_idss' => 'required|array',
                 'book_idss' => 'required|array',
             ]);

             $staff_id = $request->input('staff_idd');
             $board_ids = $request->input('board_idss');
             $class_ids = $request->input('class_idss');
             $book_ids = $request->input('book_idss');

             AssignRole::where('staff_id',$staff_id)->delete();
             // Loop through the provided data
             for ($i = 0; $i < count($board_ids); $i++) {
                 // Check if a record with the same attributes already exists
                  $existingRecord = AssignRole::where([
                    'staff_id' => $staff_id,
                    'board_id' => $board_ids[$i],
                    'class_id' => $class_ids[$i],
                    'subject_id' => $book_ids[$i],
                ])->first();
                // If an existing record is not found, create a new one
                if (!$existingRecord) {
                     // Create a new record
                     AssignRole::create([
                         'staff_id' => $staff_id,
                         'board_id' => $board_ids[$i],
                         'class_id' => $class_ids[$i],
                         'subject_id' => $book_ids[$i],
                     ]);
                    }
             }

             // Return a success response
             return response()->json([
                 'status' => 'success',
                 'message' => 'Roles Assign updated successfully.',
             ]);
         } catch (\Exception $e) {
             // Handle any exceptions and return an error response
             return response()->json([
                 'status' => 'error',
                 'message' => $e->getMessage(),
             ]);
         }
     }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
