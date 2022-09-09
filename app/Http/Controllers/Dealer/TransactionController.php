<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Gate;
use Response;
use App\Models\DealerProfile;
use Auth;
use App\Models\Account;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
       $transactions = Transaction::select("transactions.*","users.name","dealer_profiles.store_name")
                    ->join("users","users.id","=","transactions.created_by")
                    ->join("dealer_profiles","dealer_profiles.id","transactions.store_id")
            ->where(["transactions.status"=>"complete"])->orderBy("transactions.created_at","desc")->get();
        return view("dealer.transaction.index",compact('transactions'));
    }

    public function create()
    {
    	$users = DealerProfile::where(["status"=>"active"])->get();
        return view("dealer.transaction.create",compact('users'));
    }

    public function store(Request $request)
    {
        $user = explode('_', $request->user_id);
        $Transaction = new Transaction;
        $Transaction->user_id = $user[0];
        $Transaction->store_id = $user[1];
        $Transaction->transaction_number = $request->transaction_number;
        $Transaction->transaction_type = $request->transaction_type;
        if($request->type == "debit")
        {
            $Transaction->total_amount = $request->amount;
            $Transaction->balance_amount = 0 - $request->amount;
        }else
        {
            $Transaction->received_amount = $request->amount;
            $Transaction->balance_amount = $request->amount;
        }

        $Transaction->status = "complete";
        $Transaction->created_by = Auth::user()->id;
        $Transaction->remark = $request->remark;
        $Transaction->save(); 

        $sum = Account::updateAccount($user[1]);
        return redirect()->route('admin.transactions');
    }
}
