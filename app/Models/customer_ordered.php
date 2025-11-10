<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\customer_package;
use App\Models\inventory_items;
class customer_ordered extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_ordered';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_package_id', 'inventory_items_id', 'qty', 'price', 'status'];

    public function customer_package()
    {
        return $this->belongsTo(customer_package::class, 'customer_package_id', 'id');
    }

    public function inventory_items()
    {
        return $this->belongsTo(inventory_items::class, 'inventory_items_id', 'id');
    }
}
