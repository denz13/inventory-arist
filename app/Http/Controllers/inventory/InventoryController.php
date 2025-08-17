<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\tbl_inventory;
use App\Models\tbl_inventory_details_items;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = tbl_inventory::with('items');
        
        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('client_name', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%")
                  ->orWhere('status', 'like', "%{$searchTerm}%")
                  ->orWhereHas('items', function($itemQuery) use ($searchTerm) {
                      $itemQuery->where('description', 'like', "%{$searchTerm}%")
                               ->orWhere('quantity', 'like', "%{$searchTerm}%")
                               ->orWhere('rec_meter', 'like', "%{$searchTerm}%")
                               ->orWhere('qty', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        // Handle pagination
        $perPage = $request->get('per_page', 10);
        $inventories = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // If it's an AJAX request, return JSON with table HTML
        if ($request->ajax()) {
            $tableHtml = view('inventory.inventory', compact('inventories'))->render();
            return response()->json([
                'success' => true,
                'data' => $inventories,
                'html' => $tableHtml
            ]);
        }
        
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

    public function edit($id)
    {
        try {
            $inventory = tbl_inventory::with('items')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $inventory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
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

            // Find and update inventory
            $inventory = tbl_inventory::findOrFail($id);
            $inventory->update([
                'client_name' => $request->client_name,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            // Delete existing items
            tbl_inventory_details_items::where('inventory_id', $id)->delete();

            // Create new items
            foreach ($request->items as $item) {
                tbl_inventory_details_items::create([
                    'inventory_id' => $id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'rec_meter' => $item['rec_meter'] ?? '',
                    'qty' => $item['qty'] ?? 0,
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory updated successfully!',
                'data' => $inventory->load('items')
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error updating inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            \DB::beginTransaction();

            // Find inventory
            $inventory = tbl_inventory::findOrFail($id);
            
            // Delete inventory items first
            tbl_inventory_details_items::where('inventory_id', $id)->delete();
            
            // Delete inventory
            $inventory->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory deleted successfully!'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting inventory: ' . $e->getMessage()
            ], 500);
        }
    }
}
