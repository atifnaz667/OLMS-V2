@extends('layouts/layoutMaster')
@section('title', 'Test Result')
<style>
  .true-option {
      border: 2px solid green !important;
  }
  .false-option {
      border: 2px solid red !important;
  }
</style>
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Test/</span>
        Result
    </h4>

    <div class="row">
      <div class="col-12">
        <div class="card mb-4" style="border-top: 3px solid #7367f0">
          <div class="p-4">
            <div class="row">
              <div class="col-6">
                <h6>
                  Book Name : {{ $test->book->name }}
                </h6>
              </div>
              <div class="col-6">
                <h6>
                  Attempted At : {{ $test->attempted_at }}
                </h6>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <h6>
                  Total Marks : {{ $test->total_questions }}
                </h6>
              </div>
              <div class="col-6">
                <h6>
                  Obtained Marks : {{ count($test->obtainedMarks) }}
                </h6>
              </div>
            </div>
          </div>
        </div>
      </div>
        @foreach ($test->testChildren as $key => $child)
            <div class="col-12">
                <div class="card mb-4">
                    <div class="p-4">
                      <div class="row">
                        <div class="col-11">
                          <h5>{{ $key+1 }}) {!! $child->question->description !!}</h5>
                        </div>
                        <div class="col-1 ">
                          @if ($child->is_correct == 1)
                            <h5 class="mt-3 text-center" style="color:green;">
                              <i class="fa-solid fa-check fa-xl"></i>
                            </h5>
                          @else
                            <h5 class="mt-3 text-center" style="color:red;">
                              <i class="fa-solid fa-xmark fa-xl"></i>
                            </h5>
                          @endif
                        </div>
                      </div>
                        <ul class="list-group
                        @if ($child->is_correct == 1)
                          true-option
                        @else
                          false-option
                        @endif
                        ">
                            <li class="list-group-item ">
                                <label class="form-check-label">
                                    <b> Your Answer :  </b>
                                    {{ $child->selectedAnswer->choice ?? 'Not Attempted' }}
                                </label>
                            </li>
                            <li class="list-group-item ">
                              <label class="form-check-label">
                                @php
                                  $correctAnswer = $child->question->mcqChoices->where('is_true',1)->first();
                                @endphp
                                  <b> Correct Answer :  </b>
                                  {{ $correctAnswer->choice }}
                              </label>
                          </li>
                            <li class="list-group-item ">
                              <label class="form-check-label">
                                  <b> Reason :  </b>
                                  {{ $correctAnswer->reason }}
                              </label>
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
