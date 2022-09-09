@extends('layouts.admin')
@section('content')
<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">Order Id</th>
        <th scope="col">Dealer/Distributor Name</th>
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
          <td>{{$order->id}}</td>
          <td>{{$order->name}}</td>
          <td>{{$order->quantity}}</td>
          <td>{{number_format($order->total_amount,2)}}</td>
          <td>{{$order->order_status}}</td>
          <td>{{$order->created_at}}</td>
          <td>{{$order->order_expiry}}</td>
          <td><a href="{{route('admin.vieworderdetail',[$order->id])}}" title="Edit" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>
          @if($order->status == 7 || $order->status == 8)
          <a href="{{route('admin.manage-shipment',[$order->id])}}" title="Manage Shipment" class="btn btn-danger">
          <!-- Manage Shipment -->
          <i class="fa fa-cog" aria-hidden="true"></i>
        </a>
          @endif
          @if(!empty($order->pi_invoice))
          <a href="http://dds.sangamalmirah.in/public/invoice/{{$order->pi_invoice}}" download class="btn btn-danger"><i class="fa fa-download" title="Download Porforma Invoice" aria-hidden="true"></i></a>
          @endif
          @if(!empty($order->invoice))
          <a href="http://dds.sangamalmirah.in/public/invoice/{{$order->invoice}}" download class="btn btn-success"><i class="fa fa-download" title="Download Invoice" aria-hidden="true"></i></a>
          @endif
        </td>
          </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection
