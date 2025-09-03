<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class tbl_customer_order extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_customer_order';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id', 'inventory_quantity_id', 'quantity_order', 'date_deliver', 'status','reason','total_amount_price'];

    public function customer()
    {
        return $this->belongsTo(tbl_customer::class, 'customer_id', 'id');
    }

    public function inventory_quantity()
    {
        return $this->belongsTo(tbl_inventory_quantity::class, 'inventory_quantity_id', 'id');
    }
}
