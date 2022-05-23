@extends('default.app')

@section('title', __('Datatables CRUD - Edit Data'))

@section('content')
<div class="container shadow-sm bg-body rounded py-4">
  <header class="pb-3 mb-4 border-bottom">
    <a href="{{ route('user.index') }}" class="d-flex align-items-center text-dark text-decoration-none">
      <img src="{{ asset('logo.svg') }}" class="me-2 fill-blue" width="50" height="50" alt="{{ config('app.name', 'Laravel 9') }} | @yield('title')">
      <span class="fs-4">@yield('title')</span>
    </a>
  </header>

  <div class="row mb-3">
    <div class="col">
      <form method="POST" action="{{ route('user.update', $user->uuid) }}" id="form-edit" class="needs-validation" accept-charset="UTF-8" enctype="multipart/form-data" novalidate>
        @method('PUT')
        @csrf

        <div class="row mb-3">
          <label for="name" class="col-sm-2 col-form-label text-capitalize">{{ __('name') }}</label>
          <div class="col-sm-10">
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" placeholder="{{ __('Please Enter Name') }}" required>
            <div class="invalid-feedback" id="nameError">
              ERROR
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <label for="username" class="col-sm-2 col-form-label text-capitalize">{{ __('username') }}</label>
          <div class="col-sm-10">
            <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" placeholder="{{ __('Please Enter Username') }}" required>
            <div class="invalid-feedback" id="usernameError">
              ERROR
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <label for="phone" class="col-sm-2 col-form-label text-capitalize">{{ __('phone') }}</label>
          <div class="col-sm-10">
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}" placeholder="{{ __('Please Enter Phone') }}" required>
            <div class="invalid-feedback" id="phoneError">
              ERROR
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <label for="email" class="col-sm-2 col-form-label text-capitalize">{{ __('email') }}</label>
          <div class="col-sm-10">
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" placeholder="{{ __('Please Enter Email') }}" required>
            <div class="invalid-feedback" id="emailError">
              ERROR
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <label for="password" class="col-sm-2 col-form-label text-capitalize">{{ __('password') }}</label>
          <div class="col-sm-10">
            <div class="input-group has-validation">
              <input type="password" name="password" id="password" class="form-control" value="" placeholder="{{ __('Please Enter Password If You Want To Change Password') }}">
              <span class="input-group-text">
                <i class="fa-solid fa-eye showEye"></i>
                <i class="fa-solid fa-eye-slash hideEye" style="display:none;"></i>
              </span>
              <div class="invalid-feedback" id="passwordError">
                ERROR
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <label for="password_confirmation" class="col-sm-2 col-form-label text-capitalize">{{ __('password confirmation') }}</label>
          <div class="col-sm-10">
            <div class="input-group has-validation">
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" value="" placeholder="{{ __('Please Enter Password Confirmation') }}">
              <span class="input-group-text">
                <i class="fa-solid fa-eye showEye"></i>
                <i class="fa-solid fa-eye-slash hideEye" style="display:none;"></i>
              </span>
            </div>
          </div>
        </div>

        <fieldset class="row mb-3">
          <legend class="col-form-label col-sm-2 pt-0 text-capitalize">{{ __('status') }}</legend>
          <div class="col-sm-10">
            <div class="d-inline-block me-1">{{ __('Non Active') }}</div>
            <div class="form-check form-switch d-inline-block">
              @php
              $statusChecked = $user->is_active
              ? 'checked'
              : '';
              @endphp
              <input type="checkbox" class="form-check-input" name="status" id="status" style="cursor: pointer;" {{ $statusChecked }}>
              <label for="status" class="form-check-label">{{ __('Active') }}</label>
            </div>
          </div>
        </fieldset>

        <div class="row mb-3">
          <div class="col-sm-10 offset-sm-2">
            <button type="submit" class="btn btn-outline-primary text-uppercase">
              <i class="fa-solid fa-check me-1"></i>
              {{ __('save') }}
            </button>

            <a href="{{ route('user.index') }}" name="cancel" id="cancel" class="btn btn-outline-danger text-uppercase">
              <i class="fa-solid fa-x me-1"></i>
              {{ __('cancel') }}
            </a>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection

@once
  @push('after-scripts')
  <script>
    $(function() {
      $(".showEye").click(passwordShowHide);
      $(".hideEye").click(passwordShowHide);
      function passwordShowHide() {
        var password = $("#password");
        var password2 = $("#password_confirmation");
        var showEye = $(".showEye");
        var hideEye = $(".hideEye");
        if (password.attr('type') === "password") {
          password.prop('type', 'text');
          password2.prop('type', 'text');
          showEye.css("display","none");
          hideEye.css("display","block");
        } else {
          password.prop('type', 'password');
          password2.prop('type', 'password');
          showEye.css("display","block");
          hideEye.css("display","none");
        }
      }

      $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var formData = new FormData(this);
        var formURL = $(this).attr("action");

		    $.ajax({
          url: formURL,
			    method: "POST",
			    data: formData,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $("button").attr("disabled",true);
          },
          complete: function() {
            $("button").attr("disabled",false);
          },
          success:function(data) {
            $("button").attr("disabled",false);
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'save successful',
              }).then(function (result) {
                window.location.href = "{{ route('user.index') }}";
              })
            }
          },
          error: function(jqXhr, json, errorThrown) {
            $("button").attr("disabled",false);
            var data = jqXhr.responseJSON;
            $('.alert').hide();
            $.each(data.errors, function(index, value ) {
              var html = '';
              html += '<div class="alert alert-danger" role="alert">';
              html += '<span>' + value + '</span>';
              html += '</div>';
              $('#'+index+'Error').html(html);

              document.getElementById(index).setCustomValidity(html);
            });
            Swal.fire({
              icon: 'error',
              title: 'Error Validation',
              text: 'Please check your input',
            });
            $('#form-edit').addClass('was-validated');
          }
        });
      });
    });
  </script>
  @endpush
@endonce
