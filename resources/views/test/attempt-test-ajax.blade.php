@php($currentTime = date("Y-m-d H:i:s") )
<input type="hidden" id="test_id" value="{{ $test->id }}">
<input type="hidden" id="test_child_id" value="{{ $childToAttempt->id }}">
<input type="hidden" id="timeLeft" value="{{ $test->question_time - (strtotime($currentTime) - strtotime($childToAttempt->viewed_at)) }}">
<div class="row">
  <div class="col-12">
      <div class="card" >
          <div class="card-body" id="test-content">
            <div class="px-3">
              <div class="row d-flex justify-content-center">

                <div class="col-1 ">
                  <h3 style="border: 1px solid #d4c0c0;" class="p-3" id="timer">{{ $test->question_time }}</h3>
                  <div >
                  </div>
                </div>
                <div class="col-12 ">
                </div>
              </div>
              <div class="row">
                <div class="col-12 my-3">

                    <div  class="" style="  width:100%">
                      <div class="p-3">

                        <h5 class="text-center"> Question {{ $attemptedCount + 1 }}/{{ $test->total_questions }}</h5>
                        <h5>{!! ucFirst($childToAttempt->question->description) !!}</h5>
                      </div>
                    </div>
                    @php($opt = 'a')
                    @foreach ($childToAttempt->question->mcqChoices as $mcqChoices)
                      <div style="border: 1px solid #d4c0c0; width:100%; border-radius:8px;" class="mt-2 ">
                        <div class="pt-4 p-3">
                          <input style="  zoom: 1.4; float:left" class="form-check-input checkbox" type="checkbox" name="checkboxGroup" value="{{ $mcqChoices->id }}" onclick="handleCheckboxChange(this)">
                          <h6 class="py-1 pt-1" style="margin-left:50px"> {{ $opt }}) &nbsp;&nbsp;&nbsp;{!! ucFirst($mcqChoices->choice) !!}</h6>
                        </div>
                      </div>
                    @php($opt++)
                    @endforeach
                  </div>
                  <div  id="btn-div">
                    <button type="button" onclick="submitAnswer()" class="btn btn-primary px-4" style="float:left" >Skip</button>
                    <button type="button" disabled onclick="validate()" class="btn btn-primary px-4" style="float:right" id="save">Save &amp; Next</button>
                  </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
