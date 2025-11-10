<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\customer;
class customer_package extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_package';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id', 'date_ordered','package', 'status'];

    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id', 'id');
    }
}
