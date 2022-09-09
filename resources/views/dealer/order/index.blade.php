@extends('layouts.admin')
@section('content')
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
<form action="{{route('admin.addcrmdd')}}"  method="post">
    @csrf
<?php $cities = getAllCities(1); 
$data = Session::get('id_percentage_store');
$state = getStoreState();
?>
<div class="row">
    Select Dealer/Distributor *
    <div class="col-8">
        <div class="form-group">
            <select name="dd_id" id="dd_id" class="form-control select2" required>
                <option value="">Select Dealer / Distributor</option>
                @foreach($getDD as $dealer)
                <option value="<?php echo $dealer->user_id.'_'.$dealer->percentage_dealer.'_'.$dealer->id;?>" {{$dealer->user_id.'_'.$dealer->percentage_dealer.'_'.$dealer->id == $data ? 'selected':''}}><?php echo $dealer->store_name.'  ('.$cities[$dealer->city_id].')'; ?></option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</div>
</form>
<?php $j = sizeof($modelData);
$percentage =0;
    if(auth()->user()->isDealer())
    {
        $data = Session::get('id_percentage_store');
        $arr = explode('_',$data);
        $percentage = $arr[1];
    }
?>
@for($i=0;$i<$j/2;$i++)
    {{-- @if(sizeof($modelData)>1) --}}
    <?php
        $data = array_splice($modelData,0,2);
        {{-- dd(sizeof($modelData)); --}}

    ?>
    {{-- @else
    <?php $data= array_splice($modelData,0,1) ?>
    @endif --}}
<div class="row">
@foreach ($data as $key => $value)
<?php $model_id = 0; $model_id = $value['model']['id']; ?>
  <?php $calculate = ($percentage/100)*$value['model']['price'];  ?>
  <div class="col-sm-6">
  <form action="{{route('admin.addorders')}}" id="{{$model_id}}" method="post">
    @csrf
    <?php $mrp = getOfferPrice($value['model']['price'],$state); ?>
    <div class="card">
      <div class="card-body">
            <h5 class="card-title">{{$value['model']['title']}}</h5>
            <p class="card-text">MRP. <span><del>{{number_format($mrp,2)}}</del> </span></p>
            <p class="card-text">Your Price. <span>{{getDDPrice($mrp,1)}}</span></p>
            <input type="hidden" name="model" value="{{$model_id}}"/>
        <div class="row">
            <div class="col-sm-3">
                <p><strong>Color</strong></p>
                @foreach ($value['color'] as $colKey => $colVal)
                    @if(empty($value['bodycolor']))
                        <div class="form-check">
                            <input class="form-check-input" name="color[]" type="checkbox" value="{{$colVal['id']}}" id="defaultCheck1">
                            <label class="form-check-label" for="defaultCheck1">
                                {{$colVal['name']}}
                            </label>
                        </div>
                    @else
                        <div class="form-check radio-green">
                            <input type="radio" class="form-check-input" id="radioGreen1" name="color[]" value="{{$colVal['id']}}" required>
                            <label class="form-check-label" for="radioGreen1">{{$colVal['name']}}</label>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="col-sm-3">
                @if(!empty($value['bodycolor']))
                <p><strong>Body Color</strong></p>
                @foreach ($value['bodycolor'] as $varKey => $varVal)
                    @if(empty($value['variant']))
                        <div class="form-check">
                            <input class="form-check-input {{$model_id}}m{{$varVal['id']}}" name="bodycolor[]" type="checkbox" value="{{$varVal['id']}}" id="{{$model_id}}m{{$varVal['id']}}">
                            <label class="form-check-label" for="defaultCheck1">
                                {{$varVal['name']}}
                            </label>
                        </div>
                        <div class="col-3">
                        <input class="form-control ctext q{{$model_id}}m{{$varVal['id']}}" name="qty[{{$varVal['id']}}]" type="number" id="q{{$model_id}}m{{$varVal['id']}}" style="width: 100px;" value="" min="1" max="2">
                        </div>
                    @else
                        <div class="form-check radio-green">
                            <input type="radio" class="form-check-input" id="radioGreen1" name="bodycolor[]" value="{{$varVal['id']}}" required>
                            <label class="form-check-label" for="radioGreen1">{{$varVal['name']}}</label>
                        </div>
                    @endif
                @endforeach
                @endif
            </div>
            <!-- @if(empty($value['variant']))
            <div class="col-sm-3">
                <p><strong>Quantity</strong></p>
                @foreach ($value['bodycolor'] as $colKey => $colVal)
                <input class="form-control qty" name="qty[]" type="number" value=""/>
                @endforeach
            </div>
            @endif -->
            <div class="col-sm-6">
                @if(!empty($value['variant']))
                <p><strong>Add-on / Quantity</strong></p>
                <div class="row">
                    <div class="col-3">
                            <input class="form-check-input qty" name="variant[00]" type="checkbox" value="00" id="{{$model_id}}m00" maxlength="2">
                            <label class="form-check-label" for="defaultCheck1">
                                Normal
                            </label>
                    </div>
                    <div class="col-3">
                        <input class="form-control ctext q{{$model_id}}m00" name="qty[00]" type="number" id="q{{$model_id}}m00" style="width: 100px;" value="" min="1" max="2">
                    </div>
                </div>
                @foreach ($value['variant'] as $varKey => $varVal)
                <?php $varientid = $varVal['id']; ?>
                <div class="row">
                    <div class="col-3">
                        <input class="form-check-input variant" name="variant[]" type="checkbox" value="{{$varVal['id']}}" id="{{$model_id}}m{{$varVal['id']}}">
                        <label class="form-check-label" for="defaultCheck1">
                            {{$varVal['name']}}
                        </label>
                    </div>
                    <div class="col-3">
                        <input class="form-control ctext q{{$model_id}}m{{$varVal['id']}}" name="qty[{{$varientid}}]" type="number" id="q{{$model_id}}m{{$varVal['id']}}" style="width: 100px;" value="" min="1" max="2">
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            @if(!empty($value['variant']))
           <!--  <div class="col-sm-3">
                <p><strong>Quantity</strong></p>
                <input class="form-control ctext q{{$model_id}}m01" name="qty[]" type="number" id="q{{$model_id}}m01" value=""/>
                @foreach ($value['variant'] as $varKey => $varVal)
                <input class="form-control ctext q{{$model_id}}m{{$varVal['id']}}" name="qty[]" type="number" id="q{{$model_id}}m{{$varVal['id']}}" value=""/>
                @endforeach
            </div> -->
            @endif
        </div>
        <br>
        <button type="button" class="btn btn-primary model"><i class="fas fa-shopping-cart"></i> ADD TO CART</button>
      </div>
    </div>
    </form>
  </div>

@endforeach
</div>
@endfor
@endsection
@section('scripts')
<script>
$(document).on("click",".model", function(){
    var formid = $(this).closest("form").attr("id");
    $('input[type=radio').attr("required","true");
    if(!$('input:radio', 'form#'+formid).is(':checked')) {
        alert("You have not checked");
        return false;
    }
    var checkcount = $('input:checkbox:checked').length;
    if(checkcount == 0)
    {
         alert("You have not checked");
        return false;
    }
   // var dd_id = $("#dd_id").val();
   // if(dd_id == '')
   // {
   //      alert("Please select store.");
   //      return false;
   // }else{
        $('form#'+formid).submit();
   // }
});

$(document).ready(function(){
    $('.ctext').hide();
    $('input[type=checkbox]').click(function() {
        var variant_id = $(this).attr('id');
        console.log(variant_id);
        // $("#q"+variant_id).show();
        $(".q"+variant_id).toggle();
    });
});

</script>
@endsection
