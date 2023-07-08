@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<div class="row mt-5">
  <div class="col-sm-3">
    <a href="test/create" class="form-control btn btn-primary">Create Test</a>
  </div>
  <div class="col-sm-3">
    <a href="test/list" class="form-control btn btn-primary">Tests List</a>
  </div>
  <div class="col-sm-3">
    <a href="suggestion/create" class="form-control btn btn-primary">Complaints & Suggestions</a>
  </div>
</div>
@endsection
