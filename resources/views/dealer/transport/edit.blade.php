@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Transport Management
    </div>

    <div class="card-body">
        <form action="{{ route('admin.transport.update') }}" onSubmit="return confirm('Please verify before submit.')" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Name<span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{$transport->name}}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
            </div>
            <input type="hidden" name="id" value="{{$transport->id}}">
            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                <label for="mobile">Mobile<span style="color: red;">*</span></label>
                <input type="text" id="mobile" name="mobile" class="form-control" value="{{$transport->mobile}}" required>
                @if($errors->has('mobile'))
                    <em class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('vehicle_type') ? 'has-error' : '' }}">
                <label for="vehicle_type">Vehicle Type<span style="color: red;">*</span></label>
                <input type="text" id="vehicle_type" name="vehicle_type" class="form-control" value="{{$transport->vehicle_type}}" required>
                @if($errors->has('vehicle_type'))
                    <em class="invalid-feedback">
                        {{ $errors->first('vehicle_type') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('vehicle_number') ? 'has-error' : '' }}">
                <label for="vehicle_number">Vehicle Number<span style="color: red;">*</span></label>
                <input type="text" id="vehicle_number" name="vehicle_number" class="form-control" value="{{$transport->vehicle_number}}" required>
                @if($errors->has('vehicle_number'))
                    <em class="invalid-feedback">
                        {{ $errors->first('vehicle_number') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('vehicle_owner') ? 'has-error' : '' }}">
                <label for="priority">Vehicle Owner/ Transport Owner <span style="color: red;">*</span></label>
                <input type="text" name="vehicle_owner" class="form-control" value="{{$transport->vechicle_owner}}" required>
                @if($errors->has('vehicle_owner'))
                    <em class="invalid-feedback">
                        {{ $errors->first('vehicle_owner') }}
                    </em>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
                <select name="status" id="status" class="form-control select2" required>
                    <option value="active" {{$transport->status == "active" ? 'selected':''}}>Active</option>
                    <option value="inactive" {{$transport->status == "inactive" ? 'selected':''}}>Inactive</option>
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
