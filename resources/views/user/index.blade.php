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
    <div class="col d-flex justify-content-start">
      <a href="{{ route($type . '.create') }}" name="create" id="create" class="btn btn-outline-primary text-uppercase fw-bold">
        <i class="fa-solid fa-plus me-1"></i>
        {{ __('create') }}
      </a>
    </div>
    <div class="col d-flex justify-content-end">
      <div class="btn-group" role="group" aria-label="Basic outlined example">
        <button type="button" id="exportExcel" class="btn btn-outline-primary">
          <i class="fa-regular fa-file-excel me-1"></i>
          {{ __('Excel') }}
        </button>
        <button type="button" id="exportPdf" class="btn btn-outline-primary">
          <i class="fa-regular fa-file-pdf me-1"></i>
          {{ __('PDF') }}
        </button>
      </div>
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

      var iconStatusActive = '<i class="fa-solid fa-circle-check text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Active"></i>';
      var iconStatusNonActive = '<i class="fa-solid fa-lock text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Non Active"></i>';

      function actionButton(id) {
        var urlShow = '{{ route($type . ".show", ":id") }}';
        urlShow = urlShow.replace(':id', id);

        var urlEdit = '{{ route($type . ".edit", ":id") }}';
        urlEdit = urlEdit.replace(':id', id);

        var button = '' +
          '<div class="btn-group">' +
          '<button type="button" class="btn btn-default btn-sm dropdown-toggle" id="action-' + id + '" data-bs-toggle="dropdown" aria-expanded="false">' +
          '<i class="fa-solid fa-ellipsis-vertical"></i>' +
          '</button>' +

          '<ul class="dropdown-menu dropdown-menu-end" id="action-' + id + '-menu" aria-labelledby="action-' + id + '">' +

          '<li>' +
          '<a href="' + urlShow + '" class="dropdown-item" type="button" name="view" id="' + id + '">' +
          '<i class="fa-solid fa-eye me-1"></i> VIEW' +
          '</a>' +
          '</li>' +

          '<li><div class="dropdown-divider"></div></li>' +
          '<li>' +
          '<a href="' + urlEdit + '" class="dropdown-item" type="button" name="edit" id="' + id + '">' +
          '<i class="fa-solid fa-pen-to-square me-1"></i> EDIT' +
          '</a>' +
          '</li>' +

          '<li><div class="dropdown-divider"></div></li>' +
          '<li>' +
          '<button class="dropdown-item delete-btn" type="button" name="delete" data-id="' + id + '" id="' + id + '">' +
          '<i class="fa-solid fa-trash-can me-1"></i> DELETE' +
          '</button>' +
          '</li>' +

          '</ul>' +
          '</div>' +
        '';

        return button;
      }

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
          {
            name: 'status',
            data: null,
            defaultContent: "",
            orderable: false,
            searchable: false,
            render: function ( data, type, row, meta ) {
              var status = row.is_active;
              if (status) {
                return iconStatusActive;
              } else {
                return iconStatusNonActive;
              }
            }
          },
          {
            name: 'action',
            data: null,
            defaultContent: "",
            orderable: false,
            searchable: false,
            render: function ( data, type, row, meta ) {
              var id = row.uuid;
              return actionButton(id);
            }
          },
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
        dom: "" +
          "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'p>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>" +
        "",
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
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        drawCallback: function( settings ) {
          $('[data-bs-toggle="tooltip"]').tooltip({
            container : 'body'
          });
        },
      });

      $('#search').keypress(function(e) {
        table.search($(this).val()).draw();
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

      $('#exportExcel').on('click', function(e) {
        table.buttons( '.buttons-excel' ).trigger();
      });

      $('#exportPdf').on('click', function(e) {
        table.buttons( '.buttons-pdf' ).trigger();
      });
    });
    </script>
  @endpush
@endonce
