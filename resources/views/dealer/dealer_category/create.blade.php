@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Dealer Category
    </div>

    <div class="card-body">
        <form action="{{ route("admin.dealer-categories.store") }}" onSubmit="return confirm('Please verify before submit.')" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Name<span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('percentage') ? 'has-error' : '' }}">
                <label for="percentage">Percentage<span style="color: red;">*</span></label>
                <input type="text" id="percentage" name="percentage" class="form-control" value="" required>
                @if($errors->has('percentage'))
                    <em class="invalid-feedback">
                        {{ $errors->first('percentage') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
                <select name="status" id="status" class="form-control select2" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
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
