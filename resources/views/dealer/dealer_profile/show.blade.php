@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Dealer Profile
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="user_id" style="margin-top: 12px;">Select Dealer<span style="color: red;">*</span></label>
            <select name="user_id" id="user_id" class="form-control select2" required>
                <option value="">Please Select</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}" {{$dealer->user_id == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="id" value="{{$dealer->id}}">
        <div class="form-group">
            <label for="name">Name<span style="color: red;">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{$dealer->name}}" required>
        </div>
        <div class="form-group">
            <label for="name">Email<span style="color: red;">*</span></label>
            <input type="text" id="email" name="email" class="form-control" value="{{$dealer->email}}" required>
        </div>
        <div class="form-group">
            <label for="name">Phone<span style="color: red;">*</span></label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{$dealer->phone}}" required>
        </div>
        <div class="form-group">
            <label for="address">Address<span style="color: red;">*</span></label>
            <input type="text" id="address" name="address" class="form-control" value="{{$dealer->address}}" required>
        </div>
        <div class="form-group">
            <label for="priority">State <span style="color: red;">*</span></label>
            <select name="state" id="state" class="form-control select2" required>
                <option value="">Select State</option>
                <option value="Bihar" {{$dealer->state_name == "Bihar" ? 'selected' : ''}}>Bihar</option>
                <option value="UP" {{$dealer->state_name == "UP" ? 'selected' : ''}}>UP</option>
                <option value="Jharkhand" {{$dealer->state_name == "Jharkhand" ? 'selected' : ''}}>Jharkhand</option>
                <option value="West Bengal" {{$dealer->state_name == "West Bengal" ? 'selected' : ''}}>West Bengal</option>
                <option value="Odisha" {{$dealer->state_name == "Odisha" ? 'selected' : ''}}>Odisha</option>
            </select>
        </div>
        <div class="form-group">
            <label for="priority">City <span style="color: red;">*</span></label>
            <input type="text" name="city" class="form-control" value="{{$dealer->city_name}}" required>
        </div>
        <div class="form-group">
            <label for="address">GST NO.<span style="color: red;">*</span></label>
            <input type="text" id="gst_no" name="gst_no" class="form-control" value="{{$dealer->gst_no}}" required>
        </div>
        <div class="form-group">
            <label for="address">Registered Address.<span style="color: red;">*</span></label>
            <input type="text" id="registered_address" name="registered_address" class="form-control" value="{{$dealer->registered_address}}" required>
        </div>
        <div class="form-group">
            <label for="store_name">Store Name<span style="color: red;">*</span></label>
            <input type="text" id="store_name" name="store_name" class="form-control" value="{{$dealer->store_name}}" required>
        </div>
        <div class="form-group">
            <label for="pincode">Pin Code<span style="color: red;">*</span></label>
            <input type="text" id="pincode" name="pincode" class="form-control" value="{{$dealer->pincode}}" required>
        </div>
        <div class="form-group">
            <label for="dealer_category" style="margin-top: 12px;">Dealer Category<span style="color: red;">*</span></label>
            <select name="dealer_category" id="dealer_category" class="form-control select2" required>
                <option value="active">Please Select</option>
                @foreach($dealerCategory as $dealers)
                    <option value="{{$dealers->id}}" {{$dealer->dealer_category == $dealers->id ? 'selected' : ''}}>{{$dealers->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="registration_date">Registration Date<span style="color: red;">*</span></label>
            <input type="date" id="registration_date" name="registration_date" class="form-control" value="{{$dealer->registration_date}}" required>
        </div>
        <div class="form-group">
            <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
            <select name="status" id="status" class="form-control select2" required>
                <option value="active" {{$dealer->status == "active" ? 'selected' : ''}}>Active</option>
                <option value="inactive" {{$dealer->status == "inactive" ? 'selected' : ''}}>Inactive</option>
            </select>
        </div>
        <div>
            <a class="btn btn-primary" href="{{route('admin.dealer-profile')}}">Back</a>
        </div>
    </div>
</div>
@endsection
