<?php

namespace App\Http\Controllers\ledger;

use App\Http\Controllers\Controller;
use App\Models\tbl_inventory;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        // Debug: Check if table has data
        $totalRecords = tbl_inventory::count();
        \Log::info("LedgerController: Total records in tbl_inventory: " . $totalRecords);
        
        $query = tbl_inventory::with(['items' => function($query) {
            $query->select('id', 'inventory_id', 'description', 'quantity', 'rec_meter', 'qty');
        }]);
        
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
        $clients = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Debug: Log the results
        \Log::info("LedgerController: Clients found: " . $clients->count());
        \Log::info("LedgerController: First client: " . ($clients->first() ? $clients->first()->client_name : 'None'));
        
        // If it's an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $clients,
                'html' => view('ledger.ledger-table', compact('clients'))->render()
            ]);
        }
        
        return view('ledger.ledger', compact('clients'));
    }

    public function show($id)
    {
        try {
            $client = tbl_inventory::with('items')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $client
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading client details: ' . $e->getMessage()
            ], 500);
        }
    }
}
