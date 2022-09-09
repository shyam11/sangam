@extends('layouts.admin')
@section('content')
<div class="alert alert-success d-none" id="msg_div">
    <span id="res_message"></span>
</div>
<?php 
  $states = getAllState(1);
  $cities = getAllCities(1);
?>
<div class="card">
    <div class="card-header"></div>
    <div class="card-body">
       <p>{{$dds->name}}</p>
       <p>{{$cities[$dds->city_id]}}</p>
       <p>{{$states[$dds->state_id]}}</p>
    </div>
</div>
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
      <th scope="col">Price</th>
      <th scope="col">Quantity</th>
      <th scope="col">Amount</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php $sum_of_amount = 0; $total_quantity = 0; ?>
    @foreach ($orderDetails as $detail)
    <input type="hidden" name="id" class="order" value="{{$detail->order_id}}">
    <?php 
        $sum_of_amount += $detail->amount; 
        $total_quantity += $detail->quantity;
    ?>
        <tr>
        <th scope="row">{{$detail->product_name}}</th>
        <td>{{$detail->dd_price}}</td>
        <td><input type="text" name="qty" class="qty" value="{{$detail->quantity}}" {{$detail->status == "freeze" ? 'readonly':''}}/></td>
        <td>{{number_format($detail->amount)}}</td>
        <td>{{ucwords($detail->status)}}</td>
        @if($detail->status != "freeze")
        <td><button class="btn btn-success update"  data-id="{{$detail->id}}">Update</button>| <button class="btn btn-danger freeze"  data-id="{{$detail->id}}">Freeze</button></td>
        @endif
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Total Amount with GST: </strong></td>
        <td>{{number_format($order->total_amount)}}</td>
        <td></td>
        </tr>
        <input type="hidden" name="total_quantity" value="{{$total_quantity}}">
  </tbody>
</table>
</div>

<div class="container">
    <form method="post" action="{{route('admin.change-status')}}" onSubmit="return confirm('Please verify before submit.')">
        @csrf
        <input type="hidden" name="order_id" value="{{$order->id}}">
        <input type="hidden" name="dd_id" value="{{$dds->user_id}}">
        <input type="hidden" name="total_amount" value="{{$order->total_amount}}">
    @if($order->status == 1)
        <div class="messages"></div>
        <div class="controls">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="form_name">Transaction Detail *</label>
                        <input id="form_name" type="text" name="transaction" class="form-control" placeholder="Transaction Id *" data-error="name is required." required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="form_email">Payment Method *</label>
                        <input id="form_email" type="text" name="payment_method" class="form-control" placeholder="Payment Method *" data-error="Valid email is required." required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="form_phone">Amount</label>
                        <input id="form_phone" type="number" name="received_amount" class="form-control" placeholder="Received Amount" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="form_phone">Remark</label>
                        <input id="form_phone" type="text" name="remark" class="form-control" placeholder="Remark" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12">
                <p class="text-muted"><strong>*</strong> These fields are required.</p>
            </div>
        </div>
        <?php
            $a = date('Y-m-d',strtotime($order->created_at));
            $b = date('Y-m-d',strtotime($order->order_expiry));
            $days = (strtotime($b) - strtotime($a)) / (60 * 60 * 24);
            $new_date = date('Y-m-d', strtotime($order->order_expiry. ' + 2 days'));
            ?>
        @if($days <= 3)
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <input id="form_name" type="radio" name="status" placeholder="Transaction Id *" required="required" data-error="name is required." value="6"> Confirm Payment and move to Production
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input id="form_email" type="radio" name="status"  placeholder="Payment Method *" required="required" data-error="Valid email is required." value="01">
                        Extend Due Date
                    </div>
                </div>
            </div>
            <div class="row" id="extendedDate">
                <div class="form-group">
                    <label for="form_name">Created Date *</label>
                    <input id="form_name" type="text" name="" class="form-control" placeholder="Transaction Id *" required="required" data-error="name is required." value="{{$order->created_at}}" readonly>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="form_phone">Expiry Date</label>
                        <input id="form_phone" type="text" name="" class="form-control" placeholder="Received Amount" value="{{$order->order_expiry}}" readonly>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="form_phone">Extended Date</label>
                        <input id="form_phone" type="text" name="new_date" class="form-control" value="{{$new_date}}" readonly>
                    </div>
                </div>
            </div>
        @else
        <div class="col-sm-3">
            <div class="form-group">
                <input id="form_email" type="radio" name="email" placeholder="Payment Method *" required="required" data-error="Valid email is required." value="6">
                Confirm Payment and move to Production
            </div>
        </div>
        @endif
    @elseif($order->status == 6) 
        <div class="row">
            <div class="col-md-12">
                <p class="text-muted"><strong>*</strong> Please freeze above item and move to Logistic</p>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <input id="form_email" type="radio" name="status" placeholder="Payment Method *" required="required" data-error="Valid email is required." value="7">
                Confirm move to Logistic
            </div>
        </div>
    @endif
</div>
<div class="text-center">
    <button type="submit" class="btn btn-primary">Confirm</button>
</div>
</form>
@endsection
@section('scripts')
<script>
    $(document).on('click','.update', function(){
        let qty = $(this).parent().siblings().find(".qty").val();
        let id = $(this).attr("data-id");
        let order = $('.order').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/admin/orderitemupdate/"+id,
            type: 'POST',
            data: {id:id,qty:qty,order:order},
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

    $(document).on('click','.freeze', function(){
        let id = $(this).attr("data-id");
        let freeze = "freeze";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/admin/orderitemupdate/"+id,
            type: 'POST',
            data: {id:id,freeze:freeze},
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
            // console.log(cat_id);
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
                                variant+='<option value="01">Plane</option>';
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

    $(document).ready(function() {
        $("input[name$='confirm']").click(function() {
            $("#extendedDate").hide();
            $("#extendedDate").show();
        });
    });
</script>
@endsection
