<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory_items;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = inventory_items::query();
        
        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('item_name', 'like', "%{$searchTerm}%")
                  ->orWhere('marked_as', 'like', "%{$searchTerm}%")
                  ->orWhere('status', 'like', "%{$searchTerm}%")
                  ->orWhere('qty', 'like', "%{$searchTerm}%")
                  ->orWhere('price', 'like', "%{$searchTerm}%");
            });
        }
        
        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Handle pagination
        $perPage = $request->get('per_page', 15);
        $inventories = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Get existing items for dropdown (for modal)
        $existingItems = inventory_items::where('status', 'active')->get();
        $hasExistingData = $existingItems->count() > 0;
        
        // If it's an AJAX request, return JSON with table HTML
        if ($request->ajax()) {
            $tableHtml = view('inventory.inventory', compact('inventories', 'existingItems', 'hasExistingData'))->render();
            return response()->json([
                'success' => true,
                'data' => $inventories,
                'html' => $tableHtml
            ]);
        }
        
        return view('inventory.inventory', compact('inventories', 'existingItems', 'hasExistingData'));
    }

    public function store(Request $request)
    {
        // Dynamic validation based on marked_as
        $rules = [
            'qty' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'marked_as' => 'required|in:NEW,EXISTING',
            'status' => 'required|in:active,inactive',
        ];

        // If marked as NEW, require new item name
        if ($request->marked_as === 'NEW') {
            $rules['item_name'] = 'required|string|max:255';
        }
        // If marked as EXISTING, require existing item selection
        else if ($request->marked_as === 'EXISTING') {
            $rules['existing_item_name'] = 'required|string|max:255';
        }

        $request->validate($rules);

        try {
            // Determine item name based on marked_as
            $itemName = $request->marked_as === 'NEW' 
                ? $request->item_name 
                : $request->existing_item_name;

            // If marked as NEW, check for existing items with same name
            if ($request->marked_as === 'NEW') {
                $existingItem = inventory_items::where('item_name', $itemName)->first();
                if ($existingItem) {
                    // Update existing item to mark it as OLD
                    $existingItem->update([
                        'item_name' => $existingItem->item_name . ' - OLD',
                        'marked_as' => 'OLD'
                    ]);
                    
                    // Also update any other items with the same original name
                    inventory_items::where('item_name', $itemName)
                        ->where('id', '!=', $existingItem->id)
                        ->update([
                            'item_name' => $itemName . ' - OLD',
                            'marked_as' => 'OLD'
                        ]);
                }
            }

            // Create new inventory item
            $inventory = inventory_items::create([
                'item_name' => $itemName,
                'qty' => $request->qty,
                'price' => $request->price,
                'marked_as' => $request->marked_as,
                'status' => $request->status,
            ]);

            // Check if it's an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item created successfully!',
                    'data' => $inventory
                ]);
            }

            // For regular form submission, redirect with success message
            return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully!');

        } catch (\Exception $e) {
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
            $inventory = inventory_items::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $inventory
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
        // Dynamic validation based on marked_as
        $rules = [
            'qty' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'marked_as' => 'required|in:NEW,EXISTING',
            'status' => 'required|in:active,inactive',
        ];

        // If marked as NEW, require new item name
        if ($request->marked_as === 'NEW') {
            $rules['item_name'] = 'required|string|max:255';
        }
        // If marked as EXISTING, require existing item selection
        else if ($request->marked_as === 'EXISTING') {
            $rules['existing_item_name'] = 'required|string|max:255';
        }

        $request->validate($rules);

        try {
            // Determine item name based on marked_as
            $itemName = $request->marked_as === 'NEW' 
                ? $request->item_name 
                : $request->existing_item_name;

            // Find and update inventory item
            $inventory = inventory_items::findOrFail($id);
            $inventory->update([
                'item_name' => $itemName,
                'qty' => $request->qty,
                'price' => $request->price,
                'marked_as' => $request->marked_as,
                'status' => $request->status,
            ]);

            // Check if it's an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item updated successfully!',
                    'data' => $inventory
                ]);
            }

            // For regular form submission, redirect with success message
            return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully!');

        } catch (\Exception $e) {
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
            // Find and delete inventory item
            $inventory = inventory_items::findOrFail($id);
            $inventory->delete();

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
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting inventory item: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Error deleting inventory item: ' . $e->getMessage()]);
        }
    }
}
