@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Account
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Account">
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
                          Store Name
                        </th>
                        <th>
                            State
                        </th>
                         <th>
                            City
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            Outstanding Amount
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                      $states = getAllState(1);
                      $cities = getAllCities(1);
                    ?>
                    @foreach($Accounts as $key => $account)
                        <tr>
                            <td>

                            </td>
                            <td>
                                {{ $account->id ?? '' }}
                            </td>
                            <td>
                                {{ $account->name ?? '' }}
                            </td>
                            <td>{{ $account->store_name }}</td>
                            <td>{{ $states[$account->state_id] }}</td>
                            <td>{{ $cities[$account->city_id] }}</td>
                            <td>{{ $account->user_type }}</td>
                            @if($account->outstanding < 0)
                            <td style="background-color: red; color: white;">{{ number_format($account->outstanding) }}</td>
                            @else
                            <td style="background-color: green; color: white;">{{ number_format($account->outstanding) }}</td>
                            @endif
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.' . 'accounts' . '.show', $account->store_id) }}">
                                      Ledger
                                </a>
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