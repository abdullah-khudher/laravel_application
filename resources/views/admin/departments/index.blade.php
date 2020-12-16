@extends('layouts.admin')
@section('content')
@can('department_create')


    <div style="margin-bottom: 10px;" class="row ">

        <div class="col-md-6 text-right ">

            @if(session('success'))
                <div class="alert alert-success">تم الإضافة</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{!! \Session::get('error') !!}</div>
            @endif
        </div>


        <div class="col-md-6 text-right ">

            <form class="text-center border-dark" enctype="multipart/form-data" action="{{route('admin.upload_departments_excel')}}" method="post" >
                @csrf

                <div class="py-2 w-100 form-group btn btn-success py-2" >
                        <span>ملف الإكسل </span>
                        <br>
                        <input type="file" name="excel_file">
                </div>
                        <button class="btn btn-outline-primary">حفظ</button>
            </form>

        </div>

    </div>

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.departments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.department.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.department.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Department">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.department.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.logo') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.about') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.city') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.phone_number') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.category') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.sub_category') }}
                    </th>
                    <th>
                        {{ trans('cruds.department.fields.trader') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>

                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($cities as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>

                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($categories as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($sub_categories as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($traders as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('department_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.departments.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.departments.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'logo', name: 'logo', sortable: false, searchable: false },
{ data: 'about', name: 'about' },
{ data: 'city_name', name: 'city.name' },
{ data: 'phone_number', name: 'phone_number' },
{ data: 'category_name', name: 'category.name' },
{ data: 'sub_category_name', name: 'sub_category.name' },
{ data: 'trader_name', name: 'trader.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-Department').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  $('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value
      table
        .column($(this).parent().index())
        .search(value, strict)
        .draw()
  });
});

</script>
@endsection
