<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_customer;
use App\Models\tbl_customer_order;
use App\Models\tbl_inventory_quantity;
use App\Models\tbl_inventory;
use App\Models\tbl_category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = tbl_customer::with(['customer_order.inventory_quantity.inventory'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $inventoryQuantities = tbl_inventory_quantity::with('inventory.category')
                ->where('status', 'active')
                ->where('quantity', '>', 0)
                ->get();

            $categories = tbl_category::where('status', 'active')
                ->orderBy('category_name')
                ->get();

            $inventories = tbl_inventory::with(['category', 'quantities' => function($query) {
                $query->where('status', 'active')->where('quantity', '>', 0);
            }])
                ->where('status', 'active')
                ->orderBy('item_name')
                ->get();

            return view('customer.customer', compact('customers', 'inventoryQuantities', 'categories', 'inventories'));
        } catch (\Exception $e) {
            Log::error('Error loading customers: ' . $e->getMessage());
            return back()->with('error', 'Error loading customers');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'status' => 'required|in:active,inactive'
            ]);

            $customer = tbl_customer::create([
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'status' => $request->status
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully!',
                    'data' => $customer
                ]);
            }

            return redirect()->route('customer.index')->with('success', 'Customer created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating customer: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error creating customer')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $customer = tbl_customer::with(['customer_order.inventory_quantity.inventory'])
                ->findOrFail($id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $customer
                ]);
            }

            return response()->json(['error' => 'Method not allowed'], 405);
        } catch (\Exception $e) {
            Log::error('Error loading customer for edit: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            return back()->with('error', 'Customer not found');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'status' => 'required|in:active,inactive'
            ]);

            $customer = tbl_customer::findOrFail($id);
            
            $customer->update([
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'status' => $request->status
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully!',
                    'data' => $customer
                ]);
            }

            return redirect()->route('customer.index')->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating customer: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error updating customer')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $customer = tbl_customer::findOrFail($id);
            
            // Check if customer has orders
            $orderCount = $customer->customer_order()->count();
            
            if ($orderCount > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot delete customer with {$orderCount} order(s). Please delete orders first."
                    ], 400);
                }
                
                return back()->with('error', "Cannot delete customer with {$orderCount} order(s). Please delete orders first.");
            }

            $customer->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer deleted successfully!'
                ]);
            }

            return redirect()->route('customer.index')->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting customer: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting customer');
        }
    }

    // Customer Order Methods
    public function storeOrder(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:tbl_customer,id',
                'inventory_quantity_id' => 'required|exists:tbl_inventory_quantity,id',
                'quantity_order' => 'required|integer|min:1',
                'date_deliver' => 'required|date',
                'reason' => 'nullable|string|max:500',
                'total_amount_price' => 'required|numeric|min:0'
            ]);

            // Check if enough quantity available
            $inventoryQty = tbl_inventory_quantity::findOrFail($request->inventory_quantity_id);
            if ($inventoryQty->quantity < $request->quantity_order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient quantity available. Only ' . $inventoryQty->quantity . ' items available.'
                ], 400);
            }

            DB::beginTransaction();

            $order = tbl_customer_order::create([
                'customer_id' => $request->customer_id,
                'inventory_quantity_id' => $request->inventory_quantity_id,
                'quantity_order' => $request->quantity_order,
                'date_deliver' => $request->date_deliver,
                'status' => 'delivered', // Automatic delivered status
                'reason' => $request->reason,
                'total_amount_price' => $request->total_amount_price
            ]);

            // Update inventory quantity automatically since status is delivered
            $inventoryQty->quantity -= $request->quantity_order;
            $inventoryQty->save();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully!',
                    'data' => $order->load('customer', 'inventory_quantity.inventory')
                ]);
            }

            return redirect()->route('customer.index')->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error creating order')->withInput();
        }
    }

    public function editOrder($id)
    {
        try {
            $order = tbl_customer_order::with(['customer', 'inventory_quantity.inventory'])
                ->findOrFail($id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $order
                ]);
            }

            return response()->json(['error' => 'Method not allowed'], 405);
        } catch (\Exception $e) {
            Log::error('Error loading order for edit: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return back()->with('error', 'Order not found');
        }
    }

    public function updateOrder(Request $request, $id)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:tbl_customer,id',
                'inventory_quantity_id' => 'required|exists:tbl_inventory_quantity,id',
                'quantity_order' => 'required|integer|min:1',
                'date_deliver' => 'required|date',
                'status' => 'required|in:pending,confirmed,delivered,cancelled',
                'reason' => 'nullable|string|max:500',
                'total_amount_price' => 'required|numeric|min:0'
            ]);

            $order = tbl_customer_order::findOrFail($id);
            $oldQuantity = $order->quantity_order;
            $oldStatus = $order->status;
            $inventoryQty = tbl_inventory_quantity::findOrFail($request->inventory_quantity_id);

            DB::beginTransaction();

            // Restore previous quantity if order was confirmed or delivered
            if ($oldStatus === 'confirmed' || $oldStatus === 'delivered') {
                $inventoryQty->quantity += $oldQuantity;
            }

            // Check if enough quantity available for new order
            if (($request->status === 'confirmed' || $request->status === 'delivered') && $inventoryQty->quantity < $request->quantity_order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient quantity available. Only ' . $inventoryQty->quantity . ' items available.'
                ], 400);
            }

            $order->update([
                'customer_id' => $request->customer_id,
                'inventory_quantity_id' => $request->inventory_quantity_id,
                'quantity_order' => $request->quantity_order,
                'date_deliver' => $request->date_deliver,
                'status' => $request->status,
                'reason' => $request->reason,
                'total_amount_price' => $request->total_amount_price
            ]);

            // Update inventory quantity if new order is confirmed or delivered
            if ($request->status === 'confirmed' || $request->status === 'delivered') {
                $inventoryQty->quantity -= $request->quantity_order;
            }

            $inventoryQty->save();
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully!',
                    'data' => $order->load('customer', 'inventory_quantity.inventory')
                ]);
            }

            return redirect()->route('customer.index')->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error updating order')->withInput();
        }
    }

    public function destroyOrder($id)
    {
        try {
            $order = tbl_customer_order::findOrFail($id);
            
            DB::beginTransaction();

            // Restore inventory quantity if order was confirmed or delivered
            if ($order->status === 'confirmed' || $order->status === 'delivered') {
                $inventoryQty = $order->inventory_quantity;
                $inventoryQty->quantity += $order->quantity_order;
                $inventoryQty->save();
            }

            $order->delete();
            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully!'
                ]);
            }

            return redirect()->route('customer.index')->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting order');
        }
    }
}
