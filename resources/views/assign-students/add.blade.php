@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('title', 'Assign Students')
<style>
    .pagination-nav {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
    }
</style>

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/Teacher/</span>
        Assign Students
    </h4>

    <form id="assignStudentForm" class="form-repeater">
        <div class="row">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">Assign Students</h5>
                        <div class="action-btns">
                            <a href="{{ route('assigned/students') }}" class="btn btn-label-primary me-3">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md">
                                <label class="form-label" for="teacher_id">Teacher</label>
                                <select id="teacher_id" name="teacher_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select Teacher</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="board_id">Board</label>
                                <select id="board_id" name="board_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($boards as $board)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="book_id">Book</label>
                                <select id="book_id" name="book_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label" for="class_id">Class</label>
                                <select id="class_id" name="class_id" class="select2 form-select" required
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                    
                            <div class="col-md-6">
                                <label class="form-label" for="students">Students</label>
                                <!-- <select id="student_id" name="student_id[]" class="select2 form-select" required multiple="multiple"
                                    data-allow-clear="true"> -->
                                    <select id="students" name="students[]" class="select2 form-select" multiple  >
                                    </select>
                                <!-- </select> -->
                            </div>
          
                        </div>
                        <div
                            class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                            <h5 class="card-title mb-sm-0 me-2"></h5>
                            <div class="action-btns">
                                <button type="button" id="submitAssignStudent" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <br>

    </form>
    <!-- /Form Repeater -->


@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {

            $('#submitAssignStudent').click(function() {
                const toastAnimationExample = document.querySelector('.toast-ex');
                var form = $('#assignStudentForm');
                var formData = form.serialize();

                $.ajax({
                    url: '{{ route('assignStudent/store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#assignStudentForm [data-repeater-item]').remove();
                        var status = response.status;
                        var message = response.message;
                        $('.toast-ex .fw-semibold').text(status);
                        $('.toast-ex .toast-body').text(message);

                        // Show the toast notification
                        selectedType = "text-success";
                        selectedAnimation = "animate__fade";
                        toastAnimationExample.classList.add(selectedAnimation);
                        toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                        toastAnimation = new bootstrap.Toast(toastAnimationExample);
                        toastAnimation.show();

                    },
                    error: function(xhr, status, error) {
                        var response = JSON.parse(xhr.responseText);
                        var status = response.status;
                        var message = response.message;

                        $('.toast-ex .fw-semibold').text(status);
                        $('.toast-ex .toast-body').text(message);
                        selectedType = "text-warning";
                        selectedAnimation = "animate__fade";
                        toastAnimationExample.classList.add(selectedAnimation);
                        toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                        toastAnimation = new bootstrap.Toast(toastAnimationExample);
                        toastAnimation.show();
                    }
                });
            });


            $('#teacher_id,#board_id, #book_id, #class_id').change(function() {
                var board_id = $('#board_id').val();
                var book_id = $('#book_id').val();
                var class_id = $('#class_id').val();
                var teacher_id = $('#teacher_id').val();

                $.ajax({
                    url: '{{ route('get/students/ajax')}}',
                    method: 'get',
                    data: {
                    board_id:board_id,
                    class_id:class_id,
                    book_id:book_id,
                    teacher_id:teacher_id,
                    },
                    success: function(response) {
                        console.log(response)
                    if (response.status == 'success') {
                        $("#students").html(response.students);
                        if (response.students == '<option value="">Select Students</option>') {
                        showNotification('error','Students not found against this board and class');
                        }
                        // getStudents(board_id,class_id);
                    }else{
                        var message = response.message;
                        var status = response.status;
                        showNotification(status,message);
                    }
                    }
                });

            });
            function showNotification(status,message){
            const toastAnimationExample = document.querySelector('.toast-ex');
            $('.toast-ex .fw-semibold').text(status);
            $('.toast-ex .toast-body').text(message);

            // Show the toast notification
            selectedType = $("#tostType").val();
            selectedAnimation = "animate__fade";
            toastAnimationExample.classList.add(selectedAnimation);
            toastAnimationExample.querySelector('.ti').classList.add(selectedType);
            toastAnimation = new bootstrap.Toast(toastAnimationExample);
            toastAnimation.show();
            }
        });
    </script>
@endsection
