<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class tbl_inventory_details_items extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_inventory_details_items';
    protected $primaryKey = 'id';
    protected $fillable = ['inventory_id', 'description', 'quantity', 'rec_meter', 'qty'];

    public function inventory()
    {
        return $this->belongsTo(tbl_inventory::class, 'inventory_id', 'id');
    }
}
