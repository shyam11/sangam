<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductModel;
use App\Models\DealerCategory;
use Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Session;
use Carbon\Carbon;

class DealerCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DealerCategory::join('users','dealer_categories.created_by','=','users.id')->orderBy('id','DESC')
                ->filterCategories($request)
                ->select(sprintf('%s.*', (new DealerCategory)->table),'users.name as created_name');
            $table = Datatables::of($query);
            $table->editColumn('created_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y'); return $formatedDate; });
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'dealer_category_show';
                $editGate      = 'dealer_category_edit';
                $deleteGate    = 'dealer_category_delete';
                $crudRoutePart = 'dealer-categories';

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

            $table->rawColumns(['actions', 'placeholder', 'created_by','image']);

            return $table->make(true);
        }
        return view("dealer.dealer_category.index");
    }

    public function create()
    {
        return view("dealer.dealer_category.create");
    }

    public function store(Request $request)
    {
        $data= new DealerCategory();

        $data->name = $request->name;
        $data->percentage = $request->percentage;
        $data->status = $request->status;
        $data->created_by = Auth::user()->id;
        $data->save();
        return redirect()->route('admin.dealer-categories');
    }

    public function edit($id)
    {
        $dealerCategory = DealerCategory::where(["id"=>$id,"status"=>"active"])->first();
        return view('dealer.dealer_category.edit', compact('dealerCategory'));
    }

    public function show($id)
    {
        $category = DealerCategory::where(["status"=>"active","id"=>$id])->first();
        return view('dealer.dealer_category.show', compact('category'));
    }

    public function update(Request $request)
    {
        $data = [
            "name" => $request->name,
            "percentage" => $request->percentage,
            "status" => $request->status,
            "updated_by" => Auth::user()->id
        ];
        DealerCategory::where(["id"=>$request->id])->update($data);
        return redirect()->route('admin.dealer-categories');
    }

    public function destroy($id)
    {
        $model = DealerCategory::find($id);
        $model->delete();

        return back();
    }
}
