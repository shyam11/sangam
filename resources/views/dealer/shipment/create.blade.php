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
<div class="container">
    <form method="post" action="{{route('admin.manage-shipment.store')}}" onSubmit="return confirm('Please verify before submit.')">
        @csrf
        <input type="hidden" name="order_id" value="{{$orderDetail->id}}">
        <input type="hidden" name="dd_id" value="{{$dds->user_id}}">
        <input type="hidden" name="shipment_id" value="{{@$shipments->id}}">
    @if($orderDetail->status == 7)
        <div class="messages"></div>
        <div class="controls">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_name">DD Name *</label>
                        <input id="form_name" type="text" name="dd_name" class="form-control"  value="{{@$shipments->name}}">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_email">DD Mobile *</label>
                        <input id="form_email" type="text" name="dd_mobile" class="form-control" placeholder="Payment Method *" value="{{@$shipments->phone}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_name">Store Name *</label>
                        <input id="form_name" type="text" name="store_name" class="form-control" placeholder="Transaction Id *" value="{{@$shipments->store_name}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_email">Address *</label>
                        <input id="form_email" type="text" name="address" class="form-control" placeholder="Payment Method *" value="{{@$shipments->address}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_name">State *</label>
                        <input id="form_name" type="text" name="state" class="form-control" placeholder="Transaction Id *" value="{{@$shipments->state}}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_email">City *</label>
                        <input id="form_email" type="text" name="city" class="form-control" placeholder="Payment Method *" value="{{@$shipments->city}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="form_name">Body Number *</label>
                        <textarea type="text" name="body_number" class="form-control" placeholder="Body Number with comma separated *" data-error="name is required."></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12">
                <p class="text-muted"><strong>*</strong> Please enter comma seperated body number.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                <label for="status" style="margin-top: 12px;">Delivered By *</span></label>
                <select name="delivered_by" id="status" class="form-control select2" required>
                    <option value="">Please Select</option>
                    @foreach($transports as $transport)
                        <option value="{{$transport->id}}">{{$transport->name}} {{$transport->mobile}} {{$transport->vehicle_type}} {{$transport->vehicle_number}}</option>
                    @endforeach
                    
                </select>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <input id="form_name" type="radio" name="status" placeholder="Transaction Id *" required="required" data-error="name is required." value="8"> Move To Dispatch
                </div>
            </div>
        </div>
    @elseif($orderDetail->status == 8)
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="form_name">OTP *</label>
                    <input type="text" name="otp" class="form-control" placeholder="Please enter OTP." required>
                </div>
            </div>
            <input type="hidden" name="status" value="9">
        </div>
    @endif
</div>
<div class="text-center">
    <button type="submit" class="btn btn-primary">Confirm</button>
</div>
</form>
@endsection
