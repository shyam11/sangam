@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Product
    </div>

    <div class="card-body">
        <form action="{{ route("admin.products.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">Title<span style="color: red;">*</span></label>
                <input type="text" id="title" name="title" class="form-control" value="" required>
                @if($errors->has('title'))
                    <em class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('model_id') ? 'has-error' : '' }}">
                <label for="model_id" style="margin-top: 12px;">Model<span style="color: red;">*</span></label>
                <select name="model_id" id="status" class="form-control select2" required>
                    <option value="">Select Model</option>
                    @foreach ($models as $model)
                        <option value="{{$model->id}}">{{$model->model_no}}</option>
                    @endforeach
                </select>
                @if($errors->has('model_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('model_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('dimension') ? 'has-error' : '' }}">
                <label for="title">Dimension<span style="color: red;">*</span></label>
                <input type="text" id="dimension" name="dimension" class="form-control" value="" required>
                @if($errors->has('dimension'))
                    <em class="invalid-feedback">
                        {{ $errors->first('dimension') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('weight') ? 'has-error' : '' }}">
                <label for="weight">Weight<span style="color: red;">*</span></label>
                <input type="text" id="weight" name="weight" class="form-control" value="" required>
                @if($errors->has('weight'))
                    <em class="invalid-feedback">
                        {{ $errors->first('weight') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('color') ? 'has-error' : '' }}">
                <label for="color">Color<span style="color: red;">*</span></label>
                <input type="text" id="color" name="color" class="form-control" value="" required>
                @if($errors->has('color'))
                    <em class="invalid-feedback">
                        {{ $errors->first('color') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                <label for="price">Price<span style="color: red;">*</span></label>
                <input type="text" id="price" name="price" class="form-control" value="" required>
                @if($errors->has('price'))
                    <em class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('min_stock') ? 'has-error' : '' }}">
                <label for="min_stock">Minimum Stock<span style="color: red;">*</span></label>
                <input type="text" id="min_stock" name="min_stock" class="form-control" value="" required>
                @if($errors->has('min_stock'))
                    <em class="invalid-feedback">
                        {{ $errors->first('min_stock') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('available_stock') ? 'has-error' : '' }}">
                <label for="available_stock">Available Stock<span style="color: red;">*</span></label>
                <input type="text" id="available_stock" name="available_stock" class="form-control" value="" required>
                @if($errors->has('available_stock'))
                    <em class="invalid-feedback">
                        {{ $errors->first('available_stock') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('sku') ? 'has-error' : '' }}">
                <label for="sku">SKU<span style="color: red;">*</span></label>
                <input type="text" id="sku" name="sku" class="form-control" value="" required>
                @if($errors->has('sku'))
                    <em class="invalid-feedback">
                        {{ $errors->first('sku') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">Description<span style="color: red;">*</span></label>
                <input type="text" id="description" name="description" class="form-control" value="" required>
                @if($errors->has('description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                <label for="image">Image<span style="color: red;">*</span></label>
                <input type="file" id="image" name="image" class="form-control" value="" required>
                @if($errors->has('image'))
                    <em class="invalid-feedback">
                        {{ $errors->first('image') }}
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
