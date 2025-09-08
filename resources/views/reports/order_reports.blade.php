@extends('layout.app')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Order Reports
</h2>

@if(session('success'))
    <div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-5 h-5 mr-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        {{ session('success') }}
        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle w-5 h-5 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        {{ session('error') }}
        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle w-5 h-5 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
@endif

<!-- Order Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-{{ count($statusCounts) > 4 ? '5' : count($statusCounts) }} gap-6 mt-5">
    @foreach($statusCounts as $status => $count)
        <div class="intro-y box p-5">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center
                    {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 
                       ($status === 'confirmed' ? 'bg-blue-100 text-blue-600' : 
                       ($status === 'delivered' ? 'bg-green-100 text-green-600' : 
                       ($status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600'))) }}">
                    @if($status === 'pending')
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    @elseif($status === 'confirmed')
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    @elseif($status === 'delivered')
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><path d="M16 3h5v5"></path><path d="M8 3H3v5"></path><path d="M12 22v-8.3a4 4 0 0 0-1.172-2.872L3 3"></path><path d="M21 3l-7.828 7.828A4 4 0 0 0 12 13.172V22"></path></svg>
                    @elseif($status === 'cancelled')
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle"><circle cx="12" cy="12" r="10"></circle></svg>
                    @endif
                </div>
                <div class="ml-4">
                    <div class="text-slate-500 text-xs uppercase tracking-wide font-medium">{{ ucfirst($status) }} Orders</div>
                    <div class="text-2xl font-bold text-slate-600">{{ $count }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Revenue Summary -->
<div class="intro-y box p-5 mt-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-slate-700">Total Revenue (Delivered Orders)</h3>
            <div class="text-3xl font-bold text-green-600">₱{{ number_format($totalRevenue ?? 0, 2) }}</div>
        </div>
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Filters Section -->
    <div class="intro-y col-span-12 lg:col-span-3">
        <div class="box p-5">
            <h3 class="text-lg font-medium text-slate-700 mb-4">Filters</h3>
            
            <div class="space-y-4">
                <div>
                    <label for="status-filter" class="form-label">Order Status</label>
                    <select id="status-filter" class="form-control" onchange="filterOrders()">
                        <option value="">All Status</option>
                        @foreach($statusCounts as $status => $count)
                            <option value="{{ $status }}">{{ ucfirst($status) }} ({{ $count }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="customer-filter" class="form-label">Customer</label>
                    <select id="customer-filter" class="form-control" onchange="filterOrders()">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date-from" class="form-label">Date From</label>
                    <input type="date" id="date-from" class="form-control" onchange="filterOrders()">
                </div>
                
                <div>
                    <label for="date-to" class="form-label">Date To</label>
                    <input type="date" id="date-to" class="form-control" onchange="filterOrders()">
                </div>
                
                <button class="btn btn-outline-secondary w-full" onclick="clearFilters()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw mr-2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path><path d="M21 3v5h-5"></path><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path><path d="M3 21v-5h5"></path></svg>
                    Clear Filters
                </button>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="intro-y col-span-12 lg:col-span-9">
        <div class="box">
            <div class="flex flex-col sm:flex-row sm:items-center p-5 border-b border-slate-200/60">
                <h2 class="font-medium text-base mr-auto">Order Reports</h2>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto">
                    <div class="w-56 relative text-slate-500">
                        <input type="text" id="search-input" class="form-control w-56 box pr-10" placeholder="Search orders..." onkeyup="debouncedSearch(this.value)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                </div>
            </div>
    
            <!-- Orders Table -->
            <div class="overflow-auto">
                <table class="table table-report">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">ORDER ID</th>
                            <th class="whitespace-nowrap">CUSTOMER</th>
                            <th class="whitespace-nowrap">ITEM</th>
                            <th class="whitespace-nowrap">CATEGORY</th>
                            <th class="text-center whitespace-nowrap">QUANTITY</th>
                            <th class="text-center whitespace-nowrap">TOTAL PRICE</th>
                            <th class="text-center whitespace-nowrap">DELIVERY DATE</th>
                            <th class="text-center whitespace-nowrap">STATUS</th>
                            <th class="text-center whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="orders-tbody">
                        @forelse($groupedOrders as $groupedOrder)
                        <tr class="intro-x hover:bg-slate-50" x-data="{ expanded: false }">
                            <td>
                                <div class="font-medium text-primary">{{ $groupedOrder['order_count'] }} Order(s)</div>
                                <div class="text-xs text-slate-500">Click to expand</div>
                            </td>
                            <td>
                                <div class="font-medium whitespace-nowrap">{{ $groupedOrder['customer']->customer_name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500">{{ $groupedOrder['customer']->address ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $groupedOrder['order_count'] }} Item(s)</div>
                                <div class="text-xs text-slate-500">Multiple items</div>
                            </td>
                            <td>
                                <div class="text-slate-500 text-sm">Mixed Categories</div>
                            </td>
                            <td class="text-center">
                                <span class="font-medium">{{ $groupedOrder['total_quantity'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="font-medium text-green-600">₱{{ number_format($groupedOrder['total_amount'], 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-slate-500">{{ $groupedOrder['delivery_date'] ? \Carbon\Carbon::parse($groupedOrder['delivery_date'])->format('M d, Y') : 'N/A' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $groupedOrder['status'] === 'delivered' ? 'bg-green-100 text-green-800' : 
                                       ($groupedOrder['status'] === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                       ($groupedOrder['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($groupedOrder['status']) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-primary btn-sm" onclick="toggleOrderDetails({{ $groupedOrder['customer_id'] }})" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </td>
                        </tr>
                        <!-- Expandable Order Details -->
                        <tr id="order-details-{{ $groupedOrder['customer_id'] }}" class="hidden">
                            <td colspan="9" class="bg-slate-50 p-0">
                                <div class="p-4">
                                    <h4 class="font-medium text-slate-700 mb-3">Order Details for {{ $groupedOrder['customer']->customer_name }}</h4>
                                    <div class="overflow-x-auto">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Item</th>
                                                    <th>Category</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($groupedOrder['orders'] as $order)
                                                <tr>
                                                    <td class="font-medium text-primary">#{{ $order->id }}</td>
                                                    <td>{{ $order->inventory_quantity->inventory->item_name ?? 'N/A' }}</td>
                                                    <td>{{ $order->inventory_quantity->inventory->category->category_name ?? 'N/A' }}</td>
                                                    <td class="text-center">{{ $order->quantity_order ?? 0 }}</td>
                                                    <td class="text-center text-green-600">₱{{ number_format($order->total_amount_price ?? 0, 2) }}</td>
                                                    <td class="text-center">
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                                               ($order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-outline-primary btn-sm" onclick="printOrder({{ $order->id }})" title="Print Order">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-slate-500">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/reports/order_reports.js') }}"></script>
@endsection