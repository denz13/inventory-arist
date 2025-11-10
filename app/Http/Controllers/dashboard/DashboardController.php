<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\customer;
use App\Models\inventory_items;

class DashboardController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function index()
    {
        // Get basic customer statistics
        $totalCustomers = customer::count();
        $activeCustomers = customer::where('status', 'active')->count();
        $inactiveCustomers = customer::where('status', 'inactive')->count();
        
        // Get inventory statistics
        $totalInventoryItems = inventory_items::count();
        $lowStockItems = inventory_items::where('qty', '<=', 10)
            ->where('marked_as', '!=', 'OLD')
            ->count();
        $criticalStockItems = inventory_items::where('qty', '<=', 5)
            ->where('marked_as', '!=', 'OLD')
            ->count();
        
        // Get recent customers
        $recentCustomers = customer::orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get customers added this month
        $now = Carbon::now();
        $customersThisMonth = customer::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
            
        $customersLastMonth = customer::whereYear('created_at', $now->copy()->subMonth()->year)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->count();
            
        $customerGrowth = $customersLastMonth > 0 ? 
            round((($customersThisMonth - $customersLastMonth) / $customersLastMonth) * 100, 1) : 
            ($customersThisMonth > 0 ? 100 : 0);
        
        $dashboardData = [
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'inactiveCustomers' => $inactiveCustomers,
            'totalInventoryItems' => $totalInventoryItems,
            'lowStockItems' => $lowStockItems,
            'criticalStockItems' => $criticalStockItems,
            'recentCustomers' => $recentCustomers,
            'customersThisMonth' => $customersThisMonth,
            'customersLastMonth' => $customersLastMonth,
            'customerGrowth' => $customerGrowth
        ];
        
        return view('dashboard.dashboard', compact('dashboardData'));
    }
}
