@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Store
    </div>
<?php //dd($dealer->user_id); ?>
    <div class="card-body">
        <form action="{{ route('admin.dealer-profile.update') }}" onSubmit="return confirm('Please verify before submit.')" method="POST">
            @csrf
            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                <label for="user_id" style="margin-top: 12px;">Select Dealer<span style="color: red;">*</span></label>
                <select name="user_id" id="user_id" class="form-control select2" required>
                    <option value="">Please Select</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{$dealer->user_id == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('user_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('user_id') }}
                    </em>
                @endif
            </div>
            <input type="hidden" name="id" value="{{$dealer->id}}">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Name<span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{$dealer->name}}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="name">Email<span style="color: red;">*</span></label>
                <input type="text" id="email" name="email" class="form-control" value="{{$dealer->email}}" required>
                @if($errors->has('email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label for="name">Phone<span style="color: red;">*</span></label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{$dealer->phone}}" required>
                @if($errors->has('phone'))
                    <em class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">Address<span style="color: red;">*</span></label>
                <input type="text" id="address" name="address" class="form-control" value="{{$dealer->address}}" required>
                @if($errors->has('address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }}">
                <label for="state_id">State <span style="color: red;">*</span></label>
                <select name="state_id" id="state_id" class="form-control select2" required>
                    <option value="">Select State</option>
                    @foreach($states as $state)
                    <option value="{{$state->id}}" {{$dealer->state_id == $state->id ? 'selected' : ''}}>{{$state->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('state_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('state_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                <label for="city_id">City <span style="color: red;">*</span></label>
                <select name="city_id" id="city_id" class="form-control select2" required>
                    <option value="">Select City</option>
                    @foreach($cities as $city)
                    <option value="{{$city->id}}" {{$dealer->city_id == $city->id ? 'selected' : ''}}>{{$city->city}}</option>
                    @endforeach
                </select>
                @if($errors->has('city_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('city_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('gst_no') ? 'has-error' : '' }}">
                <label for="address">GST NO.<span style="color: red;">*</span></label>
                <input type="text" id="gst_no" name="gst_no" class="form-control" value="{{$dealer->gst_no}}" required>
                @if($errors->has('gst_no'))
                    <em class="invalid-feedback">
                        {{ $errors->first('gst_no') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('target') ? 'has-error' : '' }}">
                <label for="target">Monthly Target<span style="color: red;">*</span></label>
                <input type="number" id="target" name="target" class="form-control" value="{{$dealer->target}}" required>
                @if($errors->has('target'))
                    <em class="invalid-feedback">
                        {{ $errors->first('target') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('registered_address') ? 'has-error' : '' }}">
                <label for="address">Registered Address.<span style="color: red;">*</span></label>
                <input type="text" id="registered_address" name="registered_address" class="form-control" value="{{$dealer->registered_address}}" required>
                @if($errors->has('registered_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('registered_address') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('store_name') ? 'has-error' : '' }}">
                <label for="store_name">Store Name<span style="color: red;">*</span></label>
                <input type="text" id="store_name" name="store_name" class="form-control" value="{{$dealer->store_name}}" required>
                @if($errors->has('store_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('store_name') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('pincode') ? 'has-error' : '' }}">
                <label for="pincode">Pin Code<span style="color: red;">*</span></label>
                <input type="text" id="pincode" name="pincode" class="form-control" value="{{$dealer->pincode}}" required>
                @if($errors->has('pincode'))
                    <em class="invalid-feedback">
                        {{ $errors->first('pincode') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('dealer_category') ? 'has-error' : '' }}">
                <label for="dealer_category" style="margin-top: 12px;">Dealer Category<span style="color: red;">*</span></label>
                <select name="dealer_category" id="dealer_category" class="form-control select2" required>
                    <option value="active">Please Select</option>
                    @foreach($dealerCategory as $dealers)
                        <option value="{{$dealers->id}}" {{$dealer->dealer_category == $dealers->id ? 'selected' : ''}}>{{$dealers->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('dealer_category'))
                    <em class="invalid-feedback">
                        {{ $errors->first('dealer_category') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('registration_date') ? 'has-error' : '' }}">
                <label for="registration_date">Registration Date<span style="color: red;">*</span></label>
                <input type="text" id="registration_date" name="registration_date" class="form-control" value="{{$dealer->registration_date}}" required>
                @if($errors->has('registration_date'))
                    <em class="invalid-feedback">
                        {{ $errors->first('registration_date') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
                <select name="status" id="status" class="form-control select2" required>
                    <option value="active" {{$dealer->status == "active" ? 'selected' : ''}}>Active</option>
                    <option value="inactive" {{$dealer->status == "inactive" ? 'selected' : ''}}>Inactive</option>
                </select>
                @if($errors->has('status'))
                    <em class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </em>
                @endif
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="Save">
            </div>
        </form>


    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
        $(document).ready(function(){

        $(document).on('change','#state_id',function(){
            var state_id = $(this).val();
            var city = "";

            $.ajax({
                type:'GET',
                url:'{!!URL::to('admin/get-city')!!}',
                data:{'state_id':state_id},
                success:function(data){
                    console.log(data);
                    city+='<option value="" disabled>Select City</option>';
                    for(var j=0;j<data.length;j++){
                        city+='<option value="'+data[j]['id']+'">'+data[j]['city']+'</option>';
                    }
                   $('#city_id').html(" ");
                   $('#city_id').append(city);
                },
            });
        });

    });
</script>
@endsection