@extends('layouts/layoutMaster')
@section('title', 'Self Assesment')
@section('content')
    @if (Session::has('status'))
        <input type="hidden" name="" id="tostStatus" value="{{ Session::get('status') }}">
        <input type="hidden" name="" id="tostMessage" value="{{ Session::get('message') }}">
        <input type="hidden" name="" id="tostType"
            value="{{ Session::get('status') == 'Success' ? 'text-success' : 'text-warning' }}">

        <form id="redirectForm" action="{{ url('test/instructions') }}" method="POST">
            @csrf
            <input type="hidden" name="test_id" value="{{ Session::get('test_id') ?? '' }}">
        </form>
        {{ Session::forget('status') }}
        {{ Session::forget('message') }}
        {{ Session::forget('test_id') }}
    @endif
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Self Assesment/</span>
        Create
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Create Test</h5>
                <form id="createTestForm" method="post" action="assessment">
                    @csrf
                    <div class="row px-4">
                        <div class="col-sm-3 mb-3">
                            <label for="nameExLarge" class="form-label">Select Book</label>
                            <select name="book" id="book" class="form-select" onchange="getChapters(this.value)"
                                required>
                                <option value="">Select Book</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row px-4 mt-3 mb-5" id="chaptersRow">
                        <h6>Please select book</h6>
                    </div>
                    <div class="row px-4">
                        <div class="col-sm-3 mb-3">
                            <label for="nameExLarge" class="form-label">Total Questions</label>
                            <input type="input" class="form-control" name="totalQuestions"
                                placeholder="Enter Total Questions" required value="10">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label for="nameExLarge" class="form-label">Time For Each Question</label>
                            <select name="questionTime" id="questionTime" class="form-select" required>
                                {!! $timeOptions !!}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 px-4 mb-4">
                            <button type="submit" style="float:right" disabled id="submit"
                                class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        function getChapters(bookId) {
            $.ajax({
                url: '{{ route('self/assessment/chapters') }}',
                method: 'get',
                data: {
                    bookId: bookId,
                },
                success: function(response) {
                    $("#chaptersRow").html(response);
                }
            });
        }

        // Select All checkbox
        function selectCheckboxes(isChecked) {
            var isChecked = $('#select-all').prop('checked');
            if (isChecked == 1) {
                $("#submit").removeAttr('disabled');
            } else {
                $("#submit").attr('disabled', true);
            }
            $('.checkboxes').prop('checked', isChecked);
        }

        function selectCheckbox() {
            if ($('.checkboxes:checked').length === $('.checkboxes').length) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }

            if ($('.checkboxes:checked').length > 0) {
                $("#submit").removeAttr('disabled');
            } else {
                $("#submit").attr('disabled', true);
            }
        }

        $(document).ready(function() {
            var status = $("#tostStatus").val();
            if (status) {
                if (status == 'success') {
                    document.getElementById('redirectForm').submit();
                }
                const toastAnimationExample = document.querySelector('.toast-ex');
                var message = $("#tostMessage").val();
                $('.toast-ex .fw-semibold').text(status);
                $('.toast-ex .toast-body').text(message);

                // Show the toast notification
                selectedType = $("#tostType").val();
                selectedAnimation = "animate__fade";
                toastAnimationExample.classList.add(selectedAnimation);
                toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                toastAnimation = new bootstrap.Toast(toastAnimationExample);
                toastAnimation.show();
            }
        });
    </script>
@endsection
