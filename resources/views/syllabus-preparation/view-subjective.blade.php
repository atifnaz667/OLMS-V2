<!DOCTYPE html>
@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Syllabus Preparation')

@section('content')
    <style>
        .true-option {
            border: 2px solid #7367F0 !important;
            font-weight: bold;
            color: #7367F0
        }
    </style>
    <h4 class="fw-bold py-3 mb-3">
        <span class="text-muted fw-light">Syllabus/</span>
        Preparation
    </h4>
  <div class="row">
    <div class="col-6">
          <h5>Subject: {{ $book_name }}</h5>
      </div>
      <div class="col-6 text-end">
          <h5><span>Class: {{ Auth::user()->class->name }}</span></h5>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col text-center">
        <h2 class="fw-bold">{{ $test_type }} Paper</h2>
      </div>
    </div>

    @php
        $questionIndex = 1;
        $choiceNames = ['a. ', 'b. ', 'c. ', 'd. '];
    @endphp

    <div class="card mb-3">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#form-tabs-short" role="tab"
                        aria-selected="true">Short Question</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link " data-bs-toggle="tab" data-bs-target="#form-tabs-long" role="tab"
                        aria-selected="false">Long Question</button>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            <!-- Short Question -->
            <div class="tab-pane fade active show" id="form-tabs-short" role="tabpanel">
                @foreach ($shortQuestions as $question)
                    <div class="row mt-4">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Q{{ $questionIndex }}. {!! $question->description !!}</h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-group">
                                                @foreach ($question->mcqChoices as $key => $choice)
                                                    @if ($key % 2 == 0)
                                                        <li
                                                            class="list-group-item @if ($choice->is_true) true-option @endif">
                                                            <label class="form-check-label">
                                                                <span
                                                                    class="option-label">{{ $choiceNames[$key / 2] }}</span>
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
                                                        <li
                                                            class="list-group-item @if ($choice->is_true) true-option @endif">
                                                            <label class="form-check-label">
                                                                <span
                                                                    class="option-label">{{ $choiceNames[($key - 1) / 2 + 2] }}</span>
                                                                {{ $choice->choice }}
                                                            </label>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>


                                        <button class="btn btn-primary mt-3 mb-3"
                                            onclick="toggleAnswer({{ $questionIndex }})" id="btn{{ $questionIndex }}">Show Answer</button>
                                        <div class="card-text answer-text d-none" id="answer{{ $questionIndex }}">

                                            {!! $question->answer->answer !!}
                                            @if ($question->answer->image)
                                                <img src="data:image/png;base64,{{ $question->answer->image }}"
                                                    alt="Answer Image">
                                            @endif
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $questionIndex++;
                    @endphp
                @endforeach
            </div>
            <!-- Long Question -->
            <div class="tab-pane fade" id="form-tabs-long" role="tabpanel">
              @php
                  $i = 0;
              @endphp
            @foreach ($questions as $question)
              @php
                  $i++;
              @endphp
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Q{{ $i }}. {!! $question->description !!}</h5>
                            <div class="row">
                                <div class="col-6">
                                    <ul class="list-group">
                                        @foreach ($question->mcqChoices as $key => $choice)
                                            @if ($key % 2 == 0)
                                                <li
                                                    class="list-group-item @if ($choice->is_true) true-option @endif">
                                                    <label class="form-check-label">
                                                        <span class="option-label">{{ $choiceNames[$key / 2] }}</span>
                                                        {{ $choice->choice }}
                                                    </label>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>


                                <button class="btn btn-primary mt-3 mb-3"
                                    onclick="toggleAnswer({{ $questionIndex }})" id="btn{{ $questionIndex }}">Show Answer</button>
                                <div class="card-text answer-text d-none" id="answer{{ $questionIndex }}">

                                    {!! $question->answer->answer !!}
                                    @if ($question->answer->image)
                                        <img src="data:image/png;base64,{{ $question->answer->image }}" alt="Answer Image">
                                    @endif
                                </div>

                        </div>
                    </div>
                </div>
            </div>
            @php
                $questionIndex++;
            @endphp
        @endforeach
            </div>

        </div>
    </div>


@endsection

@section('page2-script')
    <script>
        function toggleAnswer(questionIndex) {
            var answerText = document.getElementById('answer' + questionIndex);
            answerText.classList.toggle('d-none');
            let button = $('#btn' + questionIndex).text();
            if (button == 'Show Answer') {
              $('#btn' + questionIndex).text('Hide Answer');
            }else{
              $('#btn' + questionIndex).text('Show Answer');
            }
        }
    </script>
@endsection
