<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class tbl_inventory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_inventory';
    protected $primaryKey = 'id';
    protected $fillable = ['client_name', 'address', 'status'];

    public function items()
    {
        return $this->hasMany(tbl_inventory_details_items::class, 'inventory_id', 'id');
    }
}
