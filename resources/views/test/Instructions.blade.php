@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();

/* Display elements */
$customizerHidden = ($customizerHidden ?? '');

@endphp
@section('title', 'Test Instructions')

@extends('layouts/commonMaster' )

@section('layoutContent')

<!-- Content -->
@yield('content')
<!--/ Content -->
<div class="p-5 text-dark">
  <h4 class="text-danger">Please read the following instructions carefully!</h4>
  <p>
    1. Test will be based upon multiple choice questions (MCQs).
  </p>
  <p>
    2. Each question has a fixed time of {{ $test->question_time }} seconds. So you have to save the answer before {{ $test->question_time }} seconds. But due to unstable internet speed it is recomended that save your answer 20sec earlier. While attempting the test keep an eye on the remaining time.
  </p>
  <p>
    3. Attempting quiz is unidirectional. Once you moved forward to the next question you can't move back to the previous one. Therefore before moving to the next question make sure you have selected the best option.
  </p>
  <p>
    4. <span class="text-danger " style="font-weight:bold;"> DO NOT </span> Press the back button / backspace button otherwise you will loose that question.
  </p>
  <p>
    5. <span class="text-danger " style="font-weight:bold;"> DO NOT </span> Refresh the page unnecessarily.
  </p>
  <p>
    6. <span class="text-danger " style="font-weight:bold;"> DO NOT </span> Close the broswer tab or window.
  </p>
  <div class="d-flex justify-content-end"  >
    <form action="attempt" method="post">
        @csrf
        <input type="hidden" name="test_id" value="{{ $test->id }}">
        <button type="submit"  class="btn btn-primary mt-3 ">Start Test</button>
    </form>
  </div>
</div>
@endsection