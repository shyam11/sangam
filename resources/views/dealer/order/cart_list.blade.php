@extends('layouts.admin')
@section('content')
<div class="alert alert-success d-none" id="msg_div">
    <span id="res_message"></span>
</div>
<!-- <form action="{{route('admin.addorders')}}" id="" method="post">
    @csrf
<div class="card">
    <div class="card-header"></div>
    <div class="card-body">
        <div class="row">
            <div class="col-2">
                <div class="form-group">
                    <select name="model" id="model" class="form-control select2" required>
                        <option value="">Select Model</option>
                        @foreach($models as $model)
                            <option value="{{$model->id}}">{{$model->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <select name="color" id="door_color" class="form-control select2" required>
                       
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <select name="bodycolor" id="body_color" class="form-control select2" required>
                        
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <select name="variant" id="variant" class="form-control select2">
                        
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <input type="number" name="qty" class="form-control">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary model"><i class="fas fa-shopping-cart"></i> ADD TO CART</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form> -->
@if(session()->has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <ul class="p-0 m-0" style="list-style: none;">
    <li>{{ session()->get('error') }}</li>
  </ul>
</div>
@endif
<div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Product Name</th>
          <th scope="col">MRP</th>
          <th scope="col">Add-on Price</th>
          <th scope="col">Price</th>
          <th scope="col">Quantity</th>
          <th scope="col">Amount</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
      <?php $sum_of_amount = 0; $total_quantity = 0; ?>
        @foreach ($carts as $cart)
        <input type="hidden" name="id[]" value="{{$cart->user_id}}">
        <?php 
            $sum_of_amount += $cart->amount; 
            $total_quantity += $cart->quantity;
        ?>
            <tr>
            <th scope="row">{{$cart->product_name}}</th>
            <td>{{number_format($cart->price)}}</td>
            <td>{{number_format($cart->attribute_price)}}</td>
            <td>{{number_format($cart->dd_price)}}</td>
            <td><input type="number" name="qty" maxlength="2" class="qty" value="{{$cart->quantity}}"/></td>
            <td>{{number_format($cart->amount)}}</td>
            <td><button class="btn btn-success update" data-model="{{$cart->product_id}}" data-id="{{$cart->id}}">Update</button>||<button data-id="{{$cart->id}}" class="btn btn-danger delete">Delete</button></td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Total Quantity</strong></td>
            <td>{{$total_quantity}}</td>
            <td><strong>SUBTOTAL:</strong></td>
            <td>{{number_format($sum_of_amount)}}</td>
        </tr>
        <?php $gst_amount = ($sum_of_amount * 18)/100;?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><strong></strong></td>
            <td></td>
            <td><strong>GST @ 18%:</strong></td>
            <td>{{number_format($gst_amount)}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><strong></strong></td>
            <td></td>
            <td><strong>GROSS TOTAL:</strong></td>
            <td>{{number_format($sum_of_amount + $gst_amount)}}</td>
        </tr>
      </tbody>
    </table>
</div>
<form action="{{route('admin.checkout')}}" method="post" id="checkoutForm" class="from-prevent-multiple-submits" onSubmit="return confirm('Please verify before submit.')">
@csrf
<input type="hidden" name="total_quantity" value="{{$total_quantity}}">
<div class="row">
    <div class="col-sm-6">
        Transportation Type<br><br>
        @if(strtolower(@$dealer->state_id) == 4)
            <input type="radio" id="pickup" name="vechile" value="1" required>
            <label>Pick Up Capicity <b>(9-10)</b></label><br>
            <input type="radio" id="truck" name="vechile" value="2">
            <label>Truck Capicity <b>(22-28)</b></label><br>
        @else
            <input type="radio" id="truck" name="vechile" value="2" required>
            <label>Truck Capicity <b>(22-28)</b></label><br>
        @endif
    </div>
    <div class="col-sm-6">
        <?php 
            $states = getAllState(1);
            $cities = getAllCities(1);
        ?>
        <span>Shipping  Address</span><br>
        <p>{{$dealer->name}}</p>
        <p>{{$cities[$dealer->city_id]}}</p>
        <p>{{$states[$dealer->state_id]}}</p>
    </div>
</div>
<div class="text-center">
    <button type="submit" class="btn btn-primary show_confirm from-prevent-multiple-submits">Place Order</button>
</div>
</form>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    $('.from-prevent-multiple-submits').on('submit', function(){
        $('.from-prevent-multiple-submits').attr('disabled','true');
    })
    
    $(document).on('click','.delete', function(){
        let id = $(this).attr("data-id");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "cartitemdelete/"+id,
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
        let qty = $(this).parent().siblings().find(".qty").val();
        let id = $(this).attr("data-id");
        let model = $(this).attr("data-model");
        if(qty < 1)
        {
            alert("Product quantity must be greater than 0");
            location.reload();
            return false;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "cartitemupdate/"+id,
            type: 'POST',
            data: {id:id,qty:qty,model_id:model},
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

    $(document).ready(function(){

        $(document).on('change','#model',function(){
            var model=$(this).val();
            var div=$(this).parent();

            var door_color ="";
            var body_color = "";
            var variant = "";

            $.ajax({
                type:'GET',
                url:'{!!URL::to('admin/getgrouping')!!}',
                data:{'model':model},
                success:function(data){
                    //console.log('success');

                    console.log(data);

                    //console.log(data.length);
                    door_color+='<option value="" selected disabled>Choose Door Color</option>';
                    body_color+='<option value="" selected disabled>Choose Body Color</option>';
                    variant+='<option value="" selected disabled>Choose Variant</option>';
                    var flag=0;
                    for(var j=0;j<data.length;j++){
                        if(data[j]['parent_id'] == 1)
                        {
                            door_color+='<option value="'+data[j]['id']+'">'+data[j]['name']+'</option>';
                        }
                        if(data[j]['parent_id'] == 2)
                        {
                            body_color+='<option value="'+data[j]['id']+'">'+data[j]['name']+'</option>';
                        }
                        if(data[j]['parent_id'] == 3)
                        {
                            if(flag==0)
                            {
                                variant+='<option value="01">Normal</option>';
                            }
                            flag++;
                            variant+='<option value="'+data[j]['id']+'">'+data[j]['name']+'</option>';
                        }

                    }

                   $('#door_color').html(" ");
                   $('#door_color').append(door_color);

                   $('#body_color').html(" ");
                   $('#body_color').append(body_color);

                   $('#variant').html(" ");
                   $('#variant').append(variant);
                },
                error:function(){

                }
            });
        });

    });
    // $('.show_confirm').click(function(e) {
    //     $(".show_confirm").prop('disabled', true);
    //     $(".show_confirm").text("Please wait ..");
    // });
    // $('.show_confirm').click(function(event) {
    //       var form =  $(this).closest("form");
    //       event.preventDefault();
    //       swal({
    //           title: `Are you sure you want to proceed this order?`,
    //           text: "After submit you can't edit order..",
    //           icon: "warning",
    //           buttons: true,
    //           dangerMode: true,
    //       })
    //       .then((willDelete) => {
    //         if (willDelete) {
    //           form.submit();
    //         }
    //       });
    //   });
</script>
@endsection
