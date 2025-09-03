<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\tbl_inventory;
use App\Models\tbl_inventory_quantity;
use App\Models\tbl_category;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = tbl_inventory::with(['category', 'quantities']);
        
        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('item_name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('status', 'like', "%{$searchTerm}%")
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('category_name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('quantity', function($quantityQuery) use ($searchTerm) {
                      $quantityQuery->where('quantity', 'like', "%{$searchTerm}%")
                                   ->orWhere('note', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        // Handle category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Handle pagination
        $perPage = $request->get('per_page', 10);
        $inventories = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Get categories for filter dropdown
        $categories = tbl_category::where('status', 'active')->get();
        
        // Get existing items with their IDs and categories for dropdown
        $existingItems = tbl_inventory::with('category')->where('status', 'active')->get();
        
        // If it's an AJAX request, return JSON with table HTML
        if ($request->ajax()) {
            $tableHtml = view('inventory.inventory', compact('inventories', 'categories', 'existingItems'))->render();
            return response()->json([
                'success' => true,
                'data' => $inventories,
                'html' => $tableHtml
            ]);
        }
        
        return view('inventory.inventory', compact('inventories', 'categories', 'existingItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:tbl_category,id',
            'item_name' => 'nullable|string|max:255',
            'existing_item_id' => 'nullable|exists:tbl_inventory,id',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_effective_date' => 'nullable|date',
            'is_low_stocks' => 'boolean',
            'note' => 'nullable|string|max:500',
        ]);

        // Custom validation: either item_name or existing_item_id must be provided
        if (!$request->filled('item_name') && !$request->filled('existing_item_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Either item name or existing item must be selected'
            ], 422);
        }

        // If new item, category_id is required
        if ($request->filled('item_name') && !$request->filled('category_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Category is required for new items'
            ], 422);
        }

        try {
            \DB::beginTransaction();

            if ($request->filled('existing_item_id')) {
                // Find existing inventory item by ID
                $inventory = tbl_inventory::findOrFail($request->existing_item_id);
                
                // Create new quantity record for existing item
                tbl_inventory_quantity::create([
                    'inventory_id' => $inventory->id,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'price_effective_date' => $request->price_effective_date,
                    'status' => 'active',
                    'is_low_stocks' => $request->boolean('is_low_stocks'),
                    'note' => $request->note,
                ]);
                
            } else {
                // Create new inventory item with new name
                $inventory = tbl_inventory::create([
                    'category_id' => $request->category_id,
                    'item_name' => $request->item_name,
                    'description' => $request->description,
                    'status' => $request->status,
                ]);

                // Create inventory quantity record
                tbl_inventory_quantity::create([
                    'inventory_id' => $inventory->id,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'price_effective_date' => $request->price_effective_date,
                    'status' => 'active',
                    'is_low_stocks' => $request->boolean('is_low_stocks'),
                    'note' => $request->note,
                ]);
            }

            \DB::commit();

            // Check if it's an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item created successfully!',
                    'data' => $inventory->load(['category', 'quantity'])
                ]);
            }

            // For regular form submission, redirect with success message
            return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully!');

        } catch (\Exception $e) {
            \DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating inventory item: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->withErrors(['error' => 'Error creating inventory item: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $inventory = tbl_inventory::with(['category', 'quantity'])->findOrFail($id);
            $categories = tbl_category::where('status', 'active')->get();
            
            return response()->json([
                'success' => true,
                'data' => $inventory,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading inventory item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:tbl_category,id',
            'item_name' => 'required_without:existing_item_name|string|max:255',
            'existing_item_name' => 'nullable|required_without:item_name|string|max:255',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_effective_date' => 'nullable|date',
            'is_low_stocks' => 'boolean',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            \DB::beginTransaction();

            // Find and update inventory item
            $inventory = tbl_inventory::findOrFail($id);
            $finalItemName = $request->filled('item_name')
                ? $request->input('item_name')
                : $request->input('existing_item_name');

            $inventory->update([
                'category_id' => $request->category_id,
                'item_name' => $finalItemName,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Update or create inventory quantity record
            $quantityRecord = tbl_inventory_quantity::where('inventory_id', $id)->first();
            if ($quantityRecord) {
                $quantityRecord->update([
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'price_effective_date' => $request->price_effective_date,
                    'is_low_stocks' => $request->boolean('is_low_stocks'),
                    'note' => $request->note,
                ]);
            } else {
                tbl_inventory_quantity::create([
                    'inventory_id' => $id,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'price_effective_date' => $request->price_effective_date,
                    'status' => 'active',
                    'is_low_stocks' => $request->boolean('is_low_stocks'),
                    'note' => $request->note,
                ]);
            }

            \DB::commit();

            // Check if it's an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item updated successfully!',
                    'data' => $inventory->load(['category', 'quantity'])
                ]);
            }

            // For regular form submission, redirect with success message
            return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully!');

        } catch (\Exception $e) {
            \DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating inventory item: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->withErrors(['error' => 'Error updating inventory item: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            \DB::beginTransaction();

            // Find and delete inventory item
            $inventory = tbl_inventory::findOrFail($id);
            
            // Delete related quantity records first
            tbl_inventory_quantity::where('inventory_id', $id)->delete();
            
            // Delete inventory item
            $inventory->delete();

            \DB::commit();

            // Check if it's an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item deleted successfully!'
                ]);
            }

            // For regular form submission, redirect with success message
            return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully!');

        } catch (\Exception $e) {
            \DB::rollback();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting inventory item: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Error deleting inventory item: ' . $e->getMessage()]);
        }
    }

    // Quantity-specific methods
    public function editQuantity($id)
    {
        try {
            $quantity = tbl_inventory_quantity::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $quantity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading quantity data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_effective_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'is_low_stocks' => 'boolean',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            \DB::beginTransaction();

            $quantityRecord = tbl_inventory_quantity::findOrFail($id);
            $quantityRecord->update([
                'quantity' => $request->quantity,
                'price' => $request->price,
                'price_effective_date' => $request->price_effective_date,
                'status' => $request->status,
                'is_low_stocks' => $request->boolean('is_low_stocks'),
                'note' => $request->note,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully!',
                'data' => $quantityRecord
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating quantity: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyQuantity($id)
    {
        try {
            \DB::beginTransaction();

            $quantityRecord = tbl_inventory_quantity::findOrFail($id);
            $quantityRecord->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantity deleted successfully!'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting quantity: ' . $e->getMessage()
            ], 500);
        }
    }
}
