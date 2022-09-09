<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeFilterProducts($query)
    {
        $query->when(request()->input('title'), function($query) {
                $query->whereHas('priority', function($query) {
                    $query->whereId(request()->input('title'));
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

    public function getallProduct($id = null)
    {

    }
}
