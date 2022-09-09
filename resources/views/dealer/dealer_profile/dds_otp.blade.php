@extends('layouts.admin')
@section('content')
<div class="alert alert-success d-none" id="msg_div">
    <span id="res_message"></span>
</div>
<?php 
  $states = getAllState(1);
  $cities = getAllCities(1);
?>
<div class="card">
    <div class="card-header"></div>
    <div class="card-body">
       <p>{{$dds->name}}</p>
       <p>{{$cities[$dds->city_id]}}</p>
       <p>{{$states[$dds->state_id]}}</p>
    </div>
</div>

@if(session()->has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <ul class="p-0 m-0" style="list-style: none;">
    <li>{{ session()->get('error') }}</li>
  </ul>
</div>
@endif
<form action="{{route('admin.verifyOtp')}}" method="post">
	@csrf
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="form_name">OTP *</label>
            <input type="text" name="otp" class="form-control" placeholder="Please enter OTP." maxlength="6" required>
        </div>
    </div>
    <input type="hidden" name="status" value="9">
    <input type="hidden" name="order_id" value="{{$orderDetail->id}}">
    <input type="hidden" name="shipment_id" value="{{$shipment->id}}">
</div>
<div class="text-center">
	<button type="submit" class="btn btn-primary">Confirm</button>
</div>
</form>
@endsection