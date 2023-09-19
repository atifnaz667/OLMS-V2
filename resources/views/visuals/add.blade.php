@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')
@section('vendor-script')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection
@section('title', 'Add Visuals')
<style>
    .pagination-nav {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
    }
</style>

@section('content')
    @if (Session::has('status'))
      <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
      <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
      <input type="hidden" name="" id="tostType" value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

      {{ Session::forget('status') }}
      {{ Session::forget('message') }}
    @endif
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Home/Visual/</span>
        Add Visual
    </h4>

    <form id="visualForm"  method="post" action="{{ route('add-visual') }}" enctype="multipart/form-data">
        <div class="row">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                        <h5 class="card-title mb-sm-0 me-2">Add Visual</h5>
                        <div class="action-btns">
                            <a href="{{ route('visual.index') }}" class="btn btn-label-primary me-3">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label" for="board_id">Board</label>
                                <select id="board_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($boards as $board)
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="class_id">Class</label>
                                <select id="class_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="book_id">Book</label>
                                <select id="book_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="chapter_id">Chapter</label>
                                <select id="chapter_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label" for="topic_id">Topic</label>
                                <select id="topic_id" name="topic_id" class="select2 form-select" data-allow-clear="true"
                                    required>
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <br>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div data-repeater-list="visuals">
                        <div data-repeater-item id="repeater-div">
                          <div id="repeater-0">
                            <div class="card-header d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                <h5 class="card-title">Visual</h5>
                                <button class="btn btn-label-danger" data-repeater-delete type="button" onclick="deleteRepeater(this.value)" value="0">
                                    <i class="ti ti-x ti-xs me-1"></i>
                                    <span class="align-middle">Delete</span>
                                </button>
                            </div>

                            <div class="row mb-4">
                                <div class="col-6 col-sm-4">
                                    <label class="form-label" for="">Visual Type</label>
                                    <select id="type" class="form-select visual-type" required data-allow-clear="true"
                                        name="visual_type[]">
                                        <option value="video">Video</option>
                                        <option value="image">Image</option>
                                    </select>
                                </div>
                                <div class="col-6 col-sm-8">
                                    <div class="visual-div">
                                        <label class="form-label" for="">Video Url</label>
                                        <input type="text" required name="path[]" id="path" class="form-control"
                                            placeholder="Enter youtube video url">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-lg-12 col-xl-12 col-12">
                                    <label class="form-label" for="form-repeater-1-1">Visual Title</label>
                                    <textarea class="form-control " required name="title[]" rows="2" class="form-control" placeholder="Enter Title"></textarea>
                                </div>
                            </div>
                            <hr>
                          </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <button class="btn btn-primary" data-repeater-create type="button" id="add-repeater" onclick="addRepeater()">
                            <i class="ti ti-plus me-1"></i>
                            <span class="align-middle">Add</span>
                        </button>
                        <button type="submit" id="submitVisual" class="btn btn-primary"
                            style="float: right">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /Form Repeater -->


@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {
            initializeSummernote();
            $('button[data-repeater-create]').click(function() {
                setTimeout(function() {
                    initializeSummernote();
                }, 100); // Delay the initialization to ensure the DOM is updated
            });

            function initializeSummernote() {
                $('.summernote-1').summernote({
                    tabsize: 2,
                    height: 70,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        // ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            }

            $('#board_id, #book_id, #class_id').change(function() {
                var boardId = $('#board_id').val();
                var bookId = $('#book_id').val();
                var classId = $('#class_id').val();

                $.ajax({
                    url: '{{ route('chapterDropDown') }}',
                    method: 'GET',
                    data: {
                        board: $('#board_id').val(),
                        book: $('#book_id').val(),
                        class: $('#class_id').val()
                    },
                    success: function(response) {
                        var chapterSelect = $('#chapter_id');
                        chapterSelect.empty().append('<option value="">Select</option>');

                        if (response.status === 'success') {
                            var chapters = response.Chapters;
                            if (chapters && chapters.length > 0) {
                                $.each(chapters, function(index, chapter) {
                                    chapterSelect.append('<option value="' + chapter
                                        .id + '">' + chapter.name + '</option>');
                                });
                            }
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

            });
            $('#board_id, #book_id, #class_id, #chapter_id').change(function() {
                $.ajax({
                    url: '{{ route('topicDropDown') }}',
                    method: 'GET',
                    data: {

                        chapter: $('#chapter_id').val()
                    },
                    success: function(response) {
                        var topicSelect = $('#topic_id');
                        topicSelect.empty().append('<option value="">Select</option>');

                        if (response.status === 'success') {
                            var topics = response.Topics;
                            if (topics && topics.length > 0) {
                                $.each(topics, function(index, topic) {
                                    topicSelect.append('<option value="' + topic
                                        .id + '">' + topic.name + '</option>');
                                });
                            }
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

            });
        });

        $(document).on("change", ".visual-type", function() {
            let visual = '';
            let visualType = $(this).val();
            if (visualType == 'video') {
                visual =
                    '<label class="form-label" for="">Video Url</label> <input type="text" name="path[]"  class="form-control" placeholder="Enter youtube video url">';
            } else {
                visual =
                    '<label class="form-label" for="">Upload Image</label> <input type="file" accept="image/*" name="file[]" id="path" class="form-control" >';
            }

            $(this).closest('div.row').find('.visual-div').html(visual)

        });


let i = 0;

    function addRepeater(){
      i++;
      let repeater = '<div id="repeater-'+i+'"><div class="card-header d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">'+
                '<h5 class="card-title">Visual</h5>'+
                '<button class="btn btn-label-danger" data-repeater-delete type="button" onclick="deleteRepeater(this.value)" value="'+i+'">'+
                    '<i class="ti ti-x ti-xs me-1"></i>'+
                    '<span class="align-middle">Delete</span>'+
                '</button>'+
            '</div>'+
            '<div class="row mb-4">'+
                '<div class="col-6 col-sm-4">'+
                    '<label class="form-label" for="">Visual Type</label>'+
                    '<select id="type" class="form-select visual-type" required data-allow-clear="true" name="visual_type[]">'+
                        '<option value="video">Video</option>'+
                        '<option value="image">Image</option>'+
                    '</select>'+
                '</div>'+
                '<div class="col-6 col-sm-8">'+
                    '<div class="visual-div">'+
                        '<label class="form-label" for="">Video Url</label>'+
                        '<input type="text" required name="path[]"  class="form-control" placeholder="Enter youtube video url">'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="row">'+
                '<div class="mb-3 col-lg-12 col-xl-12 col-12">'+
                    '<label class="form-label" for="form-repeater-1-1">Visual Title</label>'+
                    '<textarea class="form-control " required name="title[]" rows="2" class="form-control" placeholder="Enter Title"></textarea>'+
                '</div>'+
            '</div>'+
            '<hr></div>';
      $("#repeater-div").append(repeater);
    }

    function deleteRepeater(index){
      $("#repeater-"+index).remove();
    }
    $(document).ready(function() {
          var status = $("#tostStatus").val();
          if (status) {
            var message = $("#tostMessage").val();
            showNotification(status,message);
          }
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
    </script>
@endsection
