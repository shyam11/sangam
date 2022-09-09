@extends('layouts.admin')
@section('content')
<div class="alert alert-success d-none" id="msg_div">
    <span id="res_message"></span>
</div>
<h1>Test</h1>
@foreach($parentAttribute as $key=>$value)
<div class="container">
    <h3>{{$value}}</h3>
  <table class="table">
      <thead>
        <tr>
          <th scope="col">Attribute</th>
          <th scope="col">Price</th>
          <th scope="col">Minimum Quantity</th>
          <th scope="col">New Quantity</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
        <tbody>
        @if(!empty($attributeGroups) && isset($attributeGroups))
        @foreach($attributeGroups as $group)
        <input type="hidden" name="model_id" value="{{$group->model_id}}"/>
        @if($key == $group->parent_id)
        <tr>
          <td>{{$group->name}}</td>
          <td><input type="text" class="price" value="{{$group->price}}" style="max-width: 50px;"></td>
          <td><input type="text" class="min_quantity" value="{{$group->min_quantity}}" style="max-width: 50px;"></td>
          <td><input type="text" class="available_quantity" value="{{$group->available_quantity}}" style="max-width: 50px;"></td>
          <td><button class="form-control btn btn-primary update" data-model="{{$group->attribute_id}}" data-id="{{$group->id}}">Update</button>|<button class="form-control btn btn-danger delete" data-model="{{$group->attribute_id}}" data-id="{{$group->id}}">Delete</button></td>
        </tr>
        @endif
        @endforeach
        @endif
    </tbody>
  </table>
</div>
@endforeach
@endsection
@section('scripts')
<script>
    $(document).on('click','.delete', function(){
        let id = $(this).attr("data-id");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/admin/attributegroup/"+id,
            type: 'DELETE',
            data: {id:id},
            success: function(response){
            console.log(response);
                $('#res_message').show();
                $('#res_message').html(response);
                $('#msg_div').removeClass('d-none');
                setTimeout(function(){
                location.reload();
                }, 1000);
                setTimeout(function(){
                    $('#res_message').hide();
                    $('#msg_div').hide();
                },1000);
            }
        });
    });

    $(document).on('click','.update', function(){
        let id = $(this).attr("data-id");
        let price = $(this).parent().siblings().find(".price").val();
        let min_quantity = $(this).parent().siblings().find(".min_quantity").val();
        let available_quantity = $(this).parent().siblings().find(".available_quantity").val();
        console.log(price);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/admin/attributegroupupdate/"+id,
            type: 'POST',
            data: {id:id,price:price,min_quantity:min_quantity,available_quantity:available_quantity},
            success: function(response){
            console.log(response);
                $('#res_message').show();
                $('#res_message').html(response);
                $('#msg_div').removeClass('d-none');
                setTimeout(function(){
                location.reload();
                }, 1000);
                setTimeout(function(){
                    $('#res_message').hide();
                    $('#msg_div').hide();
                },1000);
            }
        });
    });
</script>
@endsection
