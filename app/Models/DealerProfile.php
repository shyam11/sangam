<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class DealerProfile extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'dealer_profiles';

    public static function dealerCategory($id = null)
    {
        // return $this->hasOne('App\Models\DealerCategory', 'id', 'id');
        $dealerCategory = DealerProfile::select('dealer_profiles.*','dealer_categories.percentage as percentage_dealer')
            ->join('dealer_categories','dealer_categories.id','=','dealer_profiles.dealer_category')
            ->where(["dealer_profiles.status"=>"active"]);
            if(!is_null($id))
            {
                $dealerCategory->where(["dealer_profiles.user_id"=>$id]);
                $dealerCategory = $dealerCategory->first();
            }else{
                $dealerCategory = $dealerCategory->get();
            }
        return $dealerCategory;
    }

    public static function getcrmDD($state = array(), $city = array())
    {
        $getcrmdd = DealerProfile::select('dealer_profiles.*','dealer_categories.percentage as percentage_dealer')
            ->join('dealer_categories','dealer_categories.id','=','dealer_profiles.dealer_category');
           
            $cnt = DealerProfile::isDDs(Auth::user()->id);
            if($cnt > 0)
            {
                $getcrmdd = $getcrmdd->where(["dealer_profiles.user_id"=>Auth::user()->id]);
            }else{
                $getcrmdd = crmStateCityFilter($getcrmdd);
            }
            $getcrmdd = $getcrmdd->get();
            // dd($getcrmdd);
        return $getcrmdd;
    }

    public static function isDDs($user_id)
    {
        $idDd = \DB::table("role_user")->where(["user_id"=>$user_id])->whereIn('role_id',[4,5])->get()->count();
        return $idDd;
    }

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function getAllDealers($id = null)
    {
        return DealerProfile::where(["status"=>"active"])->get();
    }

    public function scopeFilterDealerProfile($query)
    {
        $query->when(request()->input('priority'), function($query) {
                $query->whereHas('priority', function($query) {
                    $query->whereId(request()->input('priority'));
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
}
