<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DealerProfile;
use App\Models\Transportation;
use App\Models\Shipment;
use Auth;
use Redirect;
use Session;
use \PDF;

class ShipmentController extends Controller
{
	public function create($order_id)
	{
		$orderDetail = Order::where(["id"=>$order_id])->first();
		// $dds = DealerProfile::where(["user_id"=>$orderDetail->user_id])->first();
        $dds = DealerProfile::where(["id"=>$orderDetail->store_id])->first();
		$order_items = OrderItem::where(["order_id"=>$order_id])->get();
		$transports = Transportation::where(["status"=>"active"])->get();

		$shipment = Shipment::where(["order_id"=>$order_id])->count();
        if($shipment == 0)
        {
            $shipmnt = new Shipment;
            $shipmnt->name = $dds->name;
            $shipmnt->phone = $dds->phone;
            $shipmnt->store_name = $dds->store_name;
            $shipmnt->address = $dds->address;
            $shipmnt->order_id = $order_id;
            $shipmnt->user_id = $dds->user_id;
            $shipmnt->store_id = $dds->store_id;
            $shipmnt->shipping_status = "shipped";
            $shipmnt->state = $dds->state_name;
            $shipmnt->city = $dds->city_name;
            $shipmnt->save();
        }
        $shipments = Shipment::where(["order_id"=>$order_id])->first();
        
		return view("dealer.shipment.create",compact("orderDetail","dds","order_items","transports","shipments"));
	}
    public function store(Request $request)
    {
    	if($request->status == 8)
    	{
            // random_int(100000, 999999)
    		$data = [
    		"name" =>$request->dd_name,
    		"phone" => $request->dd_mobile,
    		"otp" => random_int(100000, 999999),
    		"store_name"=> $request->store_name,
    		"address"=> $request->address,
    		"order_id"=> $request->order_id,
    		"user_id"=> $request->dd_id,
    		"delivery_by"=> $request->delivered_by,
    		"state"=> $request->state,
    		"city"=> $request->city,
    		"body_number"=> $request->body_number,
    		"created_by"=> Auth::user()->id,
	    	];
	    	Shipment::where(["id"=>$request->shipment_id])->update($data);
	    	Order::updateOrderStatus($request->order_id,$request->status);
            $order = Order::where(["id"=>$request->order_id])->first();

            $piData['itemOrders'] = OrderItem::select('order_items.*','product_models.title','product_models.category')
                    ->join('product_models','order_items.product_id','=','product_models.id')
                    ->where(["order_items.order_id"=>$request->order_id])
                    ->get();
            $piData['order'] = Order::where(["id"=>$request->order_id])->first();
            $piData['dds'] = DealerProfile::where(["id"=>$piData['order']->store_id])->first();
            $piData['invoice_title'] = "Invoice";
            
            $path = public_path('invoice/');
            $fileName =  'Invoice'.'_'.$request->order_id.'_'.date('Y-m-d'). '.' . 'pdf' ;
            $pdf = PDF::loadView('pdf.invoice', $piData);
            $pdf->save($path . '/' . $fileName);
            Order::where(["id"=>$request->order_id])->update(["invoice"=>$fileName]);

            $wp = sendWhatsappMessage($order);
    	}elseif($request->status == 9) {
    		$shipment = Shipment::where(["id"=>$request->shipment_id])->first();
    		
    		$otp = $request->otp;
    		if($shipment->otp == $otp)
    		{
    			Shipment::where(["id"=>$request->shipment_id])->update(["shipping_status"=>"deleverd"]);
	    		Order::updateOrderStatus($request->order_id,$request->status);
    		}else
    		{
    			session()->flash('error', 'Please enter correct OTP.');
    			return redirect()->back();
    		}
    	}
    	
    	return Redirect::to('admin/getorders');
    }
}
