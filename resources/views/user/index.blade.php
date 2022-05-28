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
      <a href="{{ route($type . '.create') }}" name="create" id="create" class="btn btn-outline-primary text-uppercase fw-bold">
        <i class="fa-solid fa-plus"></i>
        {{ __('create') }}
      </a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col">
      <div class="input-group">
        <input class="form-control" type="text" placeholder="Search" id="search">
        <span class="input-group-text">
          <i class="fa-solid fa-magnifying-glass"></i>
        </span>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <table id="data-table" class="table table-striped table-hover table-sm">
        <thead>
          <tr>
            <th>No</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Name</th>
            <th>Username</th>
            <th>Phone</th>
            <th>Email</th>
            <th class="status">Status</th>
            <th class="action">Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@once
  @push('after-style')
    <style>
      .dropdown-toggle::after {
        display: none;
      }

      .dataTables_processing {
        z-index: 11000 !important;
      }
    </style>
  @endpush
@endonce

@once
  @push('after-scripts')
  <script>
    $(function() {
      $.fn.DataTable.ext.pager.numbers_length = 5;

      var table = $('#data-table').DataTable({
        ajax: {
          url: "{{ route($type . '.index') }}",
        },
        autoWidth: false,
        columns: [
          {
            name: 'DT_RowIndex',
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            name: 'created_at',
            data: {
              _: 'created_at.display',
              'sort': 'created_at.timestamp'
            }
          },
          {
            name: 'updated_at',
            data: {
              _: 'updated_at.display',
              sort: 'updated_at.timestamp'
            }
          },
          { name: 'name', data: 'name' },
          { name: 'username', data: 'username' },
          { name: 'phone', data: 'phone' },
          { name: 'email', data: 'email' },
          { name: 'status', data: 'status', orderable: false, searchable: false },
          { name: 'action', data: 'action', orderable: false, searchable: false },
        ],
        columnDefs: [
          {
            targets: 'action',
            className: "text-center",
          },
          {
            targets: 'status',
            className: "text-center",
          },
        ],
        deferRender: true,
        dom: "<'row'<'col-6'l><'col-6'p>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-6'i><'col-6'p>>",
        fixedHeader: {
          header: true,
        },
        language: {
          decimal: "",
          emptyTable: "No data available in table",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          infoEmpty: "Showing 0 to 0 of 0 entries",
          infoFiltered: "(filtered from _MAX_ total entries)",
          infoPostFix: "",
          thousands: ",",
          lengthMenu: "Show _MENU_",
          loadingRecords: "Loading...",
          processing: "",
          search: "",
          searchPlaceholder: "Search",
          zeroRecords: "No matching records found",
          paginate: {
            first: "<i class='fa-solid fa-angles-left'></i>",
            last: "<i class='fa-solid fa-angles-right'></i>",
            next: "<i class='fa-solid fa-angle-right'></i>",
            previous: "<i class='fa-solid fa-angle-left'></i>"
          },
          aria: {
              sortAscending:  ": activate to sort column ascending",
              sortDescending: ": activate to sort column descending"
          }
        },
        lengthChange: true,
        lengthMenu: [
            [5, 10, 25, 50, 100],
            [5, 10, 25, 50, 100],
        ],
        order: [1,'desc'],
        pageLength: 50,
        pagingType: 'full_numbers',
        processing: true,
        responsive: true,
        searching: true,
        serverSide: true,
        select: false,
        drawCallback: function( settings ) {
          $('[data-bs-toggle="tooltip"]').tooltip({
            container : 'body'
          });
        },
      });

      $('#search').keypress(function(e){
        table.search($(this).val()).draw() ;
      });

      table.on('page.dt', function() {
        $('html, body').animate({
            scrollTop: $('#data-table').offset().top
        }, 'fast');
        $('thead tr th:first-child').focus();
        $( "#search" ).focus();
      });

      table.on('click', '.delete-btn[data-id]', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        Swal.fire({
          title: 'Are you sure ?',
          icon: 'warning',
          focusConfirm: false,
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
        }).then((result) => {
          if (result.dismiss !== Swal.DismissReason.cancel) {
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
              url: "user/" + id,
              type: 'DELETE',
              dataType: 'json',
              data: {method: '_DELETE', submit: true},
              success:function(data) {
                Swal.fire(
                  'Deleted!',
                  'Your data has been deleted.',
                  'success'
                )
                table.draw(false);
              },
              error: function(jqXhr, json, errorThrown){
                Swal.fire(
                  'Failed!',
                  'Your data failed to delete.',
                  'error'
                )
              }
            });
          }
        })
      });
    });
    </script>
  @endpush
@endonce
