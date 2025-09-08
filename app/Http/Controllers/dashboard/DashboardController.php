<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\tbl_customer;
use App\Models\tbl_customer_order;
use App\Models\tbl_inventory;
use App\Models\tbl_inventory_quantity;

class DashboardController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function index()
    {
        // Get current date for time-based calculations
        $now = Carbon::now();
        
        // Get basic statistics
        $totalCustomers = tbl_customer::count();
        $activeCustomers = tbl_customer::where('status', 'active')->count();
        
        // Get delivered orders (completed sales)
        $deliveredOrders = tbl_customer_order::where('status', 'delivered');
        $totalOrders = $deliveredOrders->count();
        $totalRevenue = $deliveredOrders->sum('total_amount_price');
        
        // Sales by time periods
        $salesByYear = tbl_customer_order::where('status', 'delivered')
            ->select(
                DB::raw('YEAR(date_deliver) as year'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount_price) as revenue')
            )
            ->groupBy(DB::raw('YEAR(date_deliver)'))
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();
            
        $salesByMonth = tbl_customer_order::where('status', 'delivered')
            ->select(
                DB::raw('YEAR(date_deliver) as year'),
                DB::raw('MONTH(date_deliver) as month'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount_price) as revenue')
            )
            ->groupBy(DB::raw('YEAR(date_deliver)'), DB::raw('MONTH(date_deliver)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();
            
        $salesByWeek = tbl_customer_order::where('status', 'delivered')
            ->select(
                DB::raw('YEAR(date_deliver) as year'),
                DB::raw('WEEK(date_deliver) as week'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount_price) as revenue')
            )
            ->groupBy(DB::raw('YEAR(date_deliver)'), DB::raw('WEEK(date_deliver)'))
            ->orderBy('year', 'desc')
            ->orderBy('week', 'desc')
            ->take(4)
            ->get();
            
        $salesByDate = tbl_customer_order::where('status', 'delivered')
            ->select(
                DB::raw('DATE(date_deliver) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount_price) as revenue')
            )
            ->groupBy(DB::raw('DATE(date_deliver)'))
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();
        
        // Top customers by order count
        $topCustomers = tbl_customer::withCount(['customer_order' => function($query) {
                $query->where('status', 'delivered');
            }])
            ->with(['customer_order' => function($query) {
                $query->where('status', 'delivered');
            }])
            ->whereHas('customer_order', function($query) {
                $query->where('status', 'delivered');
            })
            ->orderBy('customer_order_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($customer) {
                $customer->total_spent = $customer->customer_order->sum('total_amount_price');
                return $customer;
            });
        
        // Recent customers with orders
        $recentCustomers = tbl_customer::with(['customer_order' => function($query) {
                $query->where('status', 'delivered')->latest();
            }])
            ->whereHas('customer_order', function($query) {
                $query->where('status', 'delivered');
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($customer) {
                $customer->orders_count = $customer->customer_order->count();
                $customer->total_spent = $customer->customer_order->sum('total_amount_price');
                $customer->last_order_date = $customer->customer_order->first()?->date_deliver;
                return $customer;
            });
        
        // Monthly comparison (current vs previous month)
        $currentMonth = tbl_customer_order::where('status', 'delivered')
            ->whereYear('date_deliver', $now->year)
            ->whereMonth('date_deliver', $now->month)
            ->sum('total_amount_price');
            
        $previousMonth = tbl_customer_order::where('status', 'delivered')
            ->whereYear('date_deliver', $now->copy()->subMonth()->year)
            ->whereMonth('date_deliver', $now->copy()->subMonth()->month)
            ->sum('total_amount_price');
            
        $monthlyGrowth = $previousMonth > 0 ? (($currentMonth - $previousMonth) / $previousMonth) * 100 : 0;
        
        $dashboardData = [
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'salesByYear' => $salesByYear,
            'salesByMonth' => $salesByMonth,
            'salesByWeek' => $salesByWeek,
            'salesByDate' => $salesByDate,
            'topCustomers' => $topCustomers,
            'recentCustomers' => $recentCustomers,
            'currentMonth' => $currentMonth,
            'previousMonth' => $previousMonth,
            'monthlyGrowth' => round($monthlyGrowth, 1)
        ];
        
        return view('dashboard.dashboard', compact('dashboardData'));
    }
}
