@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        View Dealer Category
    </div>

    <div class="card-body">
        <div class="form-group">
            <label for="name">Name<span style="color: red;">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{$category->name}}" readonly>
        </div>
        <div class="form-group">
            <label for="percentage">Percentage<span style="color: red;">*</span></label>
            <input type="text" id="percentage" name="percentage" class="form-control" value="{{$category->percentage}}" readonly>
        </div>
        <div class="form-group">
            <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
            <input type="text" class="form-control" name="status" value="{{$category->status}}" readonly>
        </div>
        <div>
            <a href="{{route('admin.dealer-categories')}}" class="btn btn-danger">Back</a>
        </div>
    </div>
</div>
@endsection
