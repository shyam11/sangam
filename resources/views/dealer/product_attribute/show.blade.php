@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Show Product Attribute
    </div>

    <div class="card-body">
        <div class="form-group">
            <label for="parent_id">Category<span style="color: red;">*</span></label>
            <select name="parent_id" id="parent_id" class="form-control select2" required>
                <option value="0" {{$attribute->parent_id == 0 ? 'selected' : ''}}>Parent</option>
                <option value="2" {{$attribute->parent_id == 2 ? 'selected' : ''}}>Color</option>
                <option value="3" {{$attribute->parent_id == 3 ? 'selected' : ''}}>Variant</option>
            </select>
        </div>
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">Name<span style="color: red;">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{$attribute->name}}" required>
        </div>
        <div class="form-group priceInput">
            <label for="name">Price<span style="color: red;"></span></label>
            <input type="text" id="price" name="price" class="form-control" value="{{$attribute->price}}">
        </div>
        <div class="form-group">
            <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
            <select name="status" id="status" class="form-control select2" required>
                <option value="active" {{$attribute->status == "active" ? 'selected' : ''}}>Active</option>
                <option value="inactive" {{$attribute->status == "inactive" ? 'selected' : ''}}>Inactive</option>
            </select>
        </div>
        <div>
            <a href="{{route('admin.product-attributes')}}" class="btn btn-primary" type="submit">Back</a>
        </div>
    </div>
</div>
@endsection
