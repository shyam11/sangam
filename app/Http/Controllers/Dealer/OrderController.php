<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeGrouping;
use App\Models\ProductModel;
use App\Models\Cart;
use App\Models\DealerProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Session;
use Auth;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $cat = request()->segment(count(request()->segments()));
        // if(auth()->user()->isDealer())
        // {
            $id = Auth::user()->id;
            $dealer = DealerProfile::dealerCategory($id);
            $cnt = DealerProfile::isDDs($id);
            if($cnt != 0)
            {
            // dd($dealer->id);
            if(!Session::get('id_percentage_store'))
            {
                Session::put("id_percentage_store",$dealer->user_id.'_'.$dealer->percentage_dealer.'_'.$dealer->id);
            }
           }
        // }
        $attributes = ProductAttribute::getProductAttribute(2);
        $models = ProductModel::getProductModel($cat);
        $modelData = [];
        $getDD = DealerProfile::getcrmDD();
        // dd(getAttributeCombination());
        // dd(getAttributeCombination(ProductAttributeGrouping::getAttributeMapping(3,3)));
        foreach($models as $key => $value)
        {
            $color = ProductAttributeGrouping::getAttributeMapping($value['id'],1);
            $bodycolor = ProductAttributeGrouping::getAttributeMapping($value['id'],2);
            $variant = ProductAttributeGrouping::getAttributeMapping($value['id'],3);
            // dd($variant);
            $variant = getAttributeCombination($variant);
            // dd($variant);
            $modelData[$key] = ['model'=> $value,'color'=>$color,'bodycolor'=>$bodycolor,'variant'=>$variant];
        }
        return view('dealer.order.index',compact('modelData','getDD'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if(empty($request->color) || empty($request->bodycolor) || empty($request->qty))
        {
            return redirect()->back()->with('error', 'Door Color, Body Color, and Quantity are required.');
        }
        $data = Session::get('id_percentage_store');
        $dd_id = explode('_',$data);
        $dealer_id = $dd_id[0];
        $store_id = $dd_id[2];
        Cart::addToCart($request->all(),$dealer_id,$store_id,"incart");
        // session()->flash('message', 'Product added to cart.');
        return redirect()->back();
    }

    public function cart()
    {
        $data = Session::get('id_percentage_store');
        $dd_id = explode('_',$data);
        // $id = $dd_id[0];
        $id = $dd_id[2];
        $carts = Cart::where(["store_id"=>$id,"status"=>"incart"])->get();
        $dealer = DealerProfile::where(["id"=>$id])->first();
        $models = ProductModel::where(["status"=>"active"])->get();
        return view('dealer.order.cart_list',compact('carts','models','dealer'));
    }

    public function checkout(Request $request)
    {
        $quantity = $request->total_quantity;
        $vechile = $request->vechile;
        $flag = 0;
        if($vechile == 1 && ($quantity == 9 || $quantity == 10))
        {
            $flag =1;
        }elseif($vechile == 2 && ($quantity >= 22 && $quantity <= 28))
        {
            $flag =1;
        }else{
            return redirect()->back()->with('error', 'Your Order Quantity will be same as Transport capacity.');
        }
        if($flag == 1)
        {
            $data = Session::get('id_percentage_store');
            $id_percentage = explode('_',$data);
            $id = $id_percentage[0];
            $percentage = $id_percentage[1];
            $store_id = $id_percentage[2];
            
            $order = new Order;
            $order->user_id = $id;
            $order->status = 1;
            $order->store_id = $store_id;
            $order->created_by = Auth::user()->id;
            $order->save();

            Session::put("order_id",$order->id);
            $carts = Cart::where(['user_id'=>$id,"status"=>"incart"])->get();
            $total_amt = 0;
            $quantity = 0;
            foreach($carts as $cart)
            {
                $total_amt += $cart->amount;
                $quantity += $cart->quantity;
                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cart->product_id;
                $orderItem->product_name = $cart->product_name;
                $orderItem->attribute = $cart->attribute;
                $orderItem->dd_id = $cart->user_id;
                $orderItem->price = $cart->price;
                $orderItem->dd_price = $cart->dd_price;
                $orderItem->attr_price = $cart->attribute_price;
                $orderItem->quantity = $cart->quantity;
                $orderItem->amount = $cart->amount;
                $orderItem->category = $cart->category;
                $orderItem->status = "open";
                $orderItem->save();
            }
            $dealer = DealerProfile::where(["id"=>$store_id])->first();
            $gst_type = "";
            $gst_percentage = "";
            $gst_amount = 0;
            if(@$dealer->state_id == "4")
            {
                $gst_type = "SGST/CGST";
                $gst_percentage = "9+9";
                $gst_amount = ($total_amt * 18)/100;
            }else
            {
                $gst_type = "IGST";
                $gst_percentage = "18";
                $gst_amount = ($total_amt * 18)/100;;
            }
            $current_date = date('Y-m-d');

            $dataArr = [
                "percentage" => $percentage,
                "subtotal" => $total_amt,
                "total_amount" => $total_amt + $gst_amount,
                "quantity" => $quantity,
                "gst_type" => $gst_type,
                "gst_percentage" => $gst_percentage,
                "gst_amount" => $gst_amount,
                "order_expiry" => date('Y-m-d', strtotime($current_date. ' + 2 days')),
            ];
            Order::where(["id"=>$order->id])->update($dataArr);
            Cart::where(["store_id"=>$store_id])->delete();

            $piData['itemOrders'] = OrderItem::select('order_items.*','product_models.title')
                ->join('product_models','order_items.product_id','=','product_models.id')
                ->where(["order_items.order_id"=>$order->id])
                ->get();
            $piData['order'] = Order::where(["id"=>$order->id])->first();
            $piData['dds'] = DealerProfile::where(["id"=>$store_id])->first();
            $piData['invoice_title'] = "Proforma Invoice";
        
            $path = public_path('invoice/');
            $fileName =  'PI'.'_'.$order->id.'_'.date('Y-m-d'). '.' . 'pdf' ;
            $pdf = PDF::loadView('pdf.invoice', $piData);
            $pdf->save($path . '/' . $fileName);
            Order::where(["id"=>$order->id])->update(["pi_invoice" => $fileName]);
            $itemOrders = OrderItem::select('order_items.*','product_models.title','product_models.category')
                ->join('product_models','order_items.product_id','=','product_models.id')
                ->where(["order_items.order_id"=>$order->id])
                ->get();
                
            $dds = DealerProfile::where(["id"=>$store_id])->first();
            $orderDetail = Order::where(["id"=>$order->id])->first();
            
            $wp = sendWhatsappMessage($orderDetail);
            $cnt = DealerProfile::isDDs($id);
            if($cnt == 0)
            {
                Session::forget('id_percentage_store');
            }
            return view('dealer.order.order_invoice',compact('itemOrders','dds'));
        }
    }

    public function updateCartItems(Request $request)
    {
        $cart = Cart::where(["id"=>$request->id])->first();
        $model = ProductModel::find($request->model_id);
        $qty = $request->qty;
        $data = [
            "quantity"=>$qty,
            "amount"=> ($cart->dd_price + $cart->attribute_price) * $qty,
        ];
        Cart::where(["id"=>$request->id])->update($data);
        return "Product Updated successfully.";
    }

    public function destroy(Request $request)
    {
        $cartitem = Cart::find($request->id);
        $cartitem->delete();
        return "Product Removed From Cart.";
    }

    public function getorders()
    {
        $id = Auth::user()->id;
        $user = User::where(["id"=>$id])->first();
        $city = explode(',',$user->work_location);
        $state = explode(',', $user->state);
        $segment = request()->segment(count(request()->segments()));
        $order = Order::select("orders.*","dealer_profiles.name","order_status.name as order_status")
                ->join("dealer_profiles","dealer_profiles.id","=","orders.store_id")
                ->join("order_status","order_status.id","=","orders.status")
                ->orderBy("orders.id","DESC");
                if($segment == "production")
                {
                  $order = $order->where(["orders.status"=>6]);  
                }
                if($segment == "logistic")
                {
                  $order = $order->where(["orders.status"=>7]);  
                }
                $orders = crmStateCityFilter($order);
                $orders = $orders->get();
        return view('dealer.order.orders', compact('orders'));
    }

    public function getAttrGroup(Request $request)
    {
        $getAttrs = ProductAttributeGrouping::select("product_attributes.id","product_attributes.name","product_attribute_groupings.parent_id")
                    ->join("product_attributes","product_attributes.id","=","product_attribute_groupings.attribute_id")->where(["product_attribute_groupings.model_id"=>$request->model])->get()->toArray();
        return $getAttrs;
    }

    public function addCrmDD(Request $request)
    {   
        if(!Session::get('id_percentage_store'))
        {
            Session::put("id_percentage_store",$request->dd_id);
        }else{
            Session::put("id_percentage_store",$request->dd_id);
        }
         return redirect()->back();
    }

    public function viewDetailed($order_id)
    {
        $order = Order::where(["id"=>$order_id])->first();
        $dds = DealerProfile::where(["user_id"=>$order->user_id])->first();
        $orderDetails = OrderItem::select('order_items.*','product_models.title')
                ->join("product_models","product_models.id","=","order_items.product_id")
                ->where(["order_items.order_id"=>$order_id])->get();
        return view("dealer.order.order_detail",compact('orderDetails','dds','order'));
    }

    public function updateOrderItems(Request $request)
    {
        if(!empty($request->freeze))
        {
            $order_item = OrderItem::where(["id"=>$request->id])->first();
            $qty = $request->qty;
            $data = [
                "status" => "freeze",
            ];
            OrderItem::where(["id"=>$request->id])->update($data);
        }
        else{
            $order_item = OrderItem::where(["id"=>$request->id])->first();
            $qty = $request->qty;
            $data = [
                "quantity" => $qty,
                "amount" => ($order_item->dd_price  + $order_item->attr_price) * $qty,
                "status" => "updated",
            ];
           
            OrderItem::where(["id"=>$request->id])->update($data);

            $orderItem = OrderItem::select(\DB::raw('sum(amount) as total_amount'), \DB::raw('sum(quantity) as total_quantity'))->where(["order_id"=>$request->order])->groupBy('order_id')->first();
            $order = Order::where(["id"=>$request->order])->first();
            $gst_amount = 0;
           
            $gst_amount = ($orderItem->total_amount * 18)/100;

            $dataArr = [
                "subtotal" => $orderItem->total_amount,
                "total_amount" => $orderItem->total_amount + $gst_amount,
                "quantity" => $orderItem->total_quantity,
                "gst_amount" => $gst_amount,
            ];
            Order::where(["id"=>$request->order])->update($dataArr);
            // Order::where(["id"=>$request->order])->update(["subtotal"=>$orderItem->total_amount,"total_amount"=>$orderItem->total_amount,"quantity"=>$orderItem->total_quantity]);
        }
        return "Product Updated successfully.";
    }

    public function changeOrderStatus(Request $request)
    {
        if($request->status == 01)
        {
            Order::where(["id"=>$request->order_id])->update(["order_expiry"=>$request->new_date]);
        }
        elseif($request->status == 6)
        {
            $validated = $request->validate([
                'received_amount' => 'required',
                'received_amount' => 'required',
                'remark' => 'required'
            ]);
            $order = Order::where(["id"=>$request->order_id])->first();
            $transaction = new Transaction;
            $transaction->user_id = $request->dd_id;
            $transaction->order_id = $request->order_id;
            $transaction->transaction_number = $request->transaction;
            $transaction->transaction_type = $request->payment_method;
            $transaction->received_amount = $request->received_amount;
            $transaction->total_amount = $request->total_amount;
            $transaction->balance_amount = $request->received_amount - $request->total_amount;
            $transaction->remark = $request->remark;
            $transaction->status = "complete";
            $transaction->store_id = $order->store_id;
            $transaction->created_by = Auth::user()->id;
            $transaction->save();
            Order::updateOrderStatus($request->order_id,$request->status);
            $order = Order::where(["id"=>$request->order_id])->first();
            $wp = sendWhatsappMessage($order);
            Account::updateAccount($order->store_id);
        }elseif($request->status == 7)
        {
            Order::updateOrderStatus($request->order_id,$request->status);
        }
        return Redirect::to('admin/getorders');
    }

}
