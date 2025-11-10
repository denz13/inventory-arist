<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\customer_package;
use App\Models\customer_ordered;
use App\Models\inventory_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddCustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = customer::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        // Pagination
        $customers = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get inventory items for order modal
        $inventories = inventory_items::where('status', 'active')
                     ->where('qty', '>', 0)
                     ->orderBy('item_name')
                     ->get();

        return view('customer.add_customer', compact('customers', 'inventories'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $customer = customer::create([
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer added successfully!',
                    'data' => $customer
                ]);
            }

            return redirect()->route('customer.add')->with('success', 'Customer added successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding customer: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error adding customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Request $request, $id)
    {
        try {
            $customer = customer::findOrFail($id);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $customer
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            return redirect()->route('customer.add')->with('error', 'Customer not found');
        }
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $customer = customer::findOrFail($id);
            
            $customer->update([
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully!',
                    'data' => $customer
                ]);
            }

            return redirect()->route('customer.add')->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating customer: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error updating customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $customer = customer::findOrFail($id);
            $customerName = $customer->customer_name;
            $customer->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Customer '{$customerName}' deleted successfully!"
                ]);
            }

            return redirect()->route('customer.add')->with('success', "Customer '{$customerName}' deleted successfully!");
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting customer: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }

    /**
     * Store a new customer package/order with items
     */
    public function storePackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customer,id',
            'date_ordered' => 'required|date',
            'package' => 'required|string|max:255',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.inventory_items_id' => 'required|exists:inventory_items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Create customer package
            $package = customer_package::create([
                'customer_id' => $request->customer_id,
                'date_ordered' => $request->date_ordered,
                'package' => $request->package,
                'status' => $request->status,
            ]);

            // Create customer ordered items
            foreach ($request->items as $item) {
                // Check inventory availability
                $inventoryItem = inventory_items::findOrFail($item['inventory_items_id']);
                
                if ($inventoryItem->qty < $item['qty']) {
                    throw new \Exception("Insufficient stock for {$inventoryItem->item_name}. Available: {$inventoryItem->qty}, Requested: {$item['qty']}");
                }

                // Create order item
                customer_ordered::create([
                    'customer_package_id' => $package->id,
                    'inventory_items_id' => $item['inventory_items_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'status' => 'active',
                ]);

                // Update inventory quantity
                $inventoryItem->decrement('qty', $item['qty']);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order package created successfully!',
                    'data' => $package->load('customer')
                ]);
            }

            return redirect()->route('customer.add')->with('success', 'Order package created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating order package: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating order package: ' . $e->getMessage())->withInput();
        }
    }
}
