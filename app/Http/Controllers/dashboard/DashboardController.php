<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\tbl_inventory;
use App\Models\tbl_inventory_details_items;

class DashboardController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function index()
    {
        // Get dashboard statistics from tbl_inventory
        $totalClients = tbl_inventory::count();
        $activeClients = tbl_inventory::where('status', 'active')->count();
        $totalItems = tbl_inventory_details_items::count();
        $totalInventoryValue = tbl_inventory_details_items::sum('quantity');
        
        // Get recent clients for the table
        $recentClients = tbl_inventory::with('items')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
            
        // Get top clients by item count
        $topClients = tbl_inventory::withCount('items')
            ->orderBy('items_count', 'desc')
            ->take(5)
            ->get();
        
        $dashboardData = [
            'totalClients' => $totalClients,
            'activeClients' => $activeClients,
            'totalItems' => $totalItems,
            'totalInventoryValue' => $totalInventoryValue,
            'recentClients' => $recentClients,
            'topClients' => $topClients
        ];
        
        return view('dashboard.dashboard', compact('dashboardData'));
    }
}
