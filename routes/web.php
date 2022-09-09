<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\TicketsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Complaint\HomeController;
use App\Http\Controllers\Complaint\TicketsController;
use App\Http\Controllers\Complaint\StatusesController;
use App\Http\Controllers\Complaint\PrioritiesController;
use App\Http\Controllers\Complaint\CommentsController;
use App\Http\Controllers\Complaint\CategoriesController;
use App\Http\Controllers\Complaint\AuditLogsController;

use App\Http\Controllers\Dealer\ProductModelController;
use App\Http\Controllers\Dealer\ProductController;
use App\Http\Controllers\Dealer\DealerCategoryController;
use App\Http\Controllers\Dealer\DealerProfileController;
use App\Http\Controllers\Dealer\ProductAttributeController;
use App\Http\Controllers\Dealer\OrderController;
use App\Http\Controllers\Dealer\TransportController;
use App\Http\Controllers\Dealer\ShipmentController;
use App\Http\Controllers\Dealer\TransactionController;
use App\Http\Controllers\Dealer\AccountController;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return redirect(route('login'));
});
Route::get('/home', function () {
    $route = Gate::denies('dashboard_access') ? 'admin.tickets.index' : 'admin.home';
    if (session('status')) {
        return redirect()->route($route)->with('status', session('status'));
    }

    return redirect()->route($route);
});

Auth::routes(['register' => false]);

Route::post('tickets/media', 'TicketController@storeMedia')->name('tickets.storeMedia');
Route::post('tickets/comment/{ticket}', 'TicketController@storeComment')->name('tickets.storeComment');
Route::resource('tickets', 'TicketController')->only(['show', 'create', 'store']);
Route::get('complaint','ServiceController@createComplaint')->name('createComplaint');
Route::post('complaint/store','ServiceController@storeComplaint')->name('storeComplaint');
Route::post('thank-you','ServiceController@thanku')->name('thanku');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Statuses
    Route::delete('statuses/destroy', 'StatusesController@massDestroy')->name('statuses.massDestroy');
    Route::resource('statuses', 'StatusesController');

    // Priorities
    Route::delete('priorities/destroy', 'PrioritiesController@massDestroy')->name('priorities.massDestroy');
    Route::resource('priorities', 'PrioritiesController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    // Tickets
    Route::delete('tickets/destroy', 'TicketsController@massDestroy')->name('tickets.massDestroy');
    Route::post('tickets/media', 'TicketsController@storeMedia')->name('tickets.storeMedia');
    Route::post('tickets/comment/{ticket}', 'TicketsController@storeComment')->name('tickets.storeComment');
    Route::post('tickets/close-status', 'TicketsController@ticketClose')->name('tickets.ticketClose');
    Route::get('testing','TicketsController@testing');
    Route::post('tickets/export', 'TicketsController@exportTicket')->name('tickets.export');
    Route::resource('tickets', 'TicketsController');

    // Comments
    Route::delete('comments/destroy', 'CommentsController@massDestroy')->name('comments.massDestroy');
    Route::resource('comments', 'CommentsController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    Route::get('product-model',[ProductModelController::class,'index'])->name('product-model');
    Route::get('product-model/create',[ProductModelController::class,'create'])->name('product-model.create');
    Route::post('product-model/store',[ProductModelController::class,'store'])->name('product-model-store');
    Route::get('product-model/show/{id}',[ProductModelController::class,'show'])->name('product-model.show');
    Route::get('product-model/edit/{id}',[ProductModelController::class,'edit'])->name('product-model.edit');
    Route::post('product-model/update',[ProductModelController::class,'update'])->name('product-model.update');
    Route::delete('product-model/destroy/{id}',[ProductModelController::class,'destroy'])->name('product-model.destroy');
    Route::get('product-model/editgroup/{id}',[ProductModelController::class,'editgroup'])->name('product-model.editgroup');


    Route::get('products',[ProductController::class,'index'])->name('products');
    Route::get('products/create',[ProductController::class,'create'])->name('products.create');
    Route::post('products/store',[ProductController::class,'store'])->name('products.store');
    Route::get('products/show/{id}',[ProductController::class,'show'])->name('products.show');
    Route::get('products/edit',[ProductController::class,'edit'])->name('products.edit');
    Route::post('products/update',[ProductController::class,'update'])->name('products.update');
    Route::delete('products/destroy/{id}',[ProductController::class,'destroy'])->name('products.destroy');
    Route::get('getProduct',[ProductController::class,'getProducts'])->name('products.getproducts');

    Route::get('dealer-categories',[DealerCategoryController::class,'index'])->name('dealer-categories');
    Route::get('dealer-categories/create',[DealerCategoryController::class,'create'])->name('dealer-categories.create');
    Route::post('dealer-categories/store',[DealerCategoryController::class,'store'])->name('dealer-categories.store');
    Route::get('dealer-categories/show/{id}',[DealerCategoryController::class,'show'])->name('dealer-categories.show');
    Route::get('dealer-categories/edit/{id}',[DealerCategoryController::class,'edit'])->name('dealer-categories.edit');
    Route::post('dealer-categories/update',[DealerCategoryController::class,'update'])->name('dealer-categories.update');
    Route::delete('dealer-categories/destroy/{id}',[DealerCategoryController::class,'destroy'])->name('dealer-categories.destroy');

    Route::get('dealer-profile',[DealerProfileController::class,'index'])->name('dealer-profile');
    Route::get('dealer-profile/create',[DealerProfileController::class,'create'])->name('dealer-profile.create');
    Route::post('dealer-profile/store',[DealerProfileController::class,'store'])->name('dealer-profile.store');
    Route::get('dealer-profile/show/{id}',[DealerProfileController::class,'show'])->name('dealer-profile.show');
    Route::get('dealer-profile/edit/{id}',[DealerProfileController::class,'edit'])->name('dealer-profile.edit');
    Route::post('dealer-profile/update',[DealerProfileController::class,'update'])->name('dealer-profile.update');
    Route::delete('dealer-profile/destroy/{id}',[DealerProfileController::class,'destroy'])->name('dealer-profile.destroy');
    Route::get('viewdetail/{order_id}', [DealerProfileController::class,'viewDetail'])->name('viewdetail');
    Route::get('get-city',[DealerProfileController::class,'getCity'])->name('getcity');

    Route::get('my-orders',[DealerProfileController::class,'ddOrders'])->name('ddorders');

    Route::get('submit-otp/{id}',[DealerProfileController::class,'otpView'])->name('ddotp');
    Route::post('verify-otp',[DealerProfileController::class,'verifyOtp'])->name('verifyOtp');

    Route::get('product-attributes',[ProductAttributeController::class,'index'])->name('product-attributes');
    Route::get('product-attributes/create',[ProductAttributeController::class,'create'])->name('product-attributes.create');
    Route::post('product-attributes/store',[ProductAttributeController::class,'store'])->name('product-attributes.store');
    Route::get('product-attributes/show/{id}',[ProductAttributeController::class,'show'])->name('product-attributes.show');
    Route::get('product-attributes/edit/{id}',[ProductAttributeController::class,'edit'])->name('product-attributes.edit');
    Route::post('product-attributes/update',[ProductAttributeController::class,'update'])->name('product-attributes.update');
    Route::delete('product-attributes/destroy/{id}',[ProductAttributeController::class,'destroy'])->name('product-attributes.destroy');

    Route::get('product/almirah',[OrderController::class,'index'])->name('orders');
    Route::get('product/office-collection',[OrderController::class,'index'])->name('officecollection');
    Route::get('product/accessories',[OrderController::class,'index'])->name('accessories');
    Route::post('orders/store',[OrderController::class,'store'])->name('addorders');
    Route::get('cart',[OrderController::class,'cart'])->name('cart');
    Route::post('checkout',[OrderController::class,'checkout'])->name('checkout');
    Route::delete('cartitemdelete/{id}',[OrderController::class,'destroy'])->name('deleteitem');
    Route::post('cartitemupdate/{id}',[OrderController::class,'updateCartItems'])->name('cartupdate');
    Route::get('test',[OrderController::class,'test'])->name('test');
    Route::get('vieworderdetail/{order_id}', [OrderController::class,'viewDetailed'])->name('vieworderdetail');
    Route::post('orderitemupdate/{id}',[OrderController::class,'updateOrderItems'])->name('orderItemUpdate');

    Route::get('getorders',[OrderController::class,'getorders'])->name('getorders');
    Route::get('production',[OrderController::class,'getorders'])->name('production');
    Route::get('logistic',[OrderController::class,'getorders'])->name('logistic');

    Route::get('getgrouping',[OrderController::class,'getAttrGroup'])->name('getgrouping');

    Route::post('addcrmdd',[OrderController::class,'addCrmDD'])->name('addcrmdd');
    Route::post('change-status',[OrderController::class,'changeOrderStatus'])->name('change-status');


    Route::delete('attributegroup/{id}',[ProductModelController::class,'deleteAttrGroup'])->name('deleteattrgroup');
    Route::post('attributegroupupdate/{id}',[ProductModelController::class,'updateAttrGroup'])->name('attributegroupupdate');

    Route::get('transport',[TransportController::class,'index'])->name('transport');
    Route::get('transport/create',[TransportController::class,'create'])->name('transport.create');
    Route::post('transport/store',[TransportController::class,'store'])->name('transport.store');
    Route::get('transport/show/{id}',[TransportController::class,'show'])->name('transport.show');
    Route::get('transport/edit/{id}',[TransportController::class,'edit'])->name('transport.edit');
    Route::post('transport/update',[TransportController::class,'update'])->name('transport.update');
    Route::delete('transport/destroy/{id}',[TransportController::class,'destroy'])->name('transport.destroy');

    Route::get('transactions',[TransactionController::class,'index'])->name('transactions');
    Route::get('transactions/create',[TransactionController::class,'create'])->name('transactions.create');
    Route::post('transactions/store',[TransactionController::class,'store'])->name('transactions.store');
    Route::get('transactions/show/{id}',[TransactionController::class,'show'])->name('transactions.show');
    Route::get('transactions/edit/{id}',[TransactionController::class,'edit'])->name('transactions.edit');
    Route::post('transactions/update',[TransactionController::class,'update'])->name('transactions.update');
    Route::delete('transactions/destroy/{id}',[TransactionController::class,'destroy'])->name('transactions.destroy');

    Route::get('accounts',[AccountController::class,'index'])->name('accounts');
    Route::get('accounts/show/{id}',[AccountController::class,'show'])->name('accounts.show');

    Route::get('manage-shipment/{id}',[ShipmentController::class,'create'])->name('manage-shipment');
    Route::post('manage-shipment/store',[ShipmentController::class,'store'])->name('manage-shipment.store');
});


Route::get("testing",[TestController::class,'index']);