<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Account extends Model
{
    use HasFactory;

    public static function updateAccount($store_id)
    {
    	$sum = Transaction::where(["store_id"=>$store_id])->groupBy('store_id')->sum('balance_amount');
    	Account::where(["store_id"=>$store_id])->update(["outstanding"=>$sum]);
    }
}
