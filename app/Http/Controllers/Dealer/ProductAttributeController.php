<?php

namespace App\Http\Controllers\Dealer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Session;
use Carbon\Carbon;

class ProductAttributeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $productAttributes = ProductAttribute::select("product_attributes as c.*","p.name","users.name as user_name")
        //     ->join("product_attributes as p","p.id","=","c.parent_id")
        //     ->join("users","users.id","=","c.created_by")
        //     ->get();
        $productAttributes = \DB::select("select p.name as parent_name, c.* from product_attributes c inner join product_attributes p on p.id = c.parent_id inner join users on users.id = c.created_by");
        return view("dealer.product_attribute.index",compact('productAttributes'));
    }

    public function create()
    {
        // $results = getCategoryById();
        return view("dealer.product_attribute.create");
    }

    public function store(Request $request)
    {
        $data= new ProductAttribute();

        $data->parent_id = $request->parent_id;
        $data->name = $request->name;
        $data->status = $request->status;
        $data->price = $request->price;
        $data->created_by = Auth::user()->id;
        $data->save();
        return redirect()->route('admin.product-attributes');
    }

    public function edit($id)
    {
        $attribute = ProductAttribute::where(["id"=>$id,"status"=>"active"])->first();
        return view('dealer.product_attribute.edit', compact('attribute'));
    }

    public function show($id)
    {
        $attribute = ProductAttribute::where(["id"=>$id])->first();
        return view('dealer.product_attribute.show', compact('attribute'));
    }

    public function update(Request $request)
    {
        $data = [
            "parent_id" => $request->parent_id,
            "price" => $request->price,
            "name" => $request->name,
            "status" => $request->status,
        ];
        ProductAttribute::where(["id"=>$request->id])->update($data);
        return redirect()->route('admin.product-attributes');
    }

    public function destroy($id)
    {
        $attribute = ProductAttribute::find($id);
        $attribute->delete();
        return back();
    }
}
