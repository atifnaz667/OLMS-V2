@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<h4> {{ Auth::user()->role->role }} Home Page</h4>

@endsection
