@extends('layouts.default')

@section('title', __('Datatables CRUD'))

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
      <table class="table table-striped table-bordered table-hover table-sm">
        <tbody>
          <tr>
            <td class="text-capitalize">{{ __('name') }}</td>
            <td>{{ $data->name }}</td>
          </tr>
          <tr>
            <td class="text-capitalize">{{ __('username') }}</td>
            <td>{{ $data->username }}</td>
          </tr>
          <tr>
            <td class="text-capitalize">{{ __('phone') }}</td>
            <td>{{ $data->phone }}</td>
          </tr>
          <tr>
            <td class="text-capitalize">{{ __('email') }}</td>
            <td>{{ $data->email }}</td>
          </tr>
          <tr>
            <td class="text-capitalize">{{ __('status') }}</td>
            <td>
              @if($data->is_active)
                <i class="fa-solid fa-circle-check text-primary me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Active"></i>
                {{ __('Active') }}
              @else
                <i class="fa-solid fa-lock text-danger me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Non Active"></i>
                {{ __('Non Active') }}
              @endif
            </td>
          </tr>
        </tbody>
      </table>
      <a href="{{ route($type . '.index') }}" name="cancel" id="cancel" class="btn btn-outline-danger text-uppercase">
        <i class="fa-solid fa-rotate-left me-1"></i>
        {{ __('back') }}
      </a>
    </div>
  </div>
</div>
@endsection
