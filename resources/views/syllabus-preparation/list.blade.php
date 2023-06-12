@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Syllabus Preparation')

@section('content')
    <h4 class="fw-bold py-3 mb-2">
        <span class="text-muted fw-light">Syllabus/</span>
        Preparation
    </h4>

    <!-- The modal -->
    <div class="modal fade" id="chapterModal" tabindex="-1" aria-labelledby="chapterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chapterModalLabel">Select Chapters and Topics</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkAllChapters">
                            <label class="form-check-label" for="checkAllChapters">Check All</label>
                        </div>
                    </div>

                    <div id="chapterList" class="row">
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label" for="class-name">Total Questions</label>
                            <input type="text" class="form-control" required id="total-questions" name="total-questions">
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveSelection">Start Preparation</button>
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
                            <h5 class="m-0 me-2"><i class="fa-solid fa-book fa-2xl" style="margin-right:1em"></i>
                                {{ $book->name }}</h5>
                            <div class="card-title-elements">
                            </div>
                            <div class="card-title-elements ms-auto">
                                <select id="test-type" name="test-type" class="form-select form-select-sm w-auto">
                                    <option selected="">Option 1</option>
                                    <option>Option 2</option>
                                    <option>Option 3</option>
                                </select>
                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light"
                                    data-bs-toggle="modal" data-bs-target="#chapterModal"
                                    data-book-id="{{ $book->id }}">Go</button>
                            </div>
                        </div>
                        <p class="card-text">{{ Auth::user()->class->name }}</p>
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
                var button = $(event.relatedTarget);
                var bookId = button.data('book-id');

                // Fetch chapter and topic data using AJAX based on bookId
                $.ajax({
                    url: "{{ route('fetch-chapters-topics', '') }}" + "/" + bookId,
                    method: "GET",
                    success: function(response) {
                        // Build the chapter and topic checkboxes dynamically
                        var chapterList = "";
                        $.each(response.chapters, function(index, chapter) {
                            chapterList += '<div class="form-check">';
                            chapterList +=
                                '<input class="form-check-input chapter-checkbox" type="checkbox" id="chapter_' +
                                chapter.id + '">';
                            chapterList +=
                                '<h5 class="form-check-h5" for="chapter_' +
                                chapter.id + '">' + chapter.name + '</h5>';
                            chapterList += '</div>';
                            chapterList += '<div id="topicList_' + chapter.id +
                                '" class="row row-cols-4"></div>';
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
                            var topicList = '<div class="form-check">';
                            topicList +=
                                '<input class="form-check-input topic-checkbox" type="checkbox" id="topic_' +
                                topic.id + '">';
                            topicList += '<h6 class="form-check-h6" for="topic_' +
                                topic.id + '">' + topic.name + '</h6>';
                            topicList += '</div>';
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


            $('#saveSelection').click(function() {
                var selectedChapters = [];
                var selectedTopics = [];
                var testType = $('#test-type').val();
                var totalQuestions = $('#total-questions').val();
                if (selectedChapters.length === 0 || selectedTopics.length === 0) {
                    alert('Please select at least one chapter and one topic.');
                    return;
                }

                if (totalQuestions.trim() === '') {
                    alert('Please enter the total number of questions.');
                    return;
                }

                $('.chapter-checkbox:checked').each(function() {
                    selectedChapters.push($(this).attr('id').split('_')[1]);
                });


                $('.topic-checkbox:checked').each(function() {
                    selectedTopics.push($(this).attr('id').split('_')[1]);
                })
                var data = {
                    totalQuestions: totalQuestions,
                    testType: testType,
                    chapters: selectedChapters,
                    topics: selectedTopics
                };
                $.ajax({
                    url: "{{ route('get-test-for-preparation') }}",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: data,
                    success: function(response) {
                        console.log(response);
                    }
                });
                $('#chapterModal').modal('hide');
            });

        });
    </script>
@endsection
