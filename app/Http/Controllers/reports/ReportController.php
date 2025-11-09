<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\tbl_customer;
use App\Models\tbl_customer_order;
use App\Models\tbl_inventory;
use App\Models\tbl_inventory_quantity;
use App\Models\tbl_category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.order_reports');
    }

    public function orderReports(Request $request)
    {
        $query = tbl_customer_order::with([
            'customer',
            'inventory_quantity.inventory.category'
        ]);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by customer if provided
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Group orders by customer
        $groupedOrders = $orders->groupBy('customer_id')->map(function ($customerOrders) {
            $customer = $customerOrders->first()->customer;
            $totalAmount = $customerOrders->sum('total_amount_price');
            $totalQuantity = $customerOrders->sum('quantity');
            $orderCount = $customerOrders->count();
            
            // Get the most recent delivery date
            $latestDeliveryDate = $customerOrders->max('delivery_date');
            
            // Get the most common status (or latest status)
            $statusCounts = $customerOrders->groupBy('status')->map->count();
            $primaryStatus = $statusCounts->sortDesc()->keys()->first();
            
            return [
                'customer' => $customer,
                'orders' => $customerOrders,
                'total_amount' => $totalAmount,
                'total_quantity' => $totalQuantity,
                'order_count' => $orderCount,
                'delivery_date' => $latestDeliveryDate,
                'status' => $primaryStatus,
                'customer_id' => $customer->id
            ];
        });

        // Get all customers for filter dropdown
        $customers = tbl_customer::where('status', 'active')->get();

        // Get all unique statuses from the database
        $allStatuses = tbl_customer_order::select('status')
            ->distinct()
            ->pluck('status')
            ->toArray();

        // Get status counts dynamically based on what's in the database
        $statusCounts = [];
        foreach ($allStatuses as $status) {
            $statusCounts[$status] = tbl_customer_order::where('status', $status)->count();
        }

        // Calculate total revenue
        $totalRevenue = tbl_customer_order::where('status', 'delivered')->sum('total_amount_price');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $groupedOrders->values(),
                'status_counts' => $statusCounts,
                'total_revenue' => $totalRevenue
            ]);
        }

        return view('reports.order_reports', compact('groupedOrders', 'customers', 'statusCounts', 'totalRevenue'));
    }

    public function printOrder($id)
    {
        $order = tbl_customer_order::with([
            'customer',
            'inventory_quantity.inventory.category'
        ])->findOrFail($id);

        $data = [
            'order' => $order,
            'company_name' => 'Inventory Management System',
            'print_date' => now()->format('M d, Y H:i:s')
        ];

        $pdf = Pdf::loadView('reports.print_order', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("Order-{$order->id}.pdf");
    }

    public function printMultipleOrders(Request $request)
    {
        $orderIds = explode(',', $request->get('order_ids'));
        $deliveryDate = $request->get('delivery_date');
        
        // Get all orders with their relationships
        $orders = tbl_customer_order::with([
            'customer',
            'inventory_quantity.inventory.category'
        ])->whereIn('id', $orderIds)->get();

        if ($orders->isEmpty()) {
            abort(404, 'Orders not found');
        }

        // Group orders by customer for better organization
        $groupedOrders = $orders->groupBy('customer_id');
        
        $data = [
            'groupedOrders' => $groupedOrders,
            'deliveryDate' => $deliveryDate,
            'company_name' => 'Inventory Management System',
            'print_date' => now()->format('M d, Y H:i:s'),
            'totalOrders' => $orders->count(),
            'totalAmount' => $orders->sum('total_amount_price')
        ];

        $pdf = Pdf::loadView('reports.print_multiple_orders', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $dateFormatted = $deliveryDate === 'no-date' ? 'No-Date' : \Carbon\Carbon::parse($deliveryDate)->format('Y-m-d');
        return $pdf->stream("Orders-{$dateFormatted}.pdf");
    }
}
