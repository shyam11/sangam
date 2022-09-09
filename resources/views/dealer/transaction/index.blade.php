@extends('layouts.admin')
@section('content')
@if(auth()->user()->isDealer() || auth()->user()->isDistributor())
    
@else
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.transactions.create') }}">
            Add  Transaction
        </a>
    </div>
</div>
@endif
<div class="card">
    <div class="card-header">
        Transaction Management
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Transation">
                <thead>
                    <tr>
                        <th>
                            Store Name
                        </th>
                        <th>
                            Order
                        </th>
                        <th>
                            Transaction
                        </th>
                        <th>
                            Debit
                        </th>
                        <th>
                            Credit
                        </th>
                        <th>
                            Balance
                        </th>
                        <th>
                            Created By
                        </th>
                        <th>
                            Created
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $key => $transaction)
                        <tr>
                            <td>
                                {{$transaction->store_name}}
                            </td>
                            <td>
                                {{$transaction->order_id}}
                            </td>
                            <td>
                                {{ $transaction->transaction_number}} /  {{ $transaction->transaction_type }} /  {{ $transaction->remark }}
                            </td>
                            <td>{{ number_format($transaction->total_amount,2)}}</td>
                            <td>{{ number_format($transaction->received_amount,2)}}</td>
                            <td>{{ number_format($transaction->balance_amount,2)}}</td>
                            <td>{{$transaction->name}}</td>
                            <td>{{ date('Y-m-d',strtotime($transaction->created_at))}}</td>
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

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  });
  $('.datatable-Transation:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection