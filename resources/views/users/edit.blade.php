@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')
@section('title', 'Edit Profile')


@section('content')

<div class="page-wrapper">



    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Profile</h5>
                    <div class="action-btns">

                    </div>
                </div>
                <div class="card-body">
                <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="p-4 border radius-15">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <!-- <img src="{{ asset('assets/img/avatars/1112.png') }}" alt="Admin"
                                                class="rounded-circle p-1 " width="110"> -->
                                                @if (Auth::user()->image == null)
                                                    <img src="{{ asset('assets/img/avatars/1112.png') }}"alt="Admin"
                                                class="rounded-circle p-1 " width="110">
                                                @else
                                                    <img src="files/userImages/{{ Auth::user()->image }}" alt="Admin"
                                                class="rounded-circle p-1 " width="110">
                                                @endif
                                            <div class="mt-3">
                                                <h4>{{ $user->username }}</h4>


                                                <b>
                                                    <p class="text-secondary mb-1">Joined At</p>
                                                </b>
                                                <p class="text-muted font-size-sm">
                                                    {{ date('d-F-Y h:i:s', strtotime($user->created_at)) }}</p>
                                                    <b>
                                                    <p class="text-secondary mb-1">Expire At</p>
                                                    </b>
                                                    <?php
                                                    if($user->card->expiry_date == "One Year"){
                                                        $createdDate = new DateTime($user->created_at);
                                                        $expire_date = $createdDate->add(new DateInterval('P1Y'))->format('Y-m-d H:i:s');
                                                    }elseif($user->card->expiry_date == "Six Months"){
                                                        $createdDate = new DateTime($user->created_at);
                                                        $expire_date = $createdDate->add(new DateInterval('P6M'))->format('Y-m-d H:i:s');
                                                    }
                                                    ?>
                                                    <p class="text-muted font-size-sm">
                                                    {{ date('d-F-Y h:i:s', strtotime($expire_date)) }}</p>
                                                </b>

                                            </div>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                          <li
                                                class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <b class="mb-0">Username</b>
                                                <span class="text-secondary">{{ $user->username }} </span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <b class="mb-0">Email</b>
                                                <span class="text-secondary">{{ $user->email }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <b class="mb-0">Phone No</b>
                                                <span class="text-secondary">{{ $user->phone_no }} </span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <b class="mb-0">Status</b>
                                                <span class="text-secondary"><?php if ($user->status == 'active') { ?>
                                                <td><span class="badge bg-label-success"
                                                        text-capitalized="">{{ $user->status }}</span></td>
                                                <?php } elseif ($user->status == 'pending') { ?>
                                                <td><span class="badge bg-label-warning"
                                                        text-capitalized="">{{ $user->status }}</span></td>
                                                <?php  } elseif ($user->status == 'deactive') { ?>
                                                <td><span class="badge bg-label-secondary"
                                                        text-capitalized="">{{ $user->status }}</span></td>
                                                <?php  } ?></span>
                                            </li>


                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <b class="mb-0">Board</b>
                                                <span class="text-secondary">{{ $user->board->name }} </span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <b class="mb-0">Class</b>
                                                <span class="text-secondary">{{ $user->class->name }} </span>
                                            </li>
                                        </ul>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="border p-4 radius-15">

                                        <div class="form-body">
                                            <form class="row g-3" method="POST" action="">

                                                @csrf
                                                <div class="col-md-12">
                                                  <label class="form-label" for="update-user-name">User Name</label>
                                                  <input type="text" value="{{ $user->username }}" class="form-control" required
                                                      id="update-user-name" placeholder="Username" name="update-user-name" aria-label="class" / disabled>
                                                  <input type="hidden" value="{{ $user->id }}" id="userId" name="userId" />
                                              </div>

                                              <div class="col-md-12">
                                                <label class="form-label" for="update-email">Email</label>
                                                <input type="email" value="{{ $user->email }}" class="form-control" required
                                                   id="update-email" placeholder="Username" name="update-email" aria-label="class" / disabled>
                                              </div>
                                                <div class="col-md--12">
                                                    <label for="name" class="form-label">Name *</label>
                                                    <input type="text" value="{{ $user->name }}" class="form-control" required id="update-name"
                                                    placeholder="Name" name="update-name" aria-label="class" />
                                                </div>
                                                <div class="col-md--12">
                                                    <label for="phone_no" class="form-label">Phone No *</label>
                                                    <input type="text" value="{{ $user->phone_no }}" class="form-control" required id="update-phone_no"
                                                    placeholder="Phone Number" name="update-phone_no" aria-label="class" />
                                                </div>

                                                 <div class="col-md-12">
                                                  <div class="form-password-toggle">
                                                  <label class="form-label" for="multicol-password">Old Password</label>
                                                  <div class="input-group input-group-merge">
                                                      <input type="password" id="old-password" class="form-control"
                                                          placeholder="Enter old password"
                                                          aria-describedby="multicol-password2" />
                                                      <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                                              class="ti ti-eye-off"></i></span>
                                                  </div>
                                              </div>
                                              </div>
                                                 <div class="col-md-12">
                                                  <div class="form-password-toggle">
                                                  <label class="form-label" for="multicol-password">New Password</label>
                                                  <div class="input-group input-group-merge">
                                                      <input type="password" id="multicol-password" class="form-control"
                                                          placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                          aria-describedby="multicol-password2" />
                                                      <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                                              class="ti ti-eye-off"></i></span>
                                                  </div>
                                              </div>
                                              </div>
                                              <div class="mb-3">
                                                    <label for="user-image" class="form-label">Image</label>
                                                    <input class="form-control" name="user-image" type="file" id="user-image">
                                              </div>
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                      <button type="button" onclick="updateUser()" class="btn btn-primary">Update</button>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        // var _token = "{{ csrf_token() }}";
        function updateUser() {
            // Get the form data

            //var _token = $('input[name="_token"]').val();
            //var name = $('#update-name').val();
            //var phone_no = $('#update-phone_no').val();
            //var password = $('#multicol-password').val();
            //var oldPassword = $('#old-password').val();
            //var formData = {
            //    _token: _token,
             //   name: name,
              //  phone_no: phone_no,
              //  oldPassword: oldPassword,
              //  password: password,
           // };
           var _token = $('input[name="_token"]').val();
            var name = $('#update-name').val();
            var phone_no = $('#update-phone_no').val();
            var oldPassword = $('#old-password').val();
            var password = $('#multicol-password').val();
         
            var formData = new FormData();
            formData.append('_token', _token);
            formData.append('name', name);
            formData.append('phone_no', phone_no);
            formData.append('oldPassword', oldPassword);
            formData.append('password', password);
            formData.append('user-image', $('#user-image')[0].files[0]);

            $.ajax({
                url: "{{ route('update-user-info') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                 headers: {
                        'X-CSRF-TOKEN': _token
                    },
                success: function(response) {
                  $('#multicol-password').val('');
                  $('#old-password').val('');
                    var status = response.status;
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
