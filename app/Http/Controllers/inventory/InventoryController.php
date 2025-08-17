<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\tbl_inventory;
use App\Models\tbl_inventory_details_items;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //
    public function index()
    {
        $inventories = tbl_inventory::with('items')->get();
        return view('inventory.inventory', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rec_meter' => 'nullable|string|max:255',
            'items.*.qty' => 'nullable|integer|min:0',
        ]);

        try {
            \DB::beginTransaction();

            // Create inventory
            $inventory = tbl_inventory::create([
                'client_name' => $request->client_name,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            // Create inventory items
            foreach ($request->items as $item) {
                tbl_inventory_details_items::create([
                    'inventory_id' => $inventory->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'rec_meter' => $item['rec_meter'] ?? '',
                    'qty' => $item['qty'] ?? 0,
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory created successfully!',
                'data' => $inventory->load('items')
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating inventory: ' . $e->getMessage()
            ], 500);
        }
    }
}
