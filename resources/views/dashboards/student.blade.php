@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')

<div class="row mt-5">
  <div class="col-sm-3">
    <a href="syllabus-preparation" class="form-control btn btn-primary">Full Sylabus Preraration</a>
  </div>
  <div class="col-sm-3">
    <a href="self/assessment" class="form-control btn btn-primary">Self Assessment</a>
  </div>
  <div class="col-sm-3">
    <a href="test/list" class="form-control btn btn-primary">Attempt Daily Tests</a>
  </div>
  <div class="col-sm-3">
    <a href="syllabus-preparation" class="form-control btn btn-primary">Results Reports</a>
  </div>
</div>
@endsection
