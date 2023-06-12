@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<h4> {{ Auth::user()->role->role }} Home Page</h4>
<div class="row mt-5">
  <div class="col-sm-3">
    <a href="test/create" class="form-control btn btn-primary">Create Test</a>
  </div>
</div>
@endsection
