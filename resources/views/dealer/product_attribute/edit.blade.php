@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Product Attribute
    </div>

    <div class="card-body">
        <form action="{{ route('admin.product-attributes.update') }}" method="POST">
            @csrf
            <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                <label for="parent_id">Category<span style="color: red;">*</span></label>
                <select name="parent_id" id="parent_id" class="form-control select2" required>
                    <option value="0" {{$attribute->parent_id == 0 ? 'selected' : ''}}>Parent</option>
                    <option value="1" {{$attribute->parent_id == 1 ? 'selected' : ''}}>Door Color</option>
                    <option value="2" {{$attribute->parent_id == 2 ? 'selected' : ''}}>Body Color</option>
                    <option value="3" {{$attribute->parent_id == 3 ? 'selected' : ''}}>Variant</option>
                </select>
                @if($errors->has('parent_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('parent_id') }}
                    </em>
                @endif
            </div>
            <input type="hidden" name="id" value="{{$attribute->id}}">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Name<span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{$attribute->name}}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
            </div>
            <div class="form-group priceInput {{ $errors->has('price') ? 'has-error' : '' }}">
                <label for="name">Price<span style="color: red;"></span></label>
                <input type="text" id="price" name="price" class="form-control" value="{{$attribute->price}}">
                @if($errors->has('price'))
                    <em class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
                <select name="status" id="status" class="form-control select2" required>
                    <option value="active" {{$attribute->status == "active" ? 'selected' : ''}}>Active</option>
                    <option value="inactive" {{$attribute->status == "inactive" ? 'selected' : ''}}>Inactive</option>
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
    <script>
        // $(function() {
        //     $('.priceInput').hide();
        //     $('#parent_id').change(function(){
        //         if($('#parent_id').val() == '3') {
        //             $('.priceInput').show();
        //         } else {
        //             $('.priceInput').hide();
        //         }
        //     });
        // });
    </script>
@endsection
