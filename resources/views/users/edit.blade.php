@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')
@section('title', 'Edit Profile')


@section('content')

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Profile</h5>
                    <div class="action-btns">

                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            @csrf
                            <label class="form-label" for="update-name">Name</label>
                            <input type="text" value="{{ $user->name }}" class="form-control" required id="update-name"
                                placeholder="Name" name="update-name" aria-label="class" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="update-email">Email</label>
                            <input type="email" value="{{ $user->email }}" class="form-control" required
                                id="update-email" placeholder="Username" name="update-email" aria-label="class" />

                        </div>
                        <div class="col-md-3">
                            <div class="form-password-toggle">
                                <label class="form-label" for="multicol-password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="multicol-password" class="form-control"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="multicol-password2" />
                                    <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                            class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="update-user-name">User Name</label>
                            <input type="text" value="{{ $user->username }}" class="form-control" required
                                id="update-user-name" placeholder="Username" name="update-user-name" aria-label="class" />
                            <input type="hidden" value="{{ $user->id }}" id="userId" name="userId" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label" for="board_id">Board</label>
                            <select id="board_id" name="board_id" class="select2 form-select" data-allow-clear="true">
                                @foreach ($boards as $board)
                                    @if ($user->board_id == $board->id)
                                        <option value="{{ $board->id }}" selected>{{ $board->name }}</option>
                                    @else
                                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endif
                                @endforeach


                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="class_id">Class</label>
                            <select id="class_id" name="class_id" class="select2 form-select" data-allow-clear="true">
                                @foreach ($classes as $class)
                                    @if ($user->class_id == $class->id)
                                        <option value="{{ $class->id }}" selected>{{ $class->name }}</option>
                                    @else
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button type="button" onclick="updateUser()" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page2-script')

    <script>
        const toastAnimationExample = document.querySelector('.toast-ex');


        function updateUser() {
            // Get the form data

            var _token = $('input[name="_token"]').val();
            var id = $('#userId').val();
            var name = $('#update-name').val();
            var email = $('#update-email').val();
            var username = $('#update-user-name').val();
            var password = $('#update-password').val();
            var board_id = $('#board_id').val();
            var class_id = $('#class_id').val();
            var formData = {
                _token: _token,
                name: name,
                email: email,
                password: password,
                board_id: board_id,
                class_id: class_id,
                username: username
            };
            $.ajax({
                url: "{{ route('user.update', '') }}" + "/" + id,
                type: 'PUT',
                data: formData,
                success: function(response) {

                    var status = "Profile Updated Successfully";
                    var message = response.message;
                    $('.toast-ex .fw-semibold').text(status);
                    $('.toast-ex .toast-body').text(message);
                    selectedType = "text-success";
                    selectedAnimation = "animate__fade";
                    toastAnimationExample.classList.add(selectedAnimation);
                    toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                    toastAnimation = new bootstrap.Toast(toastAnimationExample);
                    toastAnimation.show();

                },
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    var status = response.status;
                    var message = response.message;
                    $('.toast-ex .fw-semibold').text(status);
                    $('.toast-ex .toast-body').text(message);
                    selectedType = "text-warning";
                    selectedAnimation = "animate__fade";
                    toastAnimationExample.classList.add(selectedAnimation);
                    toastAnimationExample.querySelector('.ti').classList.add(selectedType);
                    toastAnimation = new bootstrap.Toast(toastAnimationExample);
                    toastAnimation.show();
                }
            });
        }
    </script>

@endsection
