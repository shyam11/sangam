<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\ProductAttribute;
use App\Models\DealerProfile;
use App\Models\Order;
use App\Models\User;
use App\Models\DealerCategory;
use App\Models\OrderItem;
use Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Session;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use App\Models\Account;
use App\Models\City;
use App\Models\Shipment;

class DealerProfileController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dealers = DealerProfile::where(["status"=>"active"])->get();

        return view("dealer.dealer_profile.index",compact('dealers'));
    }

    public function create()
    {

        $users = User::join('role_user','role_user.user_id','=','users.id')->whereIn("role_user.role_id",[4,5])->where(["users.status"=>"active"])->get();
        $dealerCategory = DealerCategory::where(["status"=>"active"])->get();
        $states = getAllState();
        return view("dealer.dealer_profile.create",compact('users','dealerCategory','states'));
    }

    public function store(Request $request)
    {
        $validator = validator()->make(request()->all(), [
            'user_id' => 'required|unique:dealer_profiles,user_id'
        ]);

        if ($validator->fails())
        {
            redirect()->back()->with('error', ['your message here']);
        }
        $dealer = new DealerProfile;
        $dealer->user_id = $request->user_id;
        $dealer->name = $request->name;
        $dealer->email = $request->email;
        $dealer->phone = $request->phone;
        $dealer->address = $request->address;
        // $dealer->state_name = $request->state;
        // $dealer->city_name = $request->city;
        $dealer->state_id = $request->state_id;
        $dealer->city_id = $request->city_id;
        $dealer->target = $request->target;
        $dealer->gst_no = $request->gst_no;
        $dealer->registered_address = $request->registered_address;
        $dealer->store_name = $request->store_name;
        $dealer->pincode =$request->pincode;
        $dealer->dealer_category = $request->dealer_category;
        $dealer->registration_date = $request->registration_date;
        $dealer->status = $request->status;
        $dealer->created_by = Auth::user()->id;
        $dealer->save(); 

        $account = new Account;
        $account->user_id = $request->user_id;
        $account->outstanding = 0;
        $account->total_amount = 0;
        $account->paid_amount = 0;
        $account->status = $request->status;
        $account->created_by = Auth::user()->id;
        $account->store_id = $dealer->id;
        $account->save();
        return redirect()->route('admin.dealer-profile');
    }

    public function show(DealerProfile $dealer,$id)
    {
        $dealer = DealerProfile::where(["id"=>$id,"status"=>"active"])->first();
        $users = User::join('role_user','role_user.user_id','=','users.id')->whereIn("role_user.role_id",[4,5])->where(["users.status"=>"active"])->get();
        $dealerCategory = DealerCategory::where(["status"=>"active"])->get();

        return view('dealer.dealer_profile.show',compact('dealer','users','dealerCategory'));
    }

    public function edit(DealerProfile $dealer,$id)
    {
        $dealer = DealerProfile::where(["id"=>$id,"status"=>"active"])->first();
        $users = User::join('role_user','role_user.user_id','=','users.id')->whereIn("role_user.role_id",[4,5])->where(["users.status"=>"active"])->get();
        $dealerCategory = DealerCategory::where(["status"=>"active"])->get();
        $states = getAllState();
        $cities = getAllCities();
        return view('dealer.dealer_profile.edit',compact('dealer','users','dealerCategory','states','cities'));
    }

    public function update(Request $request)
    {
        $data = [
        "name" => $request->name,
        "email" => $request->email,
        "phone" => $request->phone,
        "address" => $request->address,
        "state_id" => $request->state_id,
        "city_id" => $request->city_id,
        "target" => $request->target,
        "gst_no" => $request->gst_no,
        "registered_address" => $request->registered_address,
        "store_name" => $request->store_name,
        "pincode" => $request->pincode,
        "dealer_category" => $request->dealer_category,
        "registration_date" => $request->registration_date,
        "status" => $request->status,
        "updated_by" => Auth::user()->id,
        ];
        DealerProfile::where(["id"=>$request->id])->update($data);

        // $data = [
        //     "status" => $request->status,
        //     "updated_by" => Auth::user()->id,
        // ];
        // Account::where(["id"=>$request->id])->update($data);
        return redirect()->route('admin.dealer-profile');
    }

    public function destroy($id)
    {
        $dealerProfile = DealerProfile::find($id);
        $dealerProfile->delete();
        return redirect()->route('admin.dealer-profile');
    }

    public function ddOrders()
    {
        $data = Session::get('id_percentage_store');
        $id_percentage = explode('_',$data);
        $id = $id_percentage[0];

        $month = date('m');
        $orders = Order::getMyOrders($id);
        return view('dealer.dealer_profile.my_orders', compact('orders'));
    }

    public function viewDetail($order_id)
    {
        $data = Session::get('id_percentage');
        $id_percentage = explode('_',$data);
        $id = $id_percentage[0];

        $orderDetails = OrderItem::select('order_items.*','product_models.title')
                ->join("product_models","product_models.id","=","order_items.product_id")
                ->where(["order_items.order_id"=>$order_id,"dd_id"=>$id])->get();
        return view("dealer.dealer_profile.order_detail",compact('orderDetails'));

    }

    public function getCity(Request $request)
    {
        $cities = City::where(["state_id"=>$request->state_id,"status"=>"active"])->get()->toArray();
        return $cities;
    }

    public function otpView($order_id)
    {
        $orderDetail = Order::where(["id"=>$order_id])->first();
        $dds = DealerProfile::where(["id"=>$orderDetail->store_id])->first();
        $shipment = Shipment::where(["order_id"=>$order_id])->first();
        return view('dealer.dealer_profile.dds_otp',compact('orderDetail','dds','shipment'));
    }

    public function verifyOtp(Request $request)
    {
        $shipment = Shipment::where(["id"=>$request->shipment_id])->first();
            
        $otp = $request->otp;
        if($shipment->otp == $otp)
        {
            Shipment::where(["id"=>$request->shipment_id])->update(["shipping_status"=>"deleverd"]);
            Order::updateOrderStatus($request->order_id,$request->status);
        }else
        {
            session()->flash('error', 'Please enter correct OTP.');
            return redirect()->back();
        }
        return redirect()->route('admin.ddorders');
    }
}
