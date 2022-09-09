@extends('layouts.admin')
@section('content')
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Order Id</th>
      <th scope="col">Quantity</th>
      <th scope="col">Amount</th>
      <th scope="col">Status</th>
      <th scope="col">Created At</th>
      <th scope="col">Expiry At</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php $sum_of_amount = 0; ?>
    @foreach ($orders as $order)
        <tr>
        <th scope="row">{{$order->id}}</th>
        <td>{{$order->quantity}}</td>
        <td>{{$order->total_amount}}</td>
        <td>{{$order->name}}</td>
        <td>{{$order->created_at}}</td>
        <td>{{$order->order_expiry}}</td>
        <td><a href="{{route('admin.viewdetail',[$order->id])}}" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true" title="View Detail"></i></a>
          @if($order->status == 8)
            <a href="{{route('admin.ddotp',[$order->id])}}" class="btn btn-primary"><i class="fa fa-key" aria-hidden="true"></i></a>
          @endif
          @if(!empty($order->pi_invoice))
          <a href="http://dds.sangamalmirah.in/public/invoice/{{$order->pi_invoice}}" download class="btn btn-danger"><i class="fa fa-download" aria-hidden="true"></i></a>
          @endif
          @if(!empty($order->invoice) && ($order->status == 9))
          <a href="http://dds.sangamalmirah.in/public/invoice/{{$order->invoice}}" download class="btn btn-success"><i class="fa fa-download" title="Download Invoice" aria-hidden="true"></i></a>
          @endif
        </td>
        </tr>
    @endforeach
  </tbody>
</table>

@endsection
