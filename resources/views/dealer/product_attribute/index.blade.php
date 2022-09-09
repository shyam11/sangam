@extends('layouts.admin')
@section('content')
@can('product_attribute_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.product-attributes.create') }}">
                Add Product Attribute
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Product Attributes
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ProductAttribute">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                          Price
                        </th>
                        <th>
                            Status
                        </th>
                         <th>
                            Parent
                        </th>
                        <th>
                            Created At
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productAttributes as $key => $attribute)
                        <tr data-entry-id="{{ $attribute->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $attribute->id ?? '' }}
                            </td>
                            <td>
                                {{ $attribute->name ?? '' }}
                            </td>
                            <td>{{ $attribute->price }}</td>
                            <td>{{ ucwords($attribute->status) }}</td>
                            <td>{{ $attribute->parent_name }}</td>
                            <td>{{ date('Y-m-d',strtotime($attribute->created_at))}}</td>
                            <td>
                                @can('category_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . 'product-attributes' . '.show', $attribute->id) }}">
                                        View
                                    </a>
                                @endcan

                                @can('category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.' . 'product-attributes' . '.edit', $attribute->id) }}">
                                        Edit
                                    </a>
                                @endcan

                                @can('category_delete')
                                    <form action="{{ route('admin.product-attributes.destroy', $attribute->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('category_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.categories.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-ProductAttribute:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection