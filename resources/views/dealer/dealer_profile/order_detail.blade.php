@extends('layouts.admin')
@section('content')
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Product Model</th>
      <th scope="col">DD Price</th>
      <th scope="col">Attribute Price</th>
      <th scope="col">Quantity</th>
      <th scope="col">Amount</th>
      <th scope="col">Status</th>
      <th scope="col">Created At</th>
    </tr>
  </thead>
  <tbody>
  <?php $sum_of_amount = 0; ?>
    @foreach ($orderDetails as $detail)
      <?php $sum_of_amount += $detail->amount; ?>
        <tr>
          <th scope="row">{{$detail->product_name}}</th>
          <td>{{$detail->dd_price}}</td>
          <td>{{$detail->attr_price}}</td>
          <td>{{$detail->quantity}}</td>
          <td>{{number_format($detail->amount)}}</td>
          <td>{{ucwords($detail->status)}}</td>
          <td>{{$detail->created_at}}</td>
        </tr>
    @endforeach
  </tbody>
</table>
<table class="table table-hover">
  <tbody>
      <tr>
        <th scope="row"></th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Amount: </td>
        <td>{{number_format($sum_of_amount)}}</td>
      </tr>
  </tbody>
</table>

@endsection
