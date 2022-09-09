<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeGrouping extends Model
{
    use HasFactory;

    public $table = 'product_attribute_groupings';

    public static function getAttributeMapping($model_id,$parent_id)
    {
        $attribute_grouping = ProductAttributeGrouping::select('product_attributes.name','product_attributes.id')
        ->join('product_attributes','product_attribute_groupings.attribute_id','=','product_attributes.id')
        ->where(["product_attribute_groupings.model_id"=>$model_id,"product_attribute_groupings.parent_id"=>$parent_id])->get()->toArray();
        return $attribute_grouping;
    }

    public static function getAttributePrice($id = array(),$model_id)
    {
       $productArrtibutePrice = ProductAttributeGrouping::whereIn('attribute_id',$id)->where(["model_id"=>$model_id])->get()->sum('price');
       return $productArrtibutePrice;

    }
}
