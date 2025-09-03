<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class tbl_customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_customer';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_name', 'address', 'status'];

    public function customer_order()
    {
        return $this->hasMany(tbl_customer_order::class, 'customer_id', 'id');
    }
}
