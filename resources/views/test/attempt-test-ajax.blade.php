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
                  <h6> Question No ({{ $attemptedCount + 1 }})</h6>
                </div>
                <div class="col-6 d-flex justify-content-end">
                  <h6>Total Questions ({{ $test->total_questions }})</h6>
                </div>
                <div class="col-12 d-flex justify-content-end">
                  <div >
                    <h3 class="p-3" id="timer">{{ $test->question_time }}</h3>
                  </div>
                </div>
                <div class="col-12 ">
                  <h6 class="ml-2">Question:</h6>
                  <h6>{!! ucFirst($childToAttempt->question->description) !!}</h6>
                </div>
                <div class="col-12 my-3">
                  <table class="table table-striped">
                    @foreach ($childToAttempt->question->mcqChoices as $mcqChoices)
                        <tr>
                          <td style="width:5%;  zoom: 1.5; "> <input class="form-check-input checkbox" type="checkbox" name="checkboxGroup" value="{{ $mcqChoices->id }}" onclick="handleCheckboxChange(this)" ></td>
                          <td style="vertical-align: middle;">
                            <h6>{{ ucFirst($mcqChoices->choice) }}</h6>
                          </td>
                        </tr>
                        @endforeach
                    </table>
                  </div>
                <button type="button" onclick="validate()" class="btn btn-primary "
                    id="save" disabled>Save</button>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
