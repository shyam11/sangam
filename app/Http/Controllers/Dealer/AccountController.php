<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use Auth;

class AccountController extends Controller
{
    public function index()
    {
    	if(auth()->user()->isDealer() || auth()->user()->isDistributor())
    	{
    		 $Accounts = Account::select("accounts.*","dealer_profiles.name","dealer_profiles.email","dealer_profiles.state_id","dealer_profiles.city_id","dealer_profiles.store_name","roles.title as user_type")
        	->join("dealer_profiles","dealer_profiles.id","=","accounts.store_id")
        	->join("role_user","role_user.user_id","=","dealer_profiles.user_id")
        	->join("roles","roles.id","=","role_user.role_id")
        	->where(["accounts.user_id"=>Auth::user()->id])
        	->orderBy("accounts.outstanding")->get();
    	}else
    	{
    		$Accounts = Account::select("accounts.*","dealer_profiles.name","dealer_profiles.email","dealer_profiles.state_id","dealer_profiles.city_id","dealer_profiles.store_name","roles.title as user_type")
        	->join("dealer_profiles","dealer_profiles.id","=","accounts.store_id")
        	->join("role_user","role_user.user_id","=","dealer_profiles.user_id")
        	->join("roles","roles.id","=","role_user.role_id")
        	->orderBy("accounts.outstanding");
            $Accounts = crmStateCityFilter($Accounts);
            $Accounts = $Accounts->get();
    	}
        return view("dealer.account.index",compact('Accounts'));
    }

    public function show($id)
    {
    	$transactions = Transaction::select("transactions.*","users.name")
                    ->join("users","users.id","=","transactions.created_by")
                    ->where(["transactions.status"=>"complete","transactions.store_id"=>$id])
                    ->orderBy("transactions.id","desc")
                    ->get();
        return view("dealer.transaction.index",compact('transactions'));
    }
}
