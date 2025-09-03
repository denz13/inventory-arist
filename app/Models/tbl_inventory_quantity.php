<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class tbl_inventory_quantity extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_inventory_quantity';
    protected $primaryKey = 'id';
    protected $fillable = ['inventory_id', 'quantity', 'price', 'price_effective_date','status','is_low_stocks','note'];

    public function inventory()
    {
        return $this->belongsTo(tbl_inventory::class, 'inventory_id', 'id');
    }
}
