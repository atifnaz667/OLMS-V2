@php($currentTime = date("Y-m-d H:i:s") )
<input type="hidden" id="test_id" value="{{ $test->id }}">
<input type="hidden" id="test_child_id" value="{{ $childToAttempt->id }}">
<input type="hidden" id="timeLeft" value="{{ $test->question_time - (strtotime($currentTime) - strtotime($childToAttempt->viewed_at)) }}">
<div class="row">
  <div class="col-12">
      <div class="card" >
          <div class="card-body" id="test-content">
            <div class="px-3">
              <div class="row px-2">
                <div class="col-6">

                </div>
                <div class="col-6 d-flex justify-content-end">

                </div>
                <div class="col-8 d-flex justify-content-start">
                  <h6 style="border: 1px solid #d4c0c0;" class="p-4">Total Questions ({{ $test->total_questions }})</h6>
                </div>
                <div class="col-4 d-flex justify-content-end">
                  <div >
                    <h3 style="border: 1px solid #d4c0c0;" class="p-3" id="timer">{{ $test->question_time }}</h3>
                  </div>
                </div>
                <div class="col-12 ">
                </div>
              </div>
              <div class="row">
                <div class="col-12 my-3">
                  <table class="" style="width:100%; all: unset; border-spacing:10px">
                    <tr style="border: 1px solid #d4c0c0;">
                      <td colspan="2" class="py-4" style=" border: 1px solid #d4c0c0;">
                        <h6> Question No ({{ $attemptedCount + 1 }})</h6>
                        <h6>{!! ucFirst($childToAttempt->question->description) !!}</h6>
                      </td>
                    </tr>

                    @foreach ($childToAttempt->question->mcqChoices as $mcqChoices)
                        <tr style="border: 1px solid #d4c0c0;">
                          <td style="width:5%;  zoom: 1.5; border: 1px solid #d4c0c0;"> <input class="form-check-input checkbox" type="checkbox" name="checkboxGroup" value="{{ $mcqChoices->id }}" onclick="handleCheckboxChange(this)" ></td>
                          <td style="vertical-align: middle; border: 1px solid #d4c0c0;">
                            <h6 class="mt-3">{{ ucFirst($mcqChoices->choice) }}</h6>
                          </td>
                        </tr>
                        @endforeach
                    </table>
                  </div>
                  <div class="px-3" id="btn-div">
                    <button type="button" onclick="submitAnswer()" class="btn btn-primary px-4" style="float:left" >Skip</button>
                    <button type="button" disabled onclick="validate()" class="btn btn-primary px-4" style="float:right" id="save">Save &amp; Next</button>
                  </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
