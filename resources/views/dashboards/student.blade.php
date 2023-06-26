@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<h4> {{ Auth::user()->role->role }} Home Page</h4>

<div class="row mt-5">
  <div class="col-sm-3">
    <a href="syllabus-preparation" class="form-control btn btn-primary">Full Sylabus Preraration</a>
  </div>
  <div class="col-sm-3">
    <a href="syllabus-preparation" class="form-control btn btn-primary">Student Self Tests</a>
  </div>
  <div class="col-sm-3">
    <a href="test/list" class="form-control btn btn-primary">Attempt Daily Tests</a>
  </div>
  <div class="col-sm-3">
    <a href="syllabus-preparation" class="form-control btn btn-primary">Results Reports</a>
  </div>
</div>
@endsection
