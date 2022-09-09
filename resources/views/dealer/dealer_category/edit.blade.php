@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Dealer Category
    </div>

    <div class="card-body">
        <form action="{{ route('admin.dealer-categories.update') }}" onSubmit="return confirm('Please verify before submit.')" method="POST">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Name<span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{$dealerCategory->name}}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
            </div>
            <input type="hidden" name="id" value="{{$dealerCategory->id}}">
            <div class="form-group {{ $errors->has('percentage') ? 'has-error' : '' }}">
                <label for="percentage">Percentage<span style="color: red;">*</span></label>
                <input type="text" id="percentage" name="percentage" class="form-control" value="{{$dealerCategory->percentage}}" required>
                @if($errors->has('percentage'))
                    <em class="invalid-feedback">
                        {{ $errors->first('percentage') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
                <select name="status" id="status" class="form-control select2" required>
                    <option value="active" @if($dealerCategory->status == "active" ? 'selected' : '')@endif>Active</option>
                    <option value="inactive" @if($dealerCategory->status == "inactive" ? 'selected' : '')@endif>Inactive</option>
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
