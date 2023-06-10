@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Sylabys Preparation')

@section('content')
<h4 class="fw-bold py-3 mb-2">
  <span class="text-muted fw-light">Syllabus/</span>
  Preparation
</h4>

<div class="row">
  @foreach($books as $book)
  <div class="col-sm-6">
    <div class="card mb-4">
      <div class="card-body">
        <div class="card-title header-elements">

          <h5 class="m-0 me-2"><i class="fa-solid fa-book fa-2xl" style="margin-right:1em"></i> {{ $book->name }}</h5>
          <div class="card-title-elements">
          </div>
          <div class="card-title-elements ms-auto">
            <select class="form-select form-select-sm w-auto">
              <option selected="">Option 1</option>
              <option>Option 2</option>
              <option>Option 3</option>
            </select>
            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light">Go</button>
          </div>
        </div>
        <p class="card-text">{{ Auth::user()->class->name }}</p>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
