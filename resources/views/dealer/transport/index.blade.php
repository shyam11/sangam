@extends('layouts.admin')
@section('content')
@can('dealer_profile_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.transport.create') }}">
                Add Transport
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Transport Management
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Transport">
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
                            Mobile
                        </th>
                         <th>
                            Vehicle Number
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transports as $key => $transport)
                        <tr data-entry-id="{{ $transport->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $transport->id ?? '' }}
                            </td>
                            <td>
                                {{ $transport->name ?? '' }}
                            </td>
                            <td>{{ $transport->mobile}}</td>
                            <td>{{ $transport->vehicle_number}}</td>
                            <td>{{ $transport->status}}</td>
                            <td>
                                
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . 'transport' . '.show', $transport->id) }}">
                                        View
                                    </a>
                                

                                
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.' . 'transport' . '.edit', $transport->id) }}">
                                        Edit
                                    </a>
                                
                                    <form action="{{ route('admin.transport.destroy', $transport->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                                

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
  $('.datatable-Transport:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection