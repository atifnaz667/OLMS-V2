<!DOCTYPE html>
@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Multiple Choice Questions')

@section('content')
    <style>
        .true-option {
            border: 2px solid #7367F0 !important;
            font-weight: bold;
            color: #7367F0
        }
    </style>
    <h4 class="fw-bold py-3 mb-2">

    </h4>
    <div class="row">
        <div class="col-6">
            <h5>Subject: {{ $book_name }}
            </h5>
        </div>
        <div class="col-6 text-end">
            <h5>Total Marks: {{ $totalQuestions }}x1={{ $totalQuestions * 1 }}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h5>Class: {{ Auth::user()->class->name }}
            </h5>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col text-center">
            <h2 class="fw-bold">Multiple Choice Questions</h2>
        </div>
    </div>

    @php
        $questionIndex = 1;
        $choiceNames = ['A. ', 'B. ', 'C. ', 'D. '];
    @endphp


    @foreach ($questions as $question)
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Q.{{ $questionIndex }} {!! $question->description !!}</h5>
                        <div class="row">
                            <div class="col-sm-6 col-12 ">
                                <ul class="list-group">
                                    @php
                                        $answer = '';
                                        $ansKey = '';
                                    @endphp
                                    @foreach ($question->mcqChoices as $key => $choice)
                                        @if ($key % 2 == 0)
                                            @if ($choice->is_true)
                                                @php
                                                    $answer = $choice->choice;
                                                    $ansKey = $choiceNames[$key / 2];
                                                @endphp
                                            @endif
                                            <li class=" mt-2 list-group-item " style="border-top:1px solid #dbdade; ">
                                                <label class="form-check-label">
                                                    <span class="option-label"><strong>{{ $choiceNames[$key / 2] }}
                                                            &nbsp;&nbsp;</strong></span> {!! $choice->choice !!}
                                                </label>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-sm-6 col-12 ">
                                <ul class="list-group">
                                    @foreach ($question->mcqChoices as $key => $choice)
                                        @if ($key % 2 == 1)
                                            @if ($choice->is_true)
                                                @php
                                                    $answer = $choice->choice;
                                                    $ansKey = $choiceNames[($key - 1) / 2 + 2];
                                                @endphp
                                            @endif
                                            <li class="mt-2 list-group-item " style="border-top:1px solid #dbdade;">
                                                <label class="form-check-label">
                                                    <span class="option-label"><strong>{{ $choiceNames[($key - 1) / 2 + 2] }}
                                                            &nbsp;&nbsp;</strong></span>
                                                    {!! $choice->choice !!}
                                                </label>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @if ($test_type == 'Objective')
                            <button class="btn btn-primary mt-3 mb-3" onclick="toggleAnswer({{ $questionIndex }})"
                                id="btn{{ $questionIndex }}">Show Answer</button>
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="card-text answer-text p-3 d-none" id="answer{{ $questionIndex }}"
                                        style="border:1px solid #7367f0; border-radius:5px;">
                                        <b>{{ $ansKey }}</b> &nbsp; &nbsp; {!! $answer !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @php
            $questionIndex++;
        @endphp
    @endforeach
@endsection

@section('page2-script')
    <script>
        function toggleAnswer(questionIndex) {
            var answerText = document.getElementById('answer' + questionIndex);
            answerText.classList.toggle('d-none');
            var btnText = $("#btn" + questionIndex).text();
            if (btnText == 'Show Answer') {
                $("#btn" + questionIndex).text('Hide Answer')
            } else {
                $("#btn" + questionIndex).text('Show Answer')
            }
        }
    </script>
@endsection
