@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        View Product Model
    </div>

    <div class="card-body">
            <div class="form-group">
                <label for="title">Title<span style="color: red;"></span></label>
                <input type="text" id="title" name="title" class="form-control" value="{{$models->title}}" readonly>
            </div>
            <div class="form-group">
                <label for="model_no">Model<span style="color: red;"></span></label>
                <input type="text" id="model_no" name="model_no" class="form-control" value="{{$models->model_no}}" readonly>
            </div>
            <div class="form-group">
                <label for="status" style="margin-top: 12px;">Status<span style="color: red;"></span></label>
                <input type="text" name="status" class="form-control" value="{{ucwords($models->status)}}" readonly>
            </div>
            <div>
                <a href="{{route('admin.product-model')}}" class="btn btn-danger">Back</a>
            </div>
        </form>


    </div>
</div>
@endsection
