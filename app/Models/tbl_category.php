<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tbl_category extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'tbl_category';
    protected $primaryKey = 'id';
    protected $fillable = ['category_name', 'status'];
    
    /**
     * Get the inventories for the category.
     */
    public function inventories()
    {
        return $this->hasMany(tbl_inventory::class, 'category_id');
    }
}
