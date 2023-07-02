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

<div class="container mt-5" id="test-content">
  <input type="hidden" id="test_id" value="{{ $test_id }}">
</div>

@endsection

@section('page2-script')
    <script>

      var timeLeft = 90; // Set the initial time in seconds
      var timerElement = $('#timer'); // Get the timer element

      // Function to update the timer display
      function updateTimer() {
            timerElement.text(timeLeft);
            console.log(timeLeft)
            if (timeLeft === 0) {
              submitAnswer();
              timerElement.text("Time's up!");
            } else {
              timeLeft--; // Decrease the time left by 1 second
            }
          }

          var timer = setInterval(updateTimer, 1000);
          function fetchTestRecords() {
              var test_id = $('#test_id').val();

              $.ajax({
                  url: '{{ route('attempt-test-ajax') }}',
                  method: 'POST',
                  data: {
                      test_id: test_id,
                      _token:'{{ csrf_token() }}'
                  },
                  success: function(response) {
                    $("#test-content").html(response);
                    timerElement = $('#timer');
                    timeLeft = parseFloat($('#timeLeft').val());
                  },
                  error: function(xhr, status, error) {
                      console.error(error);
                  }
              });
          }
        $(document).ready(function() {
          fetchTestRecords();
        });

        function submitAnswer(){
          const toastAnimationExample = document.querySelector('.toast-ex');
            var checkboxes = document.getElementsByName("checkboxGroup");
            var selectedValue = '';
            for (var i = 0; i < checkboxes.length; i++) {
              if (checkboxes[i].checked) {
                selectedValue = checkboxes[i].value;
                break;
              }
            }

            var test_id = $('#test_id').val();
            var test_child_id = $('#test_child_id').val();

              $.ajax({
                  url: '{{ route('store-test-answer') }}',
                  method: 'POST',
                  data: {
                      test_id: test_id,
                      test_child_id: test_child_id,
                      mcq_id: selectedValue,
                      _token:'{{ csrf_token() }}'
                  },
                  success: function(response) {
                    fetchTestRecords();
                    var status = response.status;
                        var message = response.message;
                        $('.toast-ex .fw-semibold').text(status);
                        $('.toast-ex .toast-body').text(message);

                        // Show the toast notification
                        selectedType = "text-success";
                        selectedAnimation = "animate__fade";
                        toastAnimationExample.classList.add(selectedAnimation);
                        toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                        toastAnimation = new bootstrap.Toast(toastAnimationExample);
                        toastAnimation.show();
                  },
                  error: function(xhr, status, error) {
                      console.error(error);
                  }
              });

          }


          function validate() {
            var checkboxes = document.getElementsByName("checkboxGroup");
            var checkedCount = 0;
            for (var i = 0; i < checkboxes.length; i++) {
              if (checkboxes[i].checked) {
                checkedCount++;
              }
            }
            if (checkedCount != 1) {
              alert("Please select one answer.");
              return false;
            }
            submitAnswer();
          }

        function handleCheckboxChange(checkbox) {
          var checkboxes = document.getElementsByName("checkboxGroup");

          for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
          }

          checkbox.checked = true;
          $("#save").removeAttr('disabled');
        }

    </script>
@endsection
