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
    protected $fillable = ['category_id', 'item_name', 'description', 'status'];

    /**
     * Get the category for this inventory item
     */
    public function category()
    {
        return $this->belongsTo(tbl_category::class, 'category_id', 'id');
    }

    /**
     * Get the quantity details for this inventory item (single record)
     */
    public function quantity()
    {
        return $this->hasOne(tbl_inventory_quantity::class, 'inventory_id', 'id');
    }

    /**
     * Get all quantity records for this inventory item
     */
    public function quantities()
    {
        return $this->hasMany(tbl_inventory_quantity::class, 'inventory_id', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope for active inventories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive inventories
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
