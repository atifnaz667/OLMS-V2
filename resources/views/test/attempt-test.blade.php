@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();

/* Display elements */
$customizerHidden = ($customizerHidden ?? '');

@endphp

{{-- @section('style') --}}
<style>
  .timer {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 70px; /* Set the desired height for the container */
    width: 70px; /* Set the desired height for the container */
    border-radius: 50%; /* Make the container round */
     /* Add a border to the container */
  }

  h3 {
    text-align: center;
    padding: 20px; /* Adjust the padding as needed */
    font-size: 36px; /* Adjust the font size as needed */
  }
</style>
{{-- @endsection --}}
@extends('layouts/commonMaster' )
@section('title', 'Attempt Test')
@section('layoutContent')

<!-- Content -->
@yield('content')

<div class="container mt-5">

  <!-- Sticky Actions -->
  <div class="row">
      <div class="col-12">
          <div class="card" >
              <div class="card-body" id="test-content">
                <div class="px-3">
                  <div class="row">
                    <div class="col-6">
                      <h6> Question No (5)</h6>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                      <h6>Total Questions (10)</h6>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                      <div class="timer">
                        <h3 class="p-3" id="timer">90</h3>
                      </div>
                    </div>
                    <button type="button" onclick="fetchTestRecords()" class="btn btn-primary"
                        id="filterButton">Save</button>
                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>

@endsection

@section('page2-script')
    <script>

        $(document).ready(function() {
          var timeLeft = 90; // Set the initial time in seconds
          var timerElement = $('#timer'); // Get the timer element

          // Function to update the timer display
          function updateTimer() {
            timerElement.text(timeLeft); // Update the timer display

            if (timeLeft === 0) {
              // Timer has reached 0, do something (e.g., display a message)
              timerElement.text("Time's up!");
              // Stop the timer
              clearInterval(timer);
            } else {
              timeLeft--; // Decrease the time left by 1 second
            }
          }

          // Call the updateTimer function every second
          var timer = setInterval(updateTimer, 1000);
        });

        function fetchTestRecords() {
            var test_id = $('#test_id').val();

            $.ajax({
                url: '{{ route('attempt-test-ajax') }}',
                method: 'POST',
                data: {
                    test_id: test_id,
                },
                success: function(response) {
                    if (response.status === 'success') {
                      $("#test-content").html(response);
                    } else {
                      console.log('Record not found')
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
@endsection
