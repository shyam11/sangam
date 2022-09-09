<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductAttribute;

class ProductModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'product_models';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeFilterModels($query)
    {
        $query->when(request()->input('title'), function($query) {
                $query->whereHas('priority', function($query) {
                    $query->whereId(request()->input('title'));
                });
            })
            ->when(request()->input('category'), function($query) {
                $query->whereHas('category', function($query) {
                    $query->whereId(request()->input('category'));
                });
            })
            ->when(request()->input('status'), function($query) {
                $query->whereHas('status', function($query) {
                    $query->whereId(request()->input('status'));
                });
            })
            ->when(request()->input('assigned_to_user_id'), function($query) {
                $query->whereHas('assigned_to_user', function($query) {
                    $query->whereId(request()->input('assigned_to_user_id'));
                });
            });
    }

    public static function getProductModel($cat = null, $id = null)
    {
        $str = str_replace("-"," ", $cat);

        $data = array();
        if(!is_null($id))
        {
            $data = ProductModel::where(["id"=>$id,"status"=>"active","category"=>$str])->first();
        }else{
            $data = ProductModel::where(["status"=>"active","category"=>$str])->get();
        }
        return $data;
    }

    public function getProductCategory()
    {
        // SELECT p.id,p.name,p.parent_id FROM `product_attributes` p JOIN product_attributes q on p.parent_id=q.id WHERE q.name="Color";
        // $colors = ProductAttribute::select('p.id','p.name','p.parent_id')
        //         ->join('product_attributes as q','q.id','=','p.parent_id')
        //         ->where(['q.name'=>"Color"])
        //         ->get();\
        $colors = \DB::select('SELECT p.id,p.name,p.parent_id FROM `product_attributes` p JOIN product_attributes q on p.parent_id=q.id WHERE q.name="Color"');
        return $colors;
    }

    public static function getAttributeVariant($name)
    {
        $colors = \DB::select('SELECT p.id,p.name,p.parent_id FROM `product_attributes` p JOIN product_attributes q on p.parent_id=q.id WHERE q.name="Color"');
        return $colors;
    }
}
