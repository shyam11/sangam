<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'product_attributes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function getProductAttribute($pid)
    {
        $product_attribute = ProductAttribute::where(["parent_id"=>$pid])->get()->pluck('name','id')->toArray();
        return $product_attribute;
    }

    public static function getAttributePrice()
    {
        $getAttributePrice = ProductAttribute::whereNotNull('price')->get()->pluck('price','id')->toArray();
        return $getAttributePrice;
    }

    public static function getAttributeList($id = array())
    {
        // $ids = str_replace('_',',',$id);
       $productArrtibutes = ProductAttribute::whereIn('id',$id)->orderBy('parent_id')->get()->pluck('name')->toArray();
       $model_name  = implode(' ',$productArrtibutes);
       return $model_name;

    }
}
