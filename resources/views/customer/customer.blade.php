@extends('layout.app')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Customer Management
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

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <button class="btn btn-primary shadow-md mr-2" onclick="showAddCustomerModal()">Add New Customer</button>
        
        <div class="hidden md:block mx-auto text-slate-500">
            @if(isset($customers) && $customers instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries
            @else
                Showing {{ $customers->count() }} entries
            @endif
        </div>
        
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" id="search-input" class="form-control w-56 box pr-10" placeholder="Search customers..." onkeyup="debouncedSearch(this.value)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
            </div>
        </div>
    </div>
    
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">CUSTOMER NAME</th>
                    <th class="whitespace-nowrap">ADDRESS</th>
                    <th class="text-center whitespace-nowrap">TOTAL ORDERS</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <!-- Main customer row -->
                <tr class="intro-x cursor-pointer hover:bg-slate-50" onclick="toggleCustomerDetails({{ $customer->id }})">
                    <td>
                        <div class="flex items-center">
                            <svg id="icon-{{ $customer->id }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transform transition-transform duration-200 mr-2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                            <span class="font-medium whitespace-nowrap">{{ $customer->customer_name }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-sm whitespace-nowrap">
                            {{ $customer->address }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="font-medium text-primary">{{ $customer->customer_order->count() }}</span>
                    </td>
                    <td class="w-40">
                        <div class="flex items-center justify-center {{ $customer->status === 'active' ? 'text-success' : 'text-danger' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> 
                            {{ ucfirst($customer->status) }}
                        </div>
                    </td>
                    <td class="table-report__action w-56" onclick="event.stopPropagation()">
                        <div class="flex justify-center items-center">
                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="editCustomer({{ $customer->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <button class="btn btn-outline-success btn-sm mr-1" onclick="showAddOrderModal({{ $customer->id }}, '{{ $customer->customer_name }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="prepareCustomerDelete({{ $customer->id }}, '{{ $customer->customer_name }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- Collapsible order details row -->
                <tr id="details-{{ $customer->id }}" class="hidden">
                    <td colspan="5" class="bg-slate-50 p-0">
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-medium">Orders for {{ $customer->customer_name }}</h4>
                                <!-- <button class="btn btn-outline-primary btn-sm" onclick="orderFromInventoryBrowse({{ $customer->id }}, '{{ $customer->customer_name }}'); showAddOrderModal({{ $customer->id }}, '{{ $customer->customer_name }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart mr-1"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="m1 1 4 4 14 8 2 2H8"></path></svg>
                                    Browse & Order
                                </button> -->
                            </div>
                            
                            @if($customer->customer_order->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap">#</th>
                                                <th class="whitespace-nowrap">Item</th>
                                                <th class="whitespace-nowrap">Quantity</th>
                                                <th class="whitespace-nowrap">Total Price</th>
                                                <th class="whitespace-nowrap">Delivery Date</th>
                                                <th class="whitespace-nowrap">Status</th>
                                                <th class="whitespace-nowrap">Reason</th>
                                                <th class="whitespace-nowrap">Created</th>
                                                <th class="whitespace-nowrap">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->customer_order as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="font-medium">
                                                        {{ $order->inventory_quantity->inventory->item_name ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-xs text-slate-500">
                                                        {{ $order->inventory_quantity->inventory->category->category_name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="font-medium">{{ $order->quantity_order }}</span>
                                                </td>
                                                <td>₱{{ number_format($order->total_amount_price, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->date_deliver)->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="px-2 py-1 rounded-full text-xs 
                                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                                           ($order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="max-w-xs truncate" title="{{ $order->reason }}">
                                                        {{ $order->reason ?: 'No reason' }}
                                                    </div>
                                                </td>
                                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-outline-primary btn-sm mr-1" onclick="editOrder({{ $order->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="prepareOrderDelete({{ $order->id }}, '{{ $order->inventory_quantity->inventory->item_name ?? 'N/A' }}', {{ $order->quantity_order }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-slate-500 text-center py-4">No orders found for this customer.</p>
                                <div class="text-center">
                                    <button class="btn btn-outline-primary btn-sm" onclick="showAddOrderModal({{ $customer->id }}, '{{ $customer->customer_name }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        Add First Order
                                    </button>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-500">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    @include('_partials.dynamic-pagination')
</div>

<!-- BEGIN: Add Customer Modal -->
<div id="add-customer-modal" class="modal" tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Customer</h2>
                <button class="btn-close" onclick="close_modal('#add-customer-modal')" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="add-customer-form" action="{{ route('customer.store') }}" method="POST" onsubmit="return handleAddCustomerSubmit(event)">
                @csrf
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label for="customer_name" class="form-label">Customer Name *</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                        </div>
                        <div class="col-span-12">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <!-- Category and Inventory Selection Section -->
                        <div class="col-span-12 border-t border-slate-200 pt-4 mt-4">
                            <h3 class="text-md font-medium text-slate-700 mb-3">Browse Available Items</h3>
                            
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-6">
                                    <label for="browse_category" class="form-label">Select Category</label>
                                    <select id="browse_category" class="form-control" onchange="showInventoryByCategory(this.value)">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label for="browse_inventory" class="form-label">Select Item</label>
                                    <select id="browse_inventory" class="form-control" onchange="showInventoryQuantities(this.value)" disabled>
                                        <option value="">Select an item</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Inventory Details Display -->
                            <div id="inventory-details" class="hidden mt-4 p-4 bg-slate-50 rounded-lg">
                                <h4 class="font-medium text-slate-700 mb-3">Item Details</h4>
                                <div id="inventory-info" class="grid grid-cols-2 gap-4 mb-4">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                                
                                <!-- Quantities Table -->
                                <div id="quantities-section" class="hidden">
                                    <h5 class="font-medium text-slate-700 mb-2">Available Quantities (Click to Order)</h5>
                                    <div class="overflow-x-auto">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Price Date</th>
                                                    <th>Low Stock</th>
                                                    <th>Status</th>
                                                    <th>Note</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="quantities-tbody">
                                                <!-- Will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-span-12">
                            <label for="status" class="form-label">Status *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Order Summary Section for New Customer -->
                    <div id="new-customer-order-summary" class="hidden mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-green-800">Orders to be Created</h4>
                            <!-- <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearNewCustomerOrders()" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2 2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                Clear All
                            </button> -->
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Delivery Date</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="new-customer-orders-tbody">
                                    <!-- Orders will be added here -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-green-100">
                                        <td colspan="4" class="text-right font-medium">Grand Total:</td>
                                        <td id="new-customer-grand-total" class="font-bold">₱0.00</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" onclick="close_modal('#add-customer-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Add Customer Modal -->

<!-- BEGIN: Edit Customer Modal -->
<div id="edit-customer-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Customer</h2>
                <button class="btn-close" onclick="close_modal('#edit-customer-modal')" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="edit-customer-form" method="POST" onsubmit="return handleEditCustomerSubmit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_customer_id" name="customer_id">
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label for="edit_customer_name" class="form-label">Customer Name *</label>
                            <input type="text" id="edit_customer_name" name="customer_name" class="form-control" required>
                        </div>
                                    <div class="col-span-12">
                            <label for="edit_address" class="form-label">Address *</label>
                            <textarea id="edit_address" name="address" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <!-- Category and Inventory Selection Section for Edit -->
                        <div class="col-span-12 border-t border-slate-200 pt-4 mt-4">
                            <h3 class="text-md font-medium text-slate-700 mb-3">Browse Available Items</h3>
                            
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-6">
                                    <label for="edit_browse_category" class="form-label">Select Category</label>
                                    <select id="edit_browse_category" class="form-control" onchange="showEditInventoryByCategory(this.value)">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label for="edit_browse_inventory" class="form-label">Select Item</label>
                                    <select id="edit_browse_inventory" class="form-control" onchange="showEditInventoryQuantities(this.value)" disabled>
                                        <option value="">Select an item</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Inventory Details Display for Edit -->
                            <div id="edit-inventory-details" class="hidden mt-4 p-4 bg-slate-50 rounded-lg">
                                <h4 class="font-medium text-slate-700 mb-3">Item Details</h4>
                                <div id="edit-inventory-info" class="grid grid-cols-2 gap-4 mb-4">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                                
                                <!-- Quantities Table for Edit -->
                                <div id="edit-quantities-section" class="hidden">
                                    <h5 class="font-medium text-slate-700 mb-2">Available Quantities (Click to Order)</h5>
                                    <div class="overflow-x-auto">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Price Date</th>
                                                    <th>Low Stock</th>
                                                    <th>Status</th>
                                                    <th>Note</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="edit-quantities-tbody">
                                                <!-- Will be populated by JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-span-12">
                            <label for="edit_status" class="form-label">Status *</label>
                            <select id="edit_status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" onclick="close_modal('#edit-customer-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Edit Customer Modal -->

<!-- BEGIN: Delete Customer Modal -->
<div id="delete-customer-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Delete Customer</h2>
                <button class="btn-close" onclick="closeDeleteCustomerModal()" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-slate-500">Are you sure you want to delete this customer? This action cannot be undone.</p>
                <div class="mt-4 p-4 bg-slate-50 rounded-lg">
                    <div class="mb-2">
                        <strong>Customer Name:</strong> <span id="delete-customer-name" class="text-slate-700"></span>
                    </div>
                    <div class="text-sm text-slate-500 mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle inline mr-1"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        Warning: Customer with orders cannot be deleted. Delete orders first.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" onclick="closeDeleteCustomerModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmCustomerDelete()">Delete Customer</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Customer Modal -->

<!-- BEGIN: Add Order Modal -->
<div id="add-order-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Order</h2>
                <button class="btn-close" onclick="close_modal('#add-order-modal')" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="add-order-form" method="POST" onsubmit="return handleAddOrderSubmit(event)">
                @csrf
                <input type="hidden" id="order_customer_id" name="customer_id">
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <strong>Customer:</strong> <span id="order-customer-name" class="text-slate-700"></span>
                            </div>
                            <div class="text-xs text-blue-600">
                                💡 Tip: Browse available items below or select from dropdown
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label for="inventory_quantity_id" class="form-label">Select Item *</label>
                            <select id="inventory_quantity_id" name="inventory_quantity_id" class="form-control" required onchange="updateItemDetails()">
                                <option value="">Select an item</option>
                                @foreach($inventoryQuantities as $invQty)
                                    <option value="{{ $invQty->id }}" 
                                            data-price="{{ $invQty->price }}" 
                                            data-available="{{ $invQty->quantity }}"
                                            data-item-name="{{ $invQty->inventory->item_name }}"
                                            data-category="{{ $invQty->inventory->category->category_name ?? 'N/A' }}">
                                        {{ $invQty->inventory->item_name }} ({{ $invQty->inventory->category->category_name ?? 'N/A' }}) - Available: {{ $invQty->quantity }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="quantity_order" class="form-label">Quantity *</label>
                            <input type="number" id="quantity_order" name="quantity_order" class="form-control" min="1" required onchange="calculateTotal()">
                            <div id="quantity-available" class="text-xs text-slate-500 mt-1"></div>
                        </div>
                        <div class="col-span-6">
                            <label for="date_deliver" class="form-label">Delivery Date *</label>
                            <input type="date" id="date_deliver" name="date_deliver" class="form-control" required>
                        </div>
                        <div class="col-span-6">
                            <label for="order_status" class="form-label">Status *</label>
                            <select id="order_status" name="status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="total_amount_price" class="form-label">Total Amount *</label>
                            <input type="number" id="total_amount_price" name="total_amount_price" class="form-control" min="0" step="0.01" required readonly>
                        </div>
                        <div class="col-span-6">
                            <label for="reason" class="form-label">Reason/Notes</label>
                            <textarea id="reason" name="reason" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <!-- Inventory Browse Section in Order Modal -->
                    <div class="border-t border-slate-200 pt-4 mt-4">
                        <h4 class="text-md font-medium text-slate-700 mb-3">Browse Available Items</h4>
                        
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-6">
                                <label for="order_browse_category" class="form-label">Select Category</label>
                                <select id="order_browse_category" class="form-control" onchange="showOrderInventoryByCategory(this.value)">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-6">
                                <label for="order_browse_inventory" class="form-label">Select Item</label>
                                <select id="order_browse_inventory" class="form-control" onchange="showOrderInventoryQuantities(this.value)" disabled>
                                    <option value="">Select an item</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Inventory Details Display for Order Modal -->
                        <div id="order-inventory-details" class="hidden mt-4 p-4 bg-slate-50 rounded-lg">
                            <h4 class="font-medium text-slate-700 mb-3">Item Details</h4>
                            <div id="order-inventory-info" class="grid grid-cols-2 gap-4 mb-4">
                                <!-- Will be populated by JavaScript -->
                            </div>
                            
                            <!-- Quantities Table for Order Modal -->
                            <div id="order-quantities-section" class="hidden">
                                <h5 class="font-medium text-slate-700 mb-2">Available Quantities (Click to Order)</h5>
                                <div class="overflow-x-auto">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Price Date</th>
                                                <th>Low Stock</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order-quantities-tbody">
                                            <!-- Will be populated by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" onclick="close_modal('#add-order-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Add Order Modal -->

<!-- BEGIN: Edit Order Modal -->
<div id="edit-order-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Order</h2>
                <button class="btn-close" onclick="close_modal('#edit-order-modal')" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="edit-order-form" method="POST" onsubmit="return handleEditOrderSubmit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_order_id" name="order_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Customer:</strong> <span id="edit-order-customer-name" class="text-slate-700"></span>
                    </div>
                    
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label for="edit_inventory_quantity_id" class="form-label">Select Item *</label>
                            <select id="edit_inventory_quantity_id" name="inventory_quantity_id" class="form-control" required onchange="updateEditItemDetails()">
                                <option value="">Select an item</option>
                                @foreach($inventoryQuantities as $invQty)
                                    <option value="{{ $invQty->id }}" 
                                            data-price="{{ $invQty->price }}" 
                                            data-available="{{ $invQty->quantity }}"
                                            data-item-name="{{ $invQty->inventory->item_name }}"
                                            data-category="{{ $invQty->inventory->category->category_name ?? 'N/A' }}">
                                        {{ $invQty->inventory->item_name }} ({{ $invQty->inventory->category->category_name ?? 'N/A' }}) - Available: {{ $invQty->quantity }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_quantity_order" class="form-label">Quantity *</label>
                            <input type="number" id="edit_quantity_order" name="quantity_order" class="form-control" min="1" required onchange="calculateEditTotal()">
                            <div id="edit-quantity-available" class="text-xs text-slate-500 mt-1"></div>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_date_deliver" class="form-label">Delivery Date *</label>
                            <input type="date" id="edit_date_deliver" name="date_deliver" class="form-control" required>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_order_status" class="form-label">Status *</label>
                            <select id="edit_order_status" name="status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_total_amount_price" class="form-label">Total Amount *</label>
                            <input type="number" id="edit_total_amount_price" name="total_amount_price" class="form-control" min="0" step="0.01" required readonly>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_reason" class="form-label">Reason/Notes</label>
                            <textarea id="edit_reason" name="reason" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" onclick="close_modal('#edit-order-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Edit Order Modal -->

<!-- BEGIN: Delete Order Modal -->
<div id="delete-order-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Delete Order</h2>
                <button class="btn-close" onclick="closeDeleteOrderModal()" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-slate-500">Are you sure you want to delete this order? This action cannot be undone.</p>
                <div class="mt-4 p-4 bg-slate-50 rounded-lg">
                    <div class="mb-2">
                        <strong>Item:</strong> <span id="delete-order-item" class="text-slate-700"></span>
                    </div>
                    <div class="mb-2">
                        <strong>Quantity:</strong> <span id="delete-order-quantity" class="text-slate-700"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" onclick="closeDeleteOrderModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmOrderDelete()">Delete Order</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Order Modal -->

<!-- BEGIN: Quantity Input Modal -->
<div id="quantity-input-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Order Quantity</h2>
                <button class="btn-close" onclick="close_modal('#quantity-input-modal')" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-4 p-4 bg-slate-50 rounded-lg">
                    <h4 class="font-medium text-slate-700 mb-2">Selected Item</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <strong>Item:</strong> <span id="selected-item-name"></span>
                        </div>
                        <div>
                            <strong>Category:</strong> <span id="selected-category-name"></span>
                        </div>
                        <div>
                            <strong>Available:</strong> <span id="selected-available-qty" class="font-medium text-green-600"></span>
                        </div>
                        <div>
                            <strong>Price:</strong> <span id="selected-price" class="font-medium text-blue-600"></span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <label for="order_qty_input" class="form-label">Quantity to Order *</label>
                        <input type="number" id="order_qty_input" class="form-control" min="1" required onchange="calculateOrderTotal()">
                        <div id="qty-error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div class="col-span-6">
                        <label for="order_total_display" class="form-label">Total Amount</label>
                        <input type="text" id="order_total_display" class="form-control" readonly>
                    </div>
                    <div class="col-span-12">
                        <label for="order_delivery_date" class="form-label">Delivery Date *</label>
                        <input type="date" id="order_delivery_date" class="form-control" required>
                    </div>
                    <div class="col-span-12">
                        <label for="order_notes" class="form-label">Order Notes</label>
                        <textarea id="order_notes" class="form-control" rows="2" placeholder="Optional notes for this order..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" onclick="close_modal('#quantity-input-modal')">Cancel</button>
                                        <button type="button" id="confirm-order-btn" class="btn btn-primary">Add to Order</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Quantity Input Modal -->

<script>
// Customer variables
let currentDeleteCustomerId = null;
let currentDeleteOrderId = null;

// Inventory data for category/inventory browsing
const inventoryData = @json($inventories);
const categoriesData = @json($categories);

// Quantity selection variables
let selectedQuantityRecord = null;
let selectedInventoryItem = null;
let orderContext = null; // 'new' or 'existing-customer-id'

// Order summary variables
let pendingOrders = []; // Array to store orders before saving to database

// Toggle customer details
function toggleCustomerDetails(customerId) {
    const detailsPanel = document.getElementById(`details-${customerId}`);
    const icon = document.getElementById(`icon-${customerId}`);
    
    if (detailsPanel.classList.contains('hidden')) {
        // Show details
        detailsPanel.classList.remove('hidden');
        icon.classList.add('rotate-90');
    } else {
        // Hide details
        detailsPanel.classList.add('hidden');
        icon.classList.remove('rotate-90');
    }
}

// Customer Modal Functions
function showAddCustomerModal() {
    // Reset form
    document.getElementById('add-customer-form').reset();
    
    // Clear any pending orders
    pendingOrders = [];
    updateNewCustomerOrderSummary();
    
    // Reset browse selections
    document.getElementById('browse_category').value = '';
    document.getElementById('browse_inventory').value = '';
    document.getElementById('browse_inventory').disabled = true;
    document.getElementById('inventory-details').classList.add('hidden');
    
    // Set order context for new customer
    orderContext = 'new';
    
    open_modal('#add-customer-modal');
}

function editCustomer(customerId) {
    console.log('Editing customer:', customerId);
    
    // Show loading state
    showNotification('info', 'Loading customer data...');
    
    // Fetch customer data
    fetch(`/customer/${customerId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateEditCustomerForm(data.data);
            open_modal('#edit-customer-modal');
        } else {
            showNotification('error', data.message || 'Error loading customer data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

function populateEditCustomerForm(customer) {
    document.getElementById('edit_customer_id').value = customer.id;
    document.getElementById('edit_customer_name').value = customer.customer_name;
    document.getElementById('edit_address').value = customer.address;
    document.getElementById('edit_status').value = customer.status;
}

function prepareCustomerDelete(customerId, customerName) {
    currentDeleteCustomerId = customerId;
    document.getElementById('delete-customer-name').textContent = customerName;
    open_modal('#delete-customer-modal');
}

function closeDeleteCustomerModal() {
    close_modal('#delete-customer-modal');
    currentDeleteCustomerId = null;
}

function confirmCustomerDelete() {
    if (!currentDeleteCustomerId) {
        showNotification('error', 'No customer selected for deletion');
        return;
    }
    
    const deleteBtn = document.querySelector('#delete-customer-modal .btn-danger');
    const originalText = deleteBtn.textContent;
    deleteBtn.disabled = true;
    deleteBtn.textContent = 'Deleting...';
    
    fetch(`/customer/${currentDeleteCustomerId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Customer deleted successfully!');
            closeDeleteCustomerModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error deleting customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        deleteBtn.disabled = false;
        deleteBtn.textContent = originalText;
        currentDeleteCustomerId = null;
    });
}

// Order Modal Functions
function showAddOrderModal(customerId, customerName) {
    // Reset form
    document.getElementById('add-order-form').reset();
    document.getElementById('order_customer_id').value = customerId;
    document.getElementById('order-customer-name').textContent = customerName;
    
    // Set default date to today
    document.getElementById('date_deliver').value = new Date().toISOString().split('T')[0];
    
    // Set order context for quantity selection
    orderContext = customerId;
    
    open_modal('#add-order-modal');
}

// Function to handle direct ordering from inventory browsing in existing customer context
function orderFromInventoryBrowse(customerId, customerName) {
    orderContext = customerId;
    // The quantity selection will work with this context
}

function editOrder(orderId) {
    console.log('Editing order:', orderId);
    
    // Show loading state
    showNotification('info', 'Loading order data...');
    
    // Fetch order data
    fetch(`/customer/order/${orderId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateEditOrderForm(data.data);
            open_modal('#edit-order-modal');
        } else {
            showNotification('error', data.message || 'Error loading order data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

function populateEditOrderForm(order) {
    document.getElementById('edit_order_id').value = order.id;
    document.getElementById('edit-order-customer-name').textContent = order.customer.customer_name;
    document.getElementById('edit_inventory_quantity_id').value = order.inventory_quantity_id;
    document.getElementById('edit_quantity_order').value = order.quantity_order;
    document.getElementById('edit_date_deliver').value = order.date_deliver;
    document.getElementById('edit_order_status').value = order.status;
    document.getElementById('edit_total_amount_price').value = order.total_amount_price;
    document.getElementById('edit_reason').value = order.reason || '';
    
    // Update item details
    updateEditItemDetails();
}

function prepareOrderDelete(orderId, itemName, quantity) {
    currentDeleteOrderId = orderId;
    document.getElementById('delete-order-item').textContent = itemName;
    document.getElementById('delete-order-quantity').textContent = quantity;
    open_modal('#delete-order-modal');
}

function closeDeleteOrderModal() {
    close_modal('#delete-order-modal');
    currentDeleteOrderId = null;
}

function confirmOrderDelete() {
    if (!currentDeleteOrderId) {
        showNotification('error', 'No order selected for deletion');
        return;
    }
    
    const deleteBtn = document.querySelector('#delete-order-modal .btn-danger');
    const originalText = deleteBtn.textContent;
    deleteBtn.disabled = true;
    deleteBtn.textContent = 'Deleting...';
    
    fetch(`/customer/order/${currentDeleteOrderId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Order deleted successfully!');
            closeDeleteOrderModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error deleting order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        deleteBtn.disabled = false;
        deleteBtn.textContent = originalText;
        currentDeleteOrderId = null;
    });
}

// Item selection and calculation functions
function updateItemDetails() {
    const select = document.getElementById('inventory_quantity_id');
    const selectedOption = select.options[select.selectedIndex];
    const quantityInput = document.getElementById('quantity_order');
    const availableDiv = document.getElementById('quantity-available');
    
    if (selectedOption.value) {
        const available = selectedOption.getAttribute('data-available');
        availableDiv.textContent = `Available: ${available} items`;
        quantityInput.max = available;
        
        // Calculate total when item is selected
        calculateTotal();
    } else {
        availableDiv.textContent = '';
        quantityInput.max = '';
        document.getElementById('total_amount_price').value = '';
    }
}

function updateEditItemDetails() {
    const select = document.getElementById('edit_inventory_quantity_id');
    const selectedOption = select.options[select.selectedIndex];
    const quantityInput = document.getElementById('edit_quantity_order');
    const availableDiv = document.getElementById('edit-quantity-available');
    
    if (selectedOption.value) {
        const available = selectedOption.getAttribute('data-available');
        availableDiv.textContent = `Available: ${available} items`;
        quantityInput.max = available;
        
        // Calculate total when item is selected
        calculateEditTotal();
    } else {
        availableDiv.textContent = '';
        quantityInput.max = '';
        document.getElementById('edit_total_amount_price').value = '';
    }
}

function calculateTotal() {
    const select = document.getElementById('inventory_quantity_id');
    const selectedOption = select.options[select.selectedIndex];
    const quantity = document.getElementById('quantity_order').value;
    const totalInput = document.getElementById('total_amount_price');
    
    if (selectedOption.value && quantity) {
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const total = price * parseInt(quantity);
        totalInput.value = total.toFixed(2);
    } else {
        totalInput.value = '';
    }
}

function calculateEditTotal() {
    const select = document.getElementById('edit_inventory_quantity_id');
    const selectedOption = select.options[select.selectedIndex];
    const quantity = document.getElementById('edit_quantity_order').value;
    const totalInput = document.getElementById('edit_total_amount_price');
    
    if (selectedOption.value && quantity) {
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const total = price * parseInt(quantity);
        totalInput.value = total.toFixed(2);
    } else {
        totalInput.value = '';
    }
}

// Form submission handlers
function handleAddCustomerSubmit(e) {
    e.preventDefault();
    
    const form = document.getElementById('add-customer-form');
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving Customer...';
    
    // First create the customer
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const customerId = data.data.id;
            
            // If there are pending orders, create them
            if (pendingOrders.length > 0) {
                submitBtn.textContent = 'Saving Orders...';
                return createAllPendingOrders(customerId);
            } else {
                // No orders to create, just show success
                showNotification('success', 'Customer created successfully!');
                close_modal('#add-customer-modal');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return Promise.resolve();
            }
        } else {
            throw new Error(data.message || 'Error creating customer');
        }
    })
    .then(() => {
        // All operations completed successfully
        if (pendingOrders.length > 0) {
            showNotification('success', `Customer and ${pendingOrders.length} order(s) created successfully!`);
            
            // Clear pending orders
            pendingOrders = [];
            updateNewCustomerOrderSummary();
        }
        
        close_modal('#add-customer-modal');
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', error.message || 'Network error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
    
    return false;
}

async function createAllPendingOrders(customerId) {
    const orderPromises = pendingOrders.map(order => {
        const orderData = {
            customer_id: customerId,
            inventory_quantity_id: order.inventory_quantity_id,
            quantity_order: order.quantity_order,
            date_deliver: order.date_deliver,
            status: order.status,
            reason: order.reason,
            total_amount_price: order.total_amount_price
        };
        
        return fetch('/customer/order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Error creating order');
            }
            return data;
        });
    });
    
    try {
        const results = await Promise.all(orderPromises);
        return results;
    } catch (error) {
        console.error('Error creating orders:', error);
        throw error;
    }
}

function handleEditCustomerSubmit(e) {
    e.preventDefault();
    
    const form = document.getElementById('edit-customer-form');
    const formData = new FormData(form);
    const customerId = document.getElementById('edit_customer_id').value;
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    fetch(`/customer/${customerId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Customer updated successfully!');
            close_modal('#edit-customer-modal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error updating customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
    
    return false;
}

function handleAddOrderSubmit(e) {
    e.preventDefault();
    
    const form = document.getElementById('add-order-form');
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    fetch('/customer/order', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Order created successfully!');
            close_modal('#add-order-modal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error creating order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
    
    return false;
}

function handleEditOrderSubmit(e) {
    e.preventDefault();
    
    const form = document.getElementById('edit-order-form');
    const formData = new FormData(form);
    const orderId = document.getElementById('edit_order_id').value;
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    fetch(`/customer/order/${orderId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Order updated successfully!');
            close_modal('#edit-order-modal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error updating order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
    
    return false;
}

// Search functionality
function searchCustomers(searchTerm) {
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    if (!searchTerm || searchTerm.trim() === '') {
        // Show all rows if search is empty
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    const searchLower = searchTerm.toLowerCase().trim();
    let visibleCount = 0;
    
    rows.forEach(row => {
        // Skip rows with colspan (like "No customers found")
        if (row.querySelector('td[colspan]')) {
            row.style.display = 'none';
            return;
        }
        
        const customerName = row.querySelector('td:first-child span')?.textContent?.toLowerCase() || '';
        const address = row.querySelector('td:nth-child(2) div')?.textContent?.toLowerCase() || '';
        const status = row.querySelector('td:nth-child(4) div')?.textContent?.toLowerCase() || '';
        
        // Check if any field contains the search term
        const isMatch = customerName.includes(searchLower) || 
                       address.includes(searchLower) || 
                       status.includes(searchLower);
        
        if (isMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
}

// Enhanced search with debouncing
let searchTimeout;
function debouncedSearch(searchTerm) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchCustomers(searchTerm);
    }, 300); // Wait 300ms after user stops typing
}

// Show notification
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Category and Inventory Browsing Functions
function showInventoryByCategory(categoryId) {
    const inventorySelect = document.getElementById('browse_inventory');
    const inventoryDetails = document.getElementById('inventory-details');
    
    // Reset inventory select and hide details
    inventorySelect.innerHTML = '<option value="">Select an item</option>';
    inventorySelect.disabled = true;
    inventoryDetails.classList.add('hidden');
    
    if (!categoryId) {
        return;
    }
    
    // Filter inventories by category
    const filteredInventories = inventoryData.filter(inventory => 
        inventory.category_id == categoryId
    );
    
    if (filteredInventories.length > 0) {
        inventorySelect.disabled = false;
        
        filteredInventories.forEach(inventory => {
            const option = document.createElement('option');
            option.value = inventory.id;
            option.textContent = `${inventory.item_name} ${inventory.description ? '(' + inventory.description + ')' : ''}`;
            inventorySelect.appendChild(option);
        });
    }
}

function showInventoryQuantities(inventoryId) {
    const inventoryDetails = document.getElementById('inventory-details');
    const inventoryInfo = document.getElementById('inventory-info');
    const quantitiesSection = document.getElementById('quantities-section');
    const quantitiesTbody = document.getElementById('quantities-tbody');
    
    if (!inventoryId) {
        inventoryDetails.classList.add('hidden');
        return;
    }
    
    // Find the selected inventory
    const inventory = inventoryData.find(item => item.id == inventoryId);
    
    if (!inventory) {
        return;
    }
    
    // Show inventory details
    inventoryInfo.innerHTML = `
        <div>
            <label class="text-sm font-semibold text-slate-600">Item Name:</label>
            <p class="text-base text-slate-800">${inventory.item_name}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Category:</label>
            <p class="text-base text-slate-800">${inventory.category.category_name}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Description:</label>
            <p class="text-base text-slate-800">${inventory.description || 'No description'}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Status:</label>
            <p class="text-base text-slate-800">${inventory.status}</p>
        </div>
    `;
    
    // Show quantities if available
    if (inventory.quantities && inventory.quantities.length > 0) {
        quantitiesTbody.innerHTML = '';
        
        inventory.quantities.forEach(qty => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer hover:bg-blue-50 transition-colors duration-200';
            row.setAttribute('data-quantity-id', qty.id);
            row.setAttribute('data-available', qty.quantity);
            row.setAttribute('data-price', qty.price || 0);
            row.onclick = () => selectQuantityForOrder(qty, inventory, 'new');
            
            row.innerHTML = `
                <td>
                    <span class="font-medium ${qty.quantity <= 3 ? 'text-red-600' : (qty.quantity <= 5 ? 'text-yellow-600' : 'text-green-600')}">
                        ${qty.quantity}
                    </span>
                </td>
                <td>${qty.price ? '₱' + parseFloat(qty.price).toFixed(2) : 'N/A'}</td>
                <td>${qty.price_effective_date || 'N/A'}</td>
                <td>
                    ${qty.is_low_stocks ? 
                        '<span class="text-red-600"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg></span>' : 
                        '<span class="text-green-600"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"></polyline></svg></span>'
                    }
                </td>
                <td>
                    <span class="px-2 py-1 rounded-full text-xs ${qty.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${qty.status}
                    </span>
                </td>
                <td>
                    <div class="max-w-xs truncate" title="${qty.note || ''}">
                        ${qty.note || 'No notes'}
                    </div>
                </td>
                <td>
                    <span class="text-xs text-blue-600 font-medium">Click to order</span>
                </td>
            `;
            quantitiesTbody.appendChild(row);
        });
        
        quantitiesSection.classList.remove('hidden');
    } else {
        quantitiesSection.classList.add('hidden');
    }
    
    inventoryDetails.classList.remove('hidden');
}

// Edit modal functions
function showEditInventoryByCategory(categoryId) {
    const inventorySelect = document.getElementById('edit_browse_inventory');
    const inventoryDetails = document.getElementById('edit-inventory-details');
    
    // Reset inventory select and hide details
    inventorySelect.innerHTML = '<option value="">Select an item</option>';
    inventorySelect.disabled = true;
    inventoryDetails.classList.add('hidden');
    
    if (!categoryId) {
        return;
    }
    
    // Filter inventories by category
    const filteredInventories = inventoryData.filter(inventory => 
        inventory.category_id == categoryId
    );
    
    if (filteredInventories.length > 0) {
        inventorySelect.disabled = false;
        
        filteredInventories.forEach(inventory => {
            const option = document.createElement('option');
            option.value = inventory.id;
            option.textContent = `${inventory.item_name} ${inventory.description ? '(' + inventory.description + ')' : ''}`;
            inventorySelect.appendChild(option);
        });
    }
}

function showEditInventoryQuantities(inventoryId) {
    const inventoryDetails = document.getElementById('edit-inventory-details');
    const inventoryInfo = document.getElementById('edit-inventory-info');
    const quantitiesSection = document.getElementById('edit-quantities-section');
    const quantitiesTbody = document.getElementById('edit-quantities-tbody');
    
    if (!inventoryId) {
        inventoryDetails.classList.add('hidden');
        return;
    }
    
    // Find the selected inventory
    const inventory = inventoryData.find(item => item.id == inventoryId);
    
    if (!inventory) {
        return;
    }
    
    // Show inventory details
    inventoryInfo.innerHTML = `
        <div>
            <label class="text-sm font-semibold text-slate-600">Item Name:</label>
            <p class="text-base text-slate-800">${inventory.item_name}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Category:</label>
            <p class="text-base text-slate-800">${inventory.category.category_name}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Description:</label>
            <p class="text-base text-slate-800">${inventory.description || 'No description'}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Status:</label>
            <p class="text-base text-slate-800">${inventory.status}</p>
        </div>
    `;
    
    // Show quantities if available
    if (inventory.quantities && inventory.quantities.length > 0) {
        quantitiesTbody.innerHTML = '';
        
        inventory.quantities.forEach(qty => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer hover:bg-blue-50 transition-colors duration-200';
            row.setAttribute('data-quantity-id', qty.id);
            row.setAttribute('data-available', qty.quantity);
            row.setAttribute('data-price', qty.price || 0);
            row.onclick = () => selectQuantityForOrder(qty, inventory, 'edit');
            
            row.innerHTML = `
                <td>
                    <span class="font-medium ${qty.quantity <= 3 ? 'text-red-600' : (qty.quantity <= 5 ? 'text-yellow-600' : 'text-green-600')}">
                        ${qty.quantity}
                    </span>
                </td>
                <td>${qty.price ? '₱' + parseFloat(qty.price).toFixed(2) : 'N/A'}</td>
                <td>${qty.price_effective_date || 'N/A'}</td>
                <td>
                    ${qty.is_low_stocks ? 
                        '<span class="text-red-600"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg></span>' : 
                        '<span class="text-green-600"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"></polyline></svg></span>'
                    }
                </td>
                <td>
                    <span class="px-2 py-1 rounded-full text-xs ${qty.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${qty.status}
                    </span>
                </td>
                <td>
                    <div class="max-w-xs truncate" title="${qty.note || ''}">
                        ${qty.note || 'No notes'}
                    </div>
                </td>
                <td>
                    <span class="text-xs text-blue-600 font-medium">Click to order</span>
                </td>
            `;
            quantitiesTbody.appendChild(row);
        });
        
        quantitiesSection.classList.remove('hidden');
    } else {
        quantitiesSection.classList.add('hidden');
    }
    
    inventoryDetails.classList.remove('hidden');
}

// Order modal inventory browsing functions
function showOrderInventoryByCategory(categoryId) {
    const inventorySelect = document.getElementById('order_browse_inventory');
    const inventoryDetails = document.getElementById('order-inventory-details');
    
    // Reset inventory select and hide details
    inventorySelect.innerHTML = '<option value="">Select an item</option>';
    inventorySelect.disabled = true;
    inventoryDetails.classList.add('hidden');
    
    if (!categoryId) {
        return;
    }
    
    // Filter inventories by category
    const filteredInventories = inventoryData.filter(inventory => 
        inventory.category_id == categoryId
    );
    
    if (filteredInventories.length > 0) {
        inventorySelect.disabled = false;
        
        filteredInventories.forEach(inventory => {
            const option = document.createElement('option');
            option.value = inventory.id;
            option.textContent = `${inventory.item_name} ${inventory.description ? '(' + inventory.description + ')' : ''}`;
            inventorySelect.appendChild(option);
        });
    }
}

function showOrderInventoryQuantities(inventoryId) {
    const inventoryDetails = document.getElementById('order-inventory-details');
    const inventoryInfo = document.getElementById('order-inventory-info');
    const quantitiesSection = document.getElementById('order-quantities-section');
    const quantitiesTbody = document.getElementById('order-quantities-tbody');
    
    if (!inventoryId) {
        inventoryDetails.classList.add('hidden');
        return;
    }
    
    // Find the selected inventory
    const inventory = inventoryData.find(item => item.id == inventoryId);
    
    if (!inventory) {
        return;
    }
    
    // Show inventory details
    inventoryInfo.innerHTML = `
        <div>
            <label class="text-sm font-semibold text-slate-600">Item Name:</label>
            <p class="text-base text-slate-800">${inventory.item_name}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Category:</label>
            <p class="text-base text-slate-800">${inventory.category.category_name}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Description:</label>
            <p class="text-base text-slate-800">${inventory.description || 'No description'}</p>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600">Status:</label>
            <p class="text-base text-slate-800">${inventory.status}</p>
        </div>
    `;
    
    // Show quantities if available
    if (inventory.quantities && inventory.quantities.length > 0) {
        quantitiesTbody.innerHTML = '';
        
        inventory.quantities.forEach(qty => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer hover:bg-blue-50 transition-colors duration-200';
            row.setAttribute('data-quantity-id', qty.id);
            row.setAttribute('data-available', qty.quantity);
            row.setAttribute('data-price', qty.price || 0);
            row.onclick = () => selectQuantityForOrder(qty, inventory, orderContext);
            
            row.innerHTML = `
                <td>
                    <span class="font-medium ${qty.quantity <= 3 ? 'text-red-600' : (qty.quantity <= 5 ? 'text-yellow-600' : 'text-green-600')}">
                        ${qty.quantity}
                    </span>
                </td>
                <td>${qty.price ? '₱' + parseFloat(qty.price).toFixed(2) : 'N/A'}</td>
                <td>${qty.price_effective_date || 'N/A'}</td>
                <td>
                    ${qty.is_low_stocks ? 
                        '<span class="text-red-600"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg></span>' : 
                        '<span class="text-green-600"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"></polyline></svg></span>'
                    }
                </td>
                <td>
                    <span class="px-2 py-1 rounded-full text-xs ${qty.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${qty.status}
                    </span>
                </td>
                <td>
                    <div class="max-w-xs truncate" title="${qty.note || ''}">
                        ${qty.note || 'No notes'}
                    </div>
                </td>
                <td>
                    <span class="text-xs text-blue-600 font-medium">Click to order</span>
                </td>
            `;
            quantitiesTbody.appendChild(row);
        });
        
        quantitiesSection.classList.remove('hidden');
    } else {
        quantitiesSection.classList.add('hidden');
    }
    
    inventoryDetails.classList.remove('hidden');
}

// Quantity selection and ordering functions
function selectQuantityForOrder(quantityRecord, inventoryItem, context) {
    // Store the selected data
    selectedQuantityRecord = quantityRecord;
    selectedInventoryItem = inventoryItem;
    orderContext = context;
    
    // Populate the quantity input modal
    document.getElementById('selected-item-name').textContent = inventoryItem.item_name;
    document.getElementById('selected-category-name').textContent = inventoryItem.category.category_name;
    document.getElementById('selected-available-qty').textContent = quantityRecord.quantity + ' available';
    document.getElementById('selected-price').textContent = quantityRecord.price ? '₱' + parseFloat(quantityRecord.price).toFixed(2) : 'N/A';
    
    // Reset form fields
    document.getElementById('order_qty_input').value = '';
    document.getElementById('order_qty_input').max = quantityRecord.quantity;
    document.getElementById('order_total_display').value = '';
    document.getElementById('order_delivery_date').value = new Date().toISOString().split('T')[0]; // Default to today
    document.getElementById('order_notes').value = '';
    
    // Clear any previous errors
    const errorDiv = document.getElementById('qty-error');
    errorDiv.classList.add('hidden');
    errorDiv.textContent = '';
    
    // Show the modal
    open_modal('#quantity-input-modal');
}

function calculateOrderTotal() {
    const quantityInput = document.getElementById('order_qty_input');
    const totalDisplay = document.getElementById('order_total_display');
    const errorDiv = document.getElementById('qty-error');
    
    const orderQty = parseInt(quantityInput.value) || 0;
    const availableQty = selectedQuantityRecord ? selectedQuantityRecord.quantity : 0;
    const price = selectedQuantityRecord ? parseFloat(selectedQuantityRecord.price) || 0 : 0;
    
    // Clear previous errors
    errorDiv.classList.add('hidden');
    errorDiv.textContent = '';
    quantityInput.classList.remove('border-red-500');
    
    if (orderQty > 0) {
        if (orderQty > availableQty) {
            // Show error for exceeding available quantity
            errorDiv.textContent = `Cannot order more than ${availableQty} items available`;
            errorDiv.classList.remove('hidden');
            quantityInput.classList.add('border-red-500');
            totalDisplay.value = '';
        } else {
            // Calculate total
            const total = price * orderQty;
            totalDisplay.value = '₱' + total.toFixed(2);
            quantityInput.classList.remove('border-red-500');
            quantityInput.classList.add('border-green-500');
        }
    } else {
        totalDisplay.value = '';
        quantityInput.classList.remove('border-red-500', 'border-green-500');
    }
}

function confirmQuantityOrder() {
    const quantityInput = document.getElementById('order_qty_input');
    const deliveryDate = document.getElementById('order_delivery_date');
    const notes = document.getElementById('order_notes');
    
    const orderQty = parseInt(quantityInput.value) || 0;
    const availableQty = selectedQuantityRecord ? selectedQuantityRecord.quantity : 0;
    const price = selectedQuantityRecord ? parseFloat(selectedQuantityRecord.price) || 0 : 0;
    
    // Validation
    if (!orderQty || orderQty <= 0) {
        showNotification('error', 'Please enter a valid quantity');
        return;
    }
    
    if (orderQty > availableQty) {
        showNotification('error', `Cannot order more than ${availableQty} items available`);
        return;
    }
    
    if (!deliveryDate.value) {
        showNotification('error', 'Please select a delivery date');
        return;
    }
    
    // Create order object for summary table
    const newOrder = {
        id: Date.now(), // Temporary ID for removal
        inventory_quantity_id: selectedQuantityRecord.id,
        item_name: selectedInventoryItem.item_name,
        category_name: selectedInventoryItem.category.category_name,
        quantity_order: orderQty,
        price: price,
        total_amount_price: price * orderQty,
        date_deliver: deliveryDate.value,
        reason: notes.value || '',
        status: 'pending'
    };
    
    // Add to pending orders array
    pendingOrders.push(newOrder);
    
    // Update the summary table
    if (orderContext === 'new') {
        updateNewCustomerOrderSummary();
        showNotification('success', 'Order added to summary! Add more orders or click "Save Customer" to finalize.');
    } else {
        // For existing customers, still add to pending and show summary
        updateExistingCustomerOrderSummary();
        showNotification('success', 'Order added to summary! Click "Save Orders" to finalize.');
    }
    
    // Close the quantity input modal
    close_modal('#quantity-input-modal');
}

// Order summary management functions
function updateNewCustomerOrderSummary() {
    const summarySection = document.getElementById('new-customer-order-summary');
    const tbody = document.getElementById('new-customer-orders-tbody');
    const grandTotalElement = document.getElementById('new-customer-grand-total');
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    let grandTotal = 0;
    
    pendingOrders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="font-medium">${order.item_name}</td>
            <td>${order.category_name}</td>
            <td class="text-center">${order.quantity_order}</td>
            <td>₱${parseFloat(order.price).toFixed(2)}</td>
            <td class="font-medium">₱${parseFloat(order.total_amount_price).toFixed(2)}</td>
            <td>${order.date_deliver}</td>
            <td>
                <div class="max-w-xs truncate" title="${order.reason}">
                    ${order.reason || 'No notes'}
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePendingOrder(${order.id})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        grandTotal += parseFloat(order.total_amount_price);
    });
    
    grandTotalElement.textContent = '₱' + grandTotal.toFixed(2);
    
    // Show/hide summary section
    if (pendingOrders.length > 0) {
        summarySection.classList.remove('hidden');
    } else {
        summarySection.classList.add('hidden');
    }
}

function updateExistingCustomerOrderSummary() {
    // For existing customers, we'll show the orders in the Add Order modal or a similar section
    // For now, let's just use the same summary table approach
    const summarySection = document.getElementById('order-summary-section');
    const tbody = document.getElementById('order-summary-tbody');
    const grandTotalElement = document.getElementById('grand-total');
    
    if (!summarySection || !tbody || !grandTotalElement) {
        console.log('Order summary elements not found, using new customer summary');
        updateNewCustomerOrderSummary();
        return;
    }
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    let grandTotal = 0;
    
    pendingOrders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="font-medium">${order.item_name}</td>
            <td>${order.category_name}</td>
            <td class="text-center">${order.quantity_order}</td>
            <td>₱${parseFloat(order.price).toFixed(2)}</td>
            <td class="font-medium">₱${parseFloat(order.total_amount_price).toFixed(2)}</td>
            <td>${order.date_deliver}</td>
            <td>
                <div class="max-w-xs truncate" title="${order.reason}">
                    ${order.reason || 'No notes'}
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePendingOrder(${order.id})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        grandTotal += parseFloat(order.total_amount_price);
    });
    
    grandTotalElement.textContent = '₱' + grandTotal.toFixed(2);
    
    // Show/hide summary section
    if (pendingOrders.length > 0) {
        summarySection.classList.remove('hidden');
    } else {
        summarySection.classList.add('hidden');
    }
}

function removePendingOrder(orderId) {
    pendingOrders = pendingOrders.filter(order => order.id !== orderId);
    
    // Update the appropriate summary table
    if (orderContext === 'new') {
        updateNewCustomerOrderSummary();
    } else {
        updateExistingCustomerOrderSummary();
    }
    
    showNotification('info', 'Order removed from summary');
}

function clearNewCustomerOrders() {
    if (pendingOrders.length === 0) {
        showNotification('info', 'No orders to clear');
        return;
    }
    
    if (confirm('Are you sure you want to clear all pending orders?')) {
        pendingOrders = [];
        updateNewCustomerOrderSummary();
        showNotification('info', 'All orders cleared');
    }
}

function clearOrderSummary() {
    if (pendingOrders.length === 0) {
        showNotification('info', 'No orders to clear');
        return;
    }
    
    if (confirm('Are you sure you want to clear all pending orders?')) {
        pendingOrders = [];
        updateExistingCustomerOrderSummary();
        showNotification('info', 'All orders cleared');
    }
}

// Note: Old createCustomerWithOrder and createOrderForCustomer functions removed
// New workflow uses pendingOrders array and processes everything when "Save Customer" is clicked

// Add event listener for confirm order button
document.addEventListener('DOMContentLoaded', function() {
    const confirmOrderBtn = document.getElementById('confirm-order-btn');
    if (confirmOrderBtn) {
        confirmOrderBtn.addEventListener('click', confirmQuantityOrder);
    }
});
</script>

@endsection
@section('scripts')
<script src="{{ asset('js/customer/customer.js') }}"></script>
@endsection
