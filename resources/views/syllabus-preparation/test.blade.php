@extends('layouts/layoutMaster')

@section('title', 'Selects and tags - Forms')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Forms /</span> Selects and tags
    </h4>

    <div class="row">

        <!-- Select2 -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Select2</h5>
                <div class="card-body">
                    <div class="row">
                        <!-- Multiple -->
                        <div class="col-md-6 mb-4">
                            <label for="select2Multiple" class="form-label">Multiple</label>
                            <select id="select2Multiple" class="select2 form-select" multiple>
                                <option value="AK">Alaska</option>
                                <option value="HI">Hawaii</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Select2 -->
    </div>
@endsection
