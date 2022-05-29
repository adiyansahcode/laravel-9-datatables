@extends('layouts.default')

@section('title', __('Datatables CRUD - Import Data'))

@section('content')
<div class="container shadow-sm bg-body rounded py-4">
  <header class="pb-3 mb-4 border-bottom">
    <a href="{{ route($type . '.index') }}" class="d-flex align-items-center text-dark text-decoration-none">
      <img src="{{ asset('logo.svg') }}" class="me-2 fill-blue" width="50" height="50" alt="{{ config('app.name', 'Laravel 9') }} | @yield('title')">
      <span class="fs-4">@yield('title')</span>
    </a>
  </header>

  <div class="row mb-3">
    <div class="col">
      <form method="POST" action="{{ route($type . '.import') }}" id="form-import" class="needs-validation" accept-charset="UTF-8" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="row mb-3">
          <label for="file" class="col-sm-2 col-form-label text-capitalize">{{ __('file') }}</label>
          <div class="col-sm-10">
            <input type="file" name="file" id="file" class="form-control" value="" placeholder="{{ __('Please Enter File') }}" required>
            <div class="invalid-feedback" id="fileError">
              ERROR
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-sm-10 offset-sm-2">
            <div class="row">
              <div class="col d-flex justify-content-start">
                <button type="submit" class="btn btn-outline-primary text-uppercase me-2">
                  <i class="fa-solid fa-check me-1"></i>
                  {{ __('save') }}
                </button>

                <a href="{{ route($type . '.index') }}" name="cancel" id="cancel" class="btn btn-outline-danger text-uppercase">
                  <i class="fa-solid fa-x me-1"></i>
                  {{ __('cancel') }}
                </a>
              </div>

              <div class="col d-flex justify-content-end">
                <a href="{{ route($type . '.import.template') }}" name="cancel" id="cancel" class="btn btn-outline-primary text-uppercase">
                  <i class="fa-solid fa-file-arrow-down fa-lg me-1"></i>
                  {{ __('download template') }}
                </a>
              </div>
            </div>
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
      $('#form-import').on('submit', function(e) {
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

            const onlyInputs = document.querySelectorAll('#form-import input');
            for(var i = 0; i < onlyInputs.length; i++) {
                name = onlyInputs[i].id;
                if (name) {
                  document.getElementById(name).setCustomValidity('');
                }
            }
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
                window.location.href = "{{ route($type . '.index') }}";
              })
            }
          },
          error: function(jqXhr, json, errorThrown) {
            $("button").attr("disabled",false);
            var data = jqXhr.responseJSON;
            $.each(data.errors, function(index, value) {
              if (!isNaN(index)) {
                index = 'file';
              }
              $('#'+index+'Error').html(value[0]);
              document.getElementById(index).setCustomValidity(value[0]);
            });
            Swal.fire({
              icon: 'error',
              title: 'Error Validation',
              text: 'Please check your input',
            });
            $('#form-import').addClass('was-validated');
          }
        });
      });
    });
  </script>
  @endpush
@endonce
