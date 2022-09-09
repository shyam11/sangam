<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModel;
use App\Models\ProductAttribute;
use Auth;
use Session;

class Cart extends Model
{
    use HasFactory;

    public $table = 'carts';

    public static function addToCart($product,$dealer_id,$store_id,$status ="incart")
    {
        $model = ProductModel::where(["id"=>$product['model']])->first();
        $attr = '';
        
        $data = Session::get('id_percentage_store');
        $store_id = 0;
        if(!empty($data) && isset($data))
        {
            $arr = explode('_',$data);
            $percentage = $arr[1];
            $store_id = $arr[2];
        }
        $state = getStoreState();
        $mrp = getOfferPrice($model->price,$state);

        if(!empty($product['variant']))
        {
            if(!empty($product['color'][0]))
            {
                $attr = $product['color'][0];
            }
            if(!empty($product['bodycolor'][0]))
            {
                $attrbody = $product['bodycolor'][0];

                $attr = $attr.'_'.$attrbody;
            }
            foreach($product['variant'] as $key => $value)
            {
                if($product['qty'][$value] < 1)
                {
                    // return redirect()->back()->with('error', 'Quantity should be greater than 0.');
                }else{
                    $attr_add = $attr.'_'.$value;
                    $data = explode('_',$attr_add);
                    $result = Cart::where(["product_id"=>$model->id,"attribute"=>$attr_add,"store_id"=>$store_id])->first();
                    if(is_null($result))
                    {
                        $cart = new Cart;
                        $cart->product_id = $product['model'];
                        $cart->product_name = $model->title.' '.ProductAttribute::getAttributeList($data);
                        $cart->attribute = $attr.'_'.$value;
                        $cart->attribute_price = ProductAttributeGrouping::getAttributePrice($data,$model->id);
                        $cart->price = $mrp;
                        $cart->dd_price =  getDDPrice($mrp,0);
                        $cart->status = $status;
                        $cart->quantity = $product['qty'][$value];
                        $cart->amount = (getDDPrice($mrp,0) + ProductAttributeGrouping::getAttributePrice($data,$model->id)) * $product['qty'][$value];
                        $cart->category = @$model->category;
                        $cart->user_id = $dealer_id;
                        $cart->store_id = $store_id;
                        $cart->created_by = Auth::user()->id;
                        $cart->save();
                    }else{
                        $ddArr = [
                            "quantity" => $result->quantity + $product['qty'][$value],
                            "amount" => $result->amount +   (($result->dd_price + $result->attribute_price) * $product['qty'][$value]),
                        ];
                        Cart::where(["product_id"=>$model->id,"attribute"=>$attr_add,"store_id"=>$store_id])->update($ddArr);
                    }
                    session()->flash('message', 'Product added to cart.');
                }
            }
        }
        elseif(!empty($product['bodycolor'])){
            foreach($product['bodycolor'] as $key => $value)
            {
                if($product['qty'][$value] > 0)
                {
                    if(!empty($product['color'][0]))
                    {
                        $attr = $product['color'][0];
                    }

                    $attr_add = $attr.'_'.$value;
                    $data = explode('_',$attr_add);
                    $result = Cart::where(["product_id"=>$model->id,"attribute"=>$attr_add,"store_id"=>$store_id])->first();
                    if(is_null($result))
                    {
                        $cart = new Cart;
                        $cart->product_id = $product['model'];
                        $cart->product_name = $model->title.' '.ProductAttribute::getAttributeList($data);
                        $cart->attribute = $attr_add;
                        $cart->attribute_price =  ProductAttributeGrouping::getAttributePrice($data,$model->id);
                        $cart->price = $mrp;
                        $cart->dd_price =  getDDPrice($mrp,0);
                        $cart->status = $status;
                        $cart->quantity = $product['qty'][$value];
                        $cart->amount = (getDDPrice($mrp,0) + ProductAttributeGrouping::getAttributePrice($data,$model->id)) * $product['qty'][$value];
                        $cart->category = @$model->category;
                        $cart->user_id = $dealer_id;
                        $cart->store_id = $store_id;
                        $cart->created_by = Auth::user()->id;
                        $cart->save();
                        session()->flash('message', 'Product added to cart.');
                    }else
                    {
                        $ddArr = [
                            "quantity" => $result->quantity + $product['qty'][$value],
                            "amount" => $result->amount +   (($result->dd_price + $result->attribute_price) * $product['qty'][$value]),
                        ];
                        Cart::where(["product_id"=>$model->id,"attribute"=>$attr_add,"store_id"=>$store_id])->update($ddArr);
                    }
                    
                }
            }
        }
        elseif(!empty($product['color'])){
            foreach($product['color'] as $key => $value)
            {
                if($product['qty'][$value] < 1)
                {
                    // return redirect()->back()->with('error', 'Quantity should be greater than 0.');
                }else{
                    $attr_add = $attr.'_'.$value;
                    $data = explode('_',$attr_add);

                    $cart = new Cart;
                    $cart->product_id = $product['model'];
                    $cart->product_name = $model->title.'_'.ProductAttribute::getAttributeList($data);
                    $cart->attribute = $attr.'_'.$value;
                    $cart->attribute_price =  ProductAttributeGrouping::getAttributePrice($data,$model->id);
                    $cart->price = $mrp;
                    $cart->dd_price =  getDDPrice($mrp,0);
                    $cart->status = $status;
                    $cart->quantity = $product['qty'][$value];
                    $cart->amount = $product['qty'][$value] * (getDDPrice($mrp,0) + ProductAttributeGrouping::getAttributePrice($data,$model->id));
                    $cart->category = @$model->category;
                    $cart->user_id = $dealer_id;
                    $cart->store_id = $store_id;
                    $cart->created_by = Auth::user()->id;
                    $cart->save();
                    session()->flash('message', 'Product added to cart.');
                }
            }
        }
    }
}
