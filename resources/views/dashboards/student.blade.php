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
<script src="{{asset('assets/js/dashboards-crm.js')}}"></script>

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

     <!-- Earning Reports Tabs-->
  <div class="col-12 mb-4 mt-5">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0">Result Reports</h5>
          <small class="text-muted">All Subjects Result Overview</small>
        </div>

      </div>
      <div class="card-body">

        <div class="tab-content p-0 ms-0 ms-sm-2">
          <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
            <div id="earningReportsTabsOrders"></div>
          </div>
          <div class="tab-pane fade" id="navs-sales-id" role="tabpanel">
            <div id="earningReportsTabsSales"></div>
          </div>
          <div class="tab-pane fade" id="navs-profit-id" role="tabpanel">
            <div id="earningReportsTabsProfit"></div>
          </div>
          <div class="tab-pane fade" id="navs-income-id" role="tabpanel">
            <div id="earningReportsTabsIncome"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
