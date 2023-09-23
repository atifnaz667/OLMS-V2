@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')
@section('title', 'Calculator')

@section('content')
    <div style="overflow: hidden; padding-top: 56.25%; position: relative;">
        <iframe src="https://www.desmos.com/scientific"
            style="border: 0; position: absolute; top: 0; left: 0; width: 100%; height: 400%;" name="myiFrame" scrolling="no"
            frameborder="0" allowfullscreen></iframe>
    </div>
@endsection
