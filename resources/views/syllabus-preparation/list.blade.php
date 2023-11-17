@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/ui-carousel.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/ui-carousel.js')}}"></script>
@endsection
@section('title', 'My Digital Bag')
<style>
    .card-header.header-elements,
    .card-title.header-elements {
        flex-direction: column !important;
        align-items: stretch !important;
        justify-content: space-around !important;
    }

    .custom {
        display: flex;
        align-items: center !important;
    }

    .list-group-item:first-child {
        border-top-left-radius: 0px !important;
        border-top-right-radius: 0px !important;
    }
</style>
@section('content')

    <h4 class="fw-bold py-3 mb-2">
    Books Pdf
</h4>
    {{-- <div class="card">
    <!-- <h4 class="card-header text-primary">Books Pdf</h4> -->
    <!-- <div class="container"> -->
    <div id="carouselExampleIndicators" class="carousel slide ml-5" data-ride="carousel" style="height: 200px;">
        <div class="carousel-inner">
            @foreach ($bookPdf->chunk(3) as $chunk)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <div class="row justify-content-center">
                    @foreach ($chunk as $bookPd)
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <img class="d-block w-100" src="{{ asset('files/books/' . $bookPd->book->file) }}"
                                    alt="{{ $bookPd->book->name }}" style="max-height: 200px; max-width: 350px;">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 style="color: black;">{{ $bookPd->book->name }}</h5>
                                    <a href="{{ asset('files/booksPdf/' . $bookPd->book_pdf) }}" class="button-link" target="_blank" style="color: blue;">
                                        View Book
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon  text-primary" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon btn-primary" aria-hidden="true"></span>
            <span class="sr-only  ">Next</span>
        </a>
    </div>
</div> --}}
    <div class="row">
      <div class="col-12 mb-4">
        <div class="swiper" id="swiper-3d-coverflow-effect">
          <div class="swiper-wrapper">
            @foreach ($bookPdf as $bookPdf)
              <div class="swiper-slide" style="background-image:url({{ asset('files/books/' . $bookPdf->book->file) }})">
                <a href="{{ asset('files/booksPdf/' . $bookPdf->book_pdf) }}"  target="_blank" style="color:white; font-weight:bold; background-color:black">
                {{ $bookPdf->book->name }}
                </a>
              </div>
            </a>
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>


    <h4 class="fw-bold py-3 mb-2">

        Preparation
    </h4>
    <!-- The modal -->
    <div class="modal fade" id="chapterModal" tabindex="-1" aria-labelledby="chapterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chapterModalLabel">Select Units and Topics</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="px-2">
                        <div class="row">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAllChapters">
                                <label class="form-check-label" for="checkAllChapters">Check All</label>
                                <input type="hidden" class="form-control" required id="test-type" name="test-type">
                            </div>
                        </div>

                        <div id="chapterList" class="row mt-3">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label" for="class-name">Total Questions</label>
                            <input type="text" class="form-control" required id="total-questions" name="total-questions">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label" for="class-name">Total Long Questions</label>
                            <input type="text" class="form-control" id="total-long-questions"
                                name="total-long-questions">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label" for="class-name">Total Short Questions</label>
                            <input type="text" class="form-control" id="total-short-questions"
                                name="total-short-questions">
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" id="startPreparation">Start Preparation</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($books as $book)
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title header-elements">
                            <div class="row">
                                <!-- Left side: Book icon and name -->
                                <div class="col-12 col-sm-6 text-center">

                                    <h5 class="m-0">
                                        @if ($book->file != null)
                                            <img src="files/books/{{ $book->file }}" alt="Book Icon"
                                                style=" height: 8em; width:8em;">
                                        @else
                                            {{-- <i class="fa-solid fa-book fa-2xl" style=""></i> --}}
                                        @endif
                                        {{-- {{ $book->name }} --}}
                                    </h5>
                                    <h5 class="m-0"> {{ $book->name }}</h5>
                                </div>
                                <!-- Right side: Paper type buttons -->
                                <div class="col-12 col-sm-6 mt-4 custom text-center d-flex justify-content-center">
                                    <div class="list-group">
                                        <button type="button"
                                            class="list-group-item list-group-item-action btn-outline-primary waves-effect mb-2"
                                            data-bs-toggle="modal" data-bs-target="#chapterModal"
                                            data-book-id="{{ $book->id }}" data-book-name="{{ $book->name }}"
                                            data-question-type="Objective">Multiple Choice Questions</button>


                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button"
                                                class="list-group-item list-group-item-action btn-outline-danger dropdown-toggle waves-effect"
                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">Detailed Questions</button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#chapterModal" data-book-id="{{ $book->id }}"
                                                    data-question-type="Subjective" href="javascript:void(0);">SLO Based
                                                    &
                                                    Exercise</a>
                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#chapterModal" data-book-id="{{ $book->id }}"
                                                    data-question-type="Conceptual" href="javascript:void(0);">SLO
                                                    Based
                                                    Questions</a>
                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#chapterModal" data-book-id="{{ $book->id }}"
                                                    data-question-type="Exercise" href="javascript:void(0);">Exercise
                                                    Questions</a>
                                            </div>
                                        </div>

                                        <button style="border-radius: 0px !important" type="button"
                                            class="list-group-item list-group-item-action btn-outline-primary waves-effect mt-2"
                                            data-bs-toggle="modal" data-bs-target="#chapterModal"
                                            data-book-id="{{ $book->id }}" data-book-name="{{ $book->name }}"
                                            data-question-type="Visual">Videos and Animations</button>

                                        <a href="{{ url('keyPoints/' . $book->id) }}" type="button"
                                            style="border-top-width: thin;border-radius: 0px !important"
                                            class="list-group-item list-group-item-action btn-outline-danger waves-effect mt-2"
                                            data-book-id="{{ $book->id }}" data-book-name="{{ $book->name }}"
                                            data-question-type="Objective">Key
                                            Points</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {
            $('#chapterModal').on('show.bs.modal', function(event) {
                $('#test-type').val("");
                var button = $(event.relatedTarget);
                var bookId = button.data('book-id');
                var questionType = button.data('question-type');
                $('#test-type').val(questionType);
                var totalQuestionsField = $('#total-questions').closest('.row');
                var totalLongQuestionsField = $('#total-long-questions').closest('.row');
                var totalShortQuestionsField = $('#total-short-questions').closest('.row');

                if (questionType === 'Objective') {
                    totalQuestionsField.show();
                    totalLongQuestionsField.hide();
                    totalShortQuestionsField.hide();
                } else if (questionType === 'Visual') {
                    totalQuestionsField.hide();
                    totalLongQuestionsField.hide();
                    totalShortQuestionsField.hide();
                } else {
                    totalQuestionsField.hide();
                    totalLongQuestionsField.show();
                    totalShortQuestionsField.show();
                }
                // Fetch chapter and topic data using AJAX based on bookId
                $.ajax({
                    url: "{{ route('fetch-chapters-topics', '') }}" + "/" + bookId,
                    data: {
                        questionType: questionType
                    },
                    method: "GET",
                    success: function(response) {
                        // Build the chapter and topic checkboxes dynamically
                        var chapterList = "";
                        $.each(response.chapters, function(index, chapter) {
                            chapterList +=
                                '<div class="mb-2 p-2" ><div class="form-check">';
                            chapterList +=
                                '<input class="form-check-input chapter-checkbox" type="checkbox" id="chapter_' +
                                chapter.id + '">';
                            chapterList +=
                                '<input type="hidden" id="book_id" value="' +
                                chapter.book_id + '">';
                            chapterList +=
                                '<h5 class="form-check-h5" for="chapter_' +
                                chapter.id + '">' + chapter.name + '</h5>';
                            chapterList += '</div>';
                            chapterList += '<div id="topicList_' + chapter.id +
                                '" class="row mb-4 " ></div></div>';
                        });
                        $('#chapterList').html(chapterList);

                        // Handle chapter checkbox change event
                        $('.chapter-checkbox').change(function() {
                            var chapterId = $(this).attr('id').split('_')[1];
                            var isChecked = $(this).prop('checked');
                            $('#topicList_' + chapterId).find('.topic-checkbox').prop(
                                'checked', isChecked);
                        });

                        // Build the topic checkboxes dynamically
                        $.each(response.topics, function(index, topic) {
                            var topicList =
                                '<div class="col-sm-4"><div class="form-check">';
                            topicList +=
                                '<input class="form-check-input topic-checkbox" type="checkbox" id="topic_' +
                                topic.id + '">';
                            topicList += '<h6 class="form-check-h6" for="topic_' +
                                topic.id + '">' + topic.name + '</h6>';
                            topicList += '</div></div>';
                            $('#topicList_' + topic.chapter_id).append(topicList);
                        });
                    }
                });
            });

            $('#checkAllChapters').change(function() {
                var isChecked = $(this).prop('checked');
                $('.chapter-checkbox').prop('checked', isChecked);
                $('.topic-checkbox').prop('checked', isChecked);
            });

            $('#startPreparation').click(function() {
                var selectedChapters = [];
                var selectedTopics = [];
                var testType = $('#test-type').val();
                var totalQuestions = $('#total-questions').val();
                var totalLongQuestions = $('#total-long-questions').val();
                var totalShortQuestions = $('#total-short-questions').val();

                $('.chapter-checkbox:checked').each(function() {
                    selectedChapters.push($(this).attr('id').split('_')[1]);
                });

                $('.topic-checkbox:checked').each(function() {
                    selectedTopics.push($(this).attr('id').split('_')[1]);
                });

                if (selectedTopics.length === 0) {
                    alert('Please select at least  one topic.');
                    return;
                }
                var questionType = $('#test-type').val();
                if (questionType === 'Objective') {
                    if (totalQuestions.trim() === '') {
                        alert('Please enter the total number of questions.');
                        return;
                    }

                } else if (questionType === 'Visual') {

                } else {
                    if (totalLongQuestions.trim() === '' && totalShortQuestions.trim() === '') {
                        alert('Please enter the total number of both questions.');
                        return;
                    }

                }

                var form = $('<form>', {
                    'action': "{{ route('get-test-for-preparation') }}",
                    'method': 'POST'
                });

                if (questionType === 'Visual') {
                    form = $('<form>', {
                        'action': "{{ route('get.visuals') }}",
                        'method': 'POST'
                    });
                }

                var csrfToken = $('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': "{{ csrf_token() }}"
                });

                var totalQuestionsInput = $('<input>', {
                    'type': 'hidden',
                    'name': 'totalQuestions',
                    'value': totalQuestions
                });

                var totalLongQuestionsInput = $('<input>', {
                    'type': 'hidden',
                    'name': 'totalLongQuestions',
                    'value': totalLongQuestions
                });

                var totalShortQuestionsInput = $('<input>', {
                    'type': 'hidden',
                    'name': 'totalShortQuestions',
                    'value': totalShortQuestions
                });

                var testTypeInput = $('<input>', {
                    'type': 'hidden',
                    'name': 'testType',
                    'value': testType
                });

                $.each(selectedChapters, function(index, chapterId) {
                    var chapterInput = $('<input>', {
                        'type': 'hidden',
                        'name': 'chapters[]',
                        'value': chapterId
                    });
                    form.append(chapterInput);
                });
                var bookId = $('#book_id').val();
                var bookIdInput = $('<input>', {
                    'type': 'hidden',
                    'name': 'bookId',
                    'value': bookId
                });
                form.append(bookIdInput);
                $.each(selectedTopics, function(index, topicId) {
                    var topicInput = $('<input>', {
                        'type': 'hidden',
                        'name': 'topics[]',
                        'value': topicId
                    });
                    form.append(topicInput);
                });

                form.append(csrfToken);
                form.append(totalQuestionsInput);
                form.append(totalLongQuestionsInput);
                form.append(totalShortQuestionsInput);
                form.append(testTypeInput);

                $('body').append(form);
                form.submit();
            });
        });
    </script>


@endsection
