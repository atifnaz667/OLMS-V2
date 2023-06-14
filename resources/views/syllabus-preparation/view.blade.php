<!DOCTYPE html>
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

    <div class="row">
        <div class="col-6">
            <h5>Subject: {{ $book_name }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>Class:
                    {{ Auth::user()->class->name }}</span>
            </h5>
        </div>
        <div class="col-6 text-end">
            <h5>Total Marks: {{ $totalQuestions }}x1={{ $totalQuestions * 1 }}</h5>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col text-center">
            <h2 class="fw-bold">{{ $test_type }} Paper</h2>
        </div>
    </div>

    @php
        $questionIndex = 1;
        $choiceNames = ['a', 'b', 'c', 'd'];
    @endphp

    @foreach ($questions as $question)
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Q{{ $questionIndex }}. {{ $question->description }}</h5>
                        <div class="row">
                            <div class="col-6">
                                <ul class="list-group">
                                    @foreach ($question->mcqChoices as $key => $choice)
                                        @if ($key % 2 == 0)
                                            <li class="list-group-item">
                                                <label class="form-check-label">
                                                    <span class="option-label">{{ $choiceNames[$key / 2] }})</span>
                                                    {{ $choice->choice }}
                                                </label>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="list-group">
                                    @foreach ($question->mcqChoices as $key => $choice)
                                        @if ($key % 2 == 1)
                                            <li class="list-group-item">
                                                <label class="form-check-label">
                                                    <span
                                                        class="option-label">{{ $choiceNames[($key - 1) / 2 + 2] }})</span>
                                                    {{ $choice->choice }}
                                                </label>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @if ($test_type !== 'Objective')
                            <button class="btn btn-primary mt-3"
                                onclick="toggleAnswer({{ $questionIndex }})">Answer</button>
                            <div class="card-text answer-text d-none mb-5" id="answer{{ $questionIndex }}">
<br>
                                {!! $question->answer->answer !!}
                                @if ($question->answer->image)
                                    <img src="data:image/png;base64,{{ $question->answer->image }}" alt="Answer Image">
                                @endif
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
        }
    </script>
@endsection
