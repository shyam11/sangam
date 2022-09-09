<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Session;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use App\Models\Transportation;

class TransportController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $transports = Transportation::where(["status"=>"active"])->get();

        return view("dealer.transport.index",compact('transports'));
    }

    public function create()
    {
        return view("dealer.transport.create");
    }

    public function store(Request $request)
    {
        $transport = new Transportation;
        $transport->name = $request->name;
        $transport->mobile = $request->mobile;
        $transport->vehicle_type = $request->vehicle_type;
        $transport->vehicle_number = $request->vehicle_number;
        $transport->vehicle_owner = $request->vehicle_owner;
        $transport->created_by = Auth::user()->id;
        $transport->status = $request->status;
        $transport->save(); 
        return redirect()->route('admin.transport');
    }

    public function show(DealerProfile $dealer,$id)
    {
        $dealer = DealerProfile::where(["id"=>$id,"status"=>"active"])->first();
        $users = User::join('role_user','role_user.user_id','=','users.id')->where(["role_user.role_id"=>4,"users.status"=>"active"])->get();
        $dealerCategory = DealerCategory::where(["status"=>"active"])->get();

        return view('dealer.dealer_profile.show',compact('dealer','users','dealerCategory'));
    }

    public function edit($id)
    {
        $transport = Transportation::where(["id"=>$id,"status"=>"active"])->first();
        return view('dealer.transport.edit',compact('transport'));
    }

    public function update(Request $request)
    {
        $data = [
        "name" => $request->name,
        "mobile" => $request->mobile,
        "vehicle_type" => $request->vehicle_type,
        "vehicle_number" => $request->vehicle_number,
        "vehicle_owner" => $request->vehicle_owner,
        "status" => $request->status,
        "updated_by" => Auth::user()->id,
        ];
        Transportation::where(["id"=>$request->id])->update($data);
        return redirect()->route('admin.transport');
    }

    public function destroy($id)
    {
        $dealerProfile = DealerProfile::find($id);
        $dealerProfile->delete();
        return redirect()->route('admin.dealer-profile');
    }
}
