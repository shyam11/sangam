<?php
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\User;
use App\Models\DealerProfile;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use App\Models\State;
use App\Models\City;

function getDDPrice($mrp,$flag)
{
    $percentage = 0;
    $dd_price = 0;
    $price = 0;
    $data = Session::get('id_percentage_store');
    if(!empty($data) && isset($data))
    {
        $arr = explode('_',$data);
        $percentage = $arr[1];
        $dd_price = ($percentage /100) * $mrp;
    }
    $price = round($mrp - $dd_price);
    if($flag)
    {
        $price = number_format($price) . '   ('. $percentage .'% off)';
    }
    return $price;
}

function getStoreState()
{
    $data = Session::get('id_percentage_store');
    $store_id = 0;
    if(!empty($data) && isset($data))
    {
        $arr = explode('_',$data);
        $percentage = $arr[1];
        $store_id = $arr[2];
    }
    $dealer = DealerProfile::select('state_id')->where(["id"=>$store_id])->first();
    return @$dealer->state_id;
}

function getCartQty()
{
    $data = Session::get('id_percentage_store');
    $store_id = 0;
    if(!empty($data) && isset($data))
    {
        $arr = explode('_',$data);
        $percentage = $arr[1];
        $store_id = $arr[2];
    }
    return Cart::where(["store_id"=>$store_id])->sum('quantity');
}

function getOfferPrice($mrp, $state_id)
{
    if($state_id == 24)
    {
        $perntAmt = (12.59 /100) * $mrp;
        $mrp = $mrp - $perntAmt;
    }else{
        $mrp = $mrp;
    }
    return $mrp;
}

function getItemCount($id)
{
    $count = Cart::where(["user_id"=>$id,"status"=>"incart"])->get()->count();
    return $count;
}

function getIndianCurrency(float $number)
{
    $decimalVal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $decimal_length = strlen($decimalVal);

    $j = 0;
    $str1 = array();
    while( $j < $decimal_length ) {
        $divider = ($j == 2) ? 10 : 100;
        $number = floor($decimalVal % $divider);
        $decimal = floor($decimalVal / $divider);
        $j += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str1)) && $number > 9) ? 's' : null;
            //$hundred = ($counter == 1 && $str1[0]) ? ' and ' : null;
            $hundred = null;
            $str1 [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str1[] = null;
    }
    $paise = implode('', array_reverse($str1));
    //$paise = ($decimal > 0) ? " and " . ($words[(int)($decimal/10)*10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    $paiseText = ($decimalVal > 0) ? " and " . $paise . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paiseText;
}

function sendWhatsappMessage($order)
{
    $states = getAllState(1);
    $cities = getAllCities(1);
    if($order->status == 1)
    {
        $admin = User::where(["id"=>1])->first();
        $newUser = User::where(["id"=>$order->updated_by])->first();
        $dealer = DealerProfile::where(["id"=>$order->store_id])->first();
        // dd($dealer);
        if(!empty($admin))
        {
            $headers = array(
                "Content-Type" => 'application/json',
                "Authorization"=> 'Basic WmN1a0VfLUJEYmdEZXVnMHhVVlZfYVNueFdsaTE1Z2pHSk12M1pDSjA4QTo='
            );
            $apiURL = 'https://api.interakt.ai/v1/public/track/users/';
            $postInput = array(
                "phoneNumber"=> @$admin->mobile,
                "countryCode"=> "+91",
                "traits"=> array(
                    "name"=> @$admin->name,
                    "phoneNumber"=> @$admin->mobile,
                    "dd_state" => $states[@$dealer->state_id],
                    "dd_city"=> $cities[@$dealer->city_id],
                    "store_name" => @$dealer->store_name,
                    "order_amount" => number_format(@$order->total_amount),
                    "order_id"  =>$order->id,
                    "received_amount" => "None",
                    "confirmed_by"=> "None",
                )
            );
            $response = Http::withHeaders($headers)->post($apiURL, $postInput);
            $responseBody = json_decode($response->getBody(), true);
            if($responseBody['result'])
            {
                $eventApiURL = 'https://api.interakt.ai/v1/public/track/events/';
                $postEventInput = array(
                    "phoneNumber"=> $admin->mobile,
                    "countryCode"=> "+91",
                    "event"=> "Order Placed",
                    "traits"=> array(
                        "dd_state" => $states[@$dealer->state_id],
                        "dd_city"=> $cities[@$dealer->city_id],
                        "store_name" => @$dealer->store_name,
                        "order_amount" => number_format(@$order->total_amount),
                        "order_id"  =>$order->id,
                        "received_amount" => "None",
                        "confirmed_by"=> "None",
                    )
                );

                $response = Http::withHeaders($headers)->post($eventApiURL, $postEventInput);
                $responseBody = json_decode($response->getBody(), true);
            }
        
        }
        if(!empty($dealer))
        {
            $headers = array(
                "Content-Type" => 'application/json',
                "Authorization"=> 'Basic WmN1a0VfLUJEYmdEZXVnMHhVVlZfYVNueFdsaTE1Z2pHSk12M1pDSjA4QTo='
            );
            $apiURL = 'https://api.interakt.ai/v1/public/track/users/';
            $postInput = array(
                "phoneNumber"=> @$dealer->phone,
                "countryCode"=> "+91",
                "traits"=> array(
                    "name"=> @$dealer->name,
                    "phoneNumber"=> @$dealer->phone,
                    "orderId" => @$order->id,
                    "dueDay"=> 2,
                    "orderUrl" => "http://dds.sangamalmirah.in/public/invoice/".@$order->pi_invoice,
                )
            );
            $response = Http::withHeaders($headers)->post($apiURL, $postInput);
            $responseBody = json_decode($response->getBody(), true);
            if($responseBody['result'])
            {
                $eventApiURL = 'https://api.interakt.ai/v1/public/track/events/';
                $postEventInput = array(
                    "phoneNumber"=> @$dealer->phone,
                    "countryCode"=> "+91",
                    "event"=> "Order Placed DD",
                    "traits"=> array(
                       "orderId" => @$order->id,
                        "dueDay"=> 2,
                        "orderUrl" => "http://dds.sangamalmirah.in/public/invoice/".@$order->pi_invoice,
                    )
                );

                $response = Http::withHeaders($headers)->post($eventApiURL, $postEventInput);
                $responseBody = json_decode($response->getBody(), true);
            }
        }
    }elseif($order->status == 6){
        $admin = User::where(["id"=>1])->first();
        $newUser = User::where(["id"=>$order->updated_by])->first();
        $dealer = DealerProfile::where(["id"=>$order->store_id])->first();
        $receive_amt = Transaction::where(["order_id"=>$order->id])->first();
        $headers = array(
            "Content-Type" => 'application/json',
            "Authorization"=> 'Basic WmN1a0VfLUJEYmdEZXVnMHhVVlZfYVNueFdsaTE1Z2pHSk12M1pDSjA4QTo='
        );
        $apiURL = 'https://api.interakt.ai/v1/public/track/users/';
        $postInput = array(
            "phoneNumber"=> @$dealer->phone,
            "countryCode"=> "+91",
            "traits"=> array(
                "name"=> $dealer->name,
                "phoneNumber"=> $dealer->phone,
                "order_id" => @$order->id,
            )
        );
        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        $responseBody = json_decode($response->getBody(), true);
        if($responseBody['result'])
        {
            $eventApiURL = 'https://api.interakt.ai/v1/public/track/events/';
            $postEventInput = array(
                "phoneNumber"=> $dealer->phone,
                "countryCode"=> "+91",
                "event"=> "Payment Confirmation",
                "traits"=> array(
                   "order_id" => @$order->id,
                )
            );

            $response = Http::withHeaders($headers)->post($eventApiURL, $postEventInput);
            $responseBody = json_decode($response->getBody(), true);
        }

        $postInput = array(
            "phoneNumber"=> $admin->mobile,
            "countryCode"=> "+91",
            "traits"=> array(
                "name"=> $admin->name,
                "phoneNumber"=> $admin->mobile,
                "dd_state" => $states[@$dealer->state_id],
                "dd_city"=> $cities[@$dealer->city_id],
                "store_name" => @$dealer->store_name,
                "order_amount" => number_format(@$order->total_amount),
                "received_amount" => number_format(@$receive_amt->received_amount),
                "confirmed_by"=> @$newUser->name,
            )
        );
        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        $responseBody = json_decode($response->getBody(), true);
        if($responseBody['result'])
        {
            $eventApiURL = 'https://api.interakt.ai/v1/public/track/events/';
            $postEventInput = array(
                "phoneNumber"=> $admin->mobile,
                "countryCode"=> "+91",
                "event"=> "Order Placed",
                "traits"=> array(
                    "dd_state" => $states[@$dealer->state_id],
                    "dd_city"=> $cities[@$dealer->city_id],
                    "store_name" => @$dealer->store_name,
                    "order_amount" => number_format(@$order->total_amount),
                    "received_amount" => $receive_amt->received_amount,
                    "confirmed_by"=> @$newUser->name,
                )
            );

            $response = Http::withHeaders($headers)->post($eventApiURL, $postEventInput);
            $responseBody = json_decode($response->getBody(), true);
        }
    }elseif ($order->status == 8) {
        // dd("Hello");
        $admin = User::where(["id"=>1])->first();
        $newUser = User::where(["id"=>$order->updated_by])->first();
        $dealer = DealerProfile::where(["id"=>$order->store_id])->first();
        $allData = Shipment::select("shipments.*","transpotations.name as t_name","transpotations.mobile as t_mobile","transpotations.vehicle_number")->join("transpotations","transpotations.id","=","shipments.delivery_by")->where(["shipments.order_id"=>$order->id])->first();

        $headers = array(
            "Content-Type" => 'application/json',
            "Authorization"=> 'Basic WmN1a0VfLUJEYmdEZXVnMHhVVlZfYVNueFdsaTE1Z2pHSk12M1pDSjA4QTo='
        );
        $apiURL = 'https://api.interakt.ai/v1/public/track/users/';
        $postInput = array(
            "phoneNumber"=> $dealer->phone,
            "countryCode"=> "+91",
            "traits"=> array(
                "name"=> @$dealer->name,
                "phoneNumber"=> @$dealer->phone,
                "order_id" => @$order->id,
                "delivery_by" => @$allData->t_name,
                "d_mobile" => @$allData->t_mobile,
                "vehicle_number" =>@$allData->vehicle_number,
                "otp" =>@$allData->otp,
            )
        );
        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        $responseBody = json_decode($response->getBody(), true);
        if($responseBody['result'])
        {
            $eventApiURL = 'https://api.interakt.ai/v1/public/track/events/';
            $postEventInput = array(
                "phoneNumber"=> $dealer->phone,
                "countryCode"=> "+91",
                "event"=> "Order Dispatch",
                "traits"=> array(
                    "order_id" => @$order->id,
                    "delivery_by" => @$allData->t_name,
                    "d_mobile" => @$allData->t_mobile,
                    "vehicle_number" =>@$allData->vehicle_number,
                    "otp" =>@$allData->otp,
                )
            );

            $response = Http::withHeaders($headers)->post($eventApiURL, $postEventInput);
            $responseBody = json_decode($response->getBody(), true);
        }
        // dd($postInput,$postEventInput);
    }elseif ($order->status == 3) {
        # code...
    }
}

function getAttributeCombination($arr = array())
{
    $newAttr = [];
    $parent = [];
    $arrCnt = count($arr);
    if($arrCnt == 2)
    {
        $a = [];
        $b = [];
        foreach ($arr as $key => $value) {
            $newAttr[$key] = $value;
            $a[] = $value['id'];
            $b[] = $value['name'];
        }
        $a = implode('_', $a);
        $b = implode(' ', $b);
        $newAttr[] = ["name"=>$b,"id"=>$a];
    }elseif ($arrCnt == 3) {
        $newAttr[] = ["name"=>"Mirror","id"=>6];
        $newAttr[] = ["name"=>"Inter Lock","id"=>15];
        $newAttr[] = ["name"=>"Shelf Chamber","id"=>16];
        $newAttr[] = ["name"=>"Mirror Inter Lock","id"=>"6_15"];
        $newAttr[] = ["name"=>"Mirror Shelf Chamber","id"=>"6_16"];
        $newAttr[] = ["name"=>"Inter Lock Shelf Chamber" ,"id"=>"15_16"];
        $newAttr[] = ["name"=>"Mirror Inter Lock Shelf Chamber","id"=>"6_15_16"];
    }
    else{
        $newAttr = $arr;
    }
    return $newAttr;
}

function getAllState($list = 0)
{
   if($list == 1)
   {
        $states = State::where(["status"=>"active"])->pluck('name','id')->toArray();
   }else
   {
        $states = State::where(["status"=>"active"])->get();
   }
   return $states;
}

function getStateCity($state_id)
{
    $cities = State::where(["status"=>"active","state_id"=>$state_id])->orderBy("city")->get();
    return $cities;
}

function getAllCities($list = 0)
{
    if($list == 1)
    {
        $cityList = City::where(["status"=>"active"])->pluck('city','id')->toArray();
    }else
    {
        $cityList = City::select("cities.*","states.code")
                ->join("states","states.id","=","cities.state_id")
                ->where(["cities.status"=>"active"])->get();
        // $cityList = [];
        // foreach ($cities as $key => $value) {
        //     $cityList[$value->id] = $value->city.'('.$value->code.')';
        // }
    }
   return $cityList;
}

function getActiveState()
{

}

function getActiveCity()
{

}

function crmStateCityFilter($obj)
{
    $id = Auth::user()->id;
    $user =User::where(["id"=>$id,"status"=>"active"])->first();
    if(!empty($user->city_id))
    {
        $city = explode(',', $user->city_id);
        $queryObj = $obj->whereIn("dealer_profiles.city_id",$city);
    }
    elseif(!empty($user->state_id))
    {
        $state = explode(',', $user->state_id);
        $queryObj =  $obj->whereIn("dealer_profiles.state_id",$state);
    }
    else{
        $queryObj = $obj;
    }
    return $queryObj;
}