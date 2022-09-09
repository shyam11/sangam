<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductModel;
use Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Session;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::join('users','products.created_by','=','users.id')->orderBy('id','DESC')
                ->filterProducts($request)
                ->select(sprintf('%s.*', (new Product)->table),'users.name');
            $table = Datatables::of($query);
            $table->editColumn('created_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y'); return $formatedDate; });
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'product_show';
                $editGate      = 'product_edit';
                $deleteGate    = 'product_delete';
                $crudRoutePart = 'products';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->editColumn('model_no', function ($row) {
                return $row->model_no ? $row->model_no : "";
            });
            $table->addColumn('status', function ($row) {
                return $row->status ? ucwords($row->status) : '';
            });
            $table->addColumn('created_by', function ($row) {
                return $row->name ? $row->name : '';
            });
           $table->editColumn('image', function ($row) {
                $url=asset("dealer/products/".$row->image);
                $image = '<img src='.$url.' border="0" width="60" class="img-rounded" align="center" />';
                return $image;
            });

            $table->rawColumns(['actions', 'placeholder', 'created_by','image']);

            return $table->make(true);
        }
        return view("dealer.product.index");
    }

    public function create()
    {
        $models = ProductModel::where(["status"=>"active"])->get();
        return view("dealer.product.create",compact('models'));
    }

    public function store(Request $request)
    {
        $data= new Product();

        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('dealer/products'), $filename);
            $data['image']= $filename;
        }
        $data->title = $request->title;
        $data->model_id = $request->model_id;
        $data->dimension = $request->dimension;
        $data->weight = $request->weight;
        $data->color = $request->color;
        $data->price = $request->price;
        $data->min_stock = $request->min_stock;
        $data->available_stock = $request->available_stock;
        $data->sku = $request->sku;
        $data->description = $request->description;
        $data->status = $request->status;
        $data->created_by = Auth::user()->id;
        $data->save();
        return redirect()->route('admin.products');
    }

    public function edit(Ticket $ticket)
    {
        $models = ProductModel::where(["status"=>"active"])->get();
        return view('admin.tickets.edit', compact('statuses', 'priorities', 'categories', 'assigned_to_users', 'ticket'));
    }

    public function show($id)
    {
        $product = Product::select("products.*","product_models.model_no")
            ->join("product_models","product_models.id","=","products.model_id")
            ->where(["products.status"=>"active","products.id"=>$id])->first();
        return view('dealer.product.show', compact('product'));
    }

    public function destroy($id)
    {
        $model = Product::find($id);
        $model->delete();

        return back();
    }

    public function getProducts(Request $request)
    {

    }
}
