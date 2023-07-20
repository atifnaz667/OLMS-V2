@php($currentTime = date("Y-m-d H:i:s") )
<input type="hidden" id="test_id" value="{{ $test->id }}">
<input type="hidden" id="test_child_id" value="{{ $childToAttempt->id }}">
<input type="hidden" id="timeLeft" value="{{ $test->question_time - (strtotime($currentTime) - strtotime($childToAttempt->viewed_at)) }}">
<div class="row">
  <div class="col-12">
      <div class="card" >
          <div class="card-body" id="test-content">
            <div class="px-3">
              <div class="row">
                <div class="col-6">

                </div>
                <div class="col-6 d-flex justify-content-end">

                </div>
                <div class="col-6 d-flex justify-content-start">
                  <h6 style="border: 1px solid #d4c0c0;" class="p-4">Total Questions ({{ $test->total_questions }})</h6>
                </div>
                <div class="col-6 d-flex justify-content-end">
                  <div >
                    <h3 style="border: 1px solid #d4c0c0;" class="p-3" id="timer">{{ $test->question_time }}</h3>
                  </div>
                </div>
                <div class="col-12 ">
                </div>
                <div class="col-12 my-3">
                  <table class="" style="width:100%">
                    <tr style="border: 1px solid black;">
                      <td colspan="2" class="py-4">
                        <h6> Question No ({{ $attemptedCount + 1 }})</h6>
                        <h6>{!! ucFirst($childToAttempt->question->description) !!}</h6>
                      </td>
                    </tr>

                    @foreach ($childToAttempt->question->mcqChoices as $mcqChoices)
                        <tr style="border: 1px solid black;">
                          <td style="width:5%;  zoom: 1.5; border: 1px solid black;"> <input class="form-check-input checkbox" type="checkbox" name="checkboxGroup" value="{{ $mcqChoices->id }}" onclick="handleCheckboxChange(this)" ></td>
                          <td style="vertical-align: middle; border: 1px solid black;">
                            <h6>{{ ucFirst($mcqChoices->choice) }}</h6>
                          </td>
                        </tr>
                        @endforeach
                    </table>
                  </div>
                  <div class="px-3" id="btn-div">
                    <button type="button" disabled onclick="validate()" class="btn btn-primary px-4" style="float:right" id="save">Save &amp; Next</button>
                  </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
