<!DOCTYPE html>
@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Keynotes')

@section('content')
    <style>
        .true-option {
            border: 2px solid #7367F0 !important;
            font-weight: bold;
            color: #7367F0
        }
    </style>


    <div class="row mt-4">
        <div class="col text-center">
            <h2 class="fw-bold">Keynotes {{ $book }}</h2>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            <span>Select topics of any unit to view Keynotes</span>
        </div>
        <div class="card-body">
            @foreach ($chapters as $chapter)
                <h5 class="card-title mt-3">Unit: {{ $chapter->name }}</h5>

                @foreach ($questionTypes as $questionType)
                    <div class="col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input questionType-checkbox" type="checkbox"
                                id="{{ $chapter->id }}_questionType_{{ $questionType->type }}"
                                data-questiontype-id="{{ $questionType->type }}" data-chapter-id="{{ $chapter->id }}">
                            <h6 class="form-check-h6" for="{{ $chapter->id }}_questionType_{{ $questionType->type }}">
                                {{ $questionType->type }} </h6>
                        </div>
                    </div>
                    <div id="{{ $chapter->id }}_notes_container_{{ $questionType->type }}" class="notes-container"></div>
                @endforeach
            @endforeach
        </div>
    </div>


@endsection

@section('page2-script')
    <script>
        $(document).ready(function() {
            $('.questionType-checkbox').on('change', function() {
                var questionType = $(this).data('questiontype-id');
                var chapter = $(this).data('chapter-id');
                $('.questionType-checkbox').not(this).prop('checked', false);
                if ($(this).is(':checked')) {
                    $('.notes-container').empty();
                    $.ajax({
                        url: "{{ url('load-notes', '') }}" + "/" + chapter + "/" + questionType,
                        method: 'GET',
                        success: function(response) {
                            $('#' + chapter + '_notes_container_' + questionType).html(
                                response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    $('#' + chapter + '_notes_container_' + questionType).empty();
                }
            });
        });
    </script>
@endsection
