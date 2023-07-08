@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection
@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection
@section('title', 'Home')

@section('content')
    <h4> {{ Auth::user()->role->role }} Home Page</h4>

    <div class="row mt-5">
        <div class="col-sm-3">
            <a href="syllabus-preparation" class="form-control btn btn-primary">Full Sylabus Preraration</a>
        </div>
        <div class="col-sm-3">
            <a href="self/assessment" class="form-control btn btn-primary">Self Assessment</a>
        </div>
        <div class="col-sm-3">
            <a href="test/list" class="form-control btn btn-primary">Attempt Test</a>
        </div>
        <div class="col-sm-3">
            <a href="suggestion/create" class="form-control btn btn-primary">Complaints & Suggestions</a>
        </div>
    </div>




    <div class="row mt-4">

        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Average Test Percentage</h5>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label" for="board_id">Book</label>
                        <div class="col-sm-8">
                            <select required id="board_id" name="board_id" class="select2 form-select"
                                data-allow-clear="true">
                                {{-- <option value=""></option> --}}
                                @foreach ($boards as $board)
                                    <option value="{{ $board->id }}">{{ $board->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                            <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                                <h1 class="mb-0">164</h1>
                                <p class="mb-0">Total Tests</p>
                            </div>
                            <ul class="p-0 m-0">
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                    <div class="badge rounded bg-label-info p-1"><i class="ti ti-circle-check ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Completed Test</h6>
                                        <small class="text-muted">28</small>
                                    </div>
                                </li>
                                <li class="d-flex gap-3 align-items-center pb-1">
                                    <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i></div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Pending Tests</h6>
                                        <small class="text-muted">1</small>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                            <div id="supportTracker"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
