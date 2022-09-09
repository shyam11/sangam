<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    public static function getMyOrders($id)
    {
       $orders = Order::select('orders.*','order_status.name')
       			->join("order_status","order_status.id","=","orders.status")
       			->where(["orders.user_id"=>$id])->orderBy('orders.id','desc')->get();
       return $orders;
    }

    public static function getOrderDetails($id)
    {
    	
    }

    public static function updateOrderStatus($order_id,$status)
    {
        Order::where(["id"=>$order_id])->update(["status"=>$status]);
    }
}
