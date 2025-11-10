<!-- BEGIN: Add Order Modal -->
<div id="add-order-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Order</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="add-order-form" action="{{ route('customer.package.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Customer Information -->
                    <div class="bg-slate-50 p-4 rounded-lg mb-4">
                        <h3 class="text-sm font-medium text-slate-600 mb-2">Customer Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <strong class="text-slate-700">Customer Name:</strong>
                                <span id="order-customer-name" class="text-slate-600 ml-2"></span>
                            </div>
                            <div>
                                <strong class="text-slate-700">Customer ID:</strong>
                                <span id="order-customer-id" class="text-slate-600 ml-2"></span>
                            </div>
                        </div>
                        <input type="hidden" id="order_customer_id" name="customer_id">
                    </div>

                    <!-- Package Details -->
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label for="date_ordered" class="form-label">Order Date *</label>
                            <input type="date" id="date_ordered" name="date_ordered" class="form-control" required>
                        </div>
                        <div class="col-span-6">
                            <label for="package_name" class="form-label">Package Name *</label>
                            <input type="text" id="package_name" name="package" class="form-control" placeholder="Enter package name" required>
                        </div>
                        <div class="col-span-12">
                            <label for="package_status" class="form-label">Package Status</label>
                            <select id="package_status" name="status" class="form-control">
                                <option value="pending" selected>Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Order Items Section -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-slate-700">Order Items</h3>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addOrderItem()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-1">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Add Item
                            </button>
                        </div>

                        <!-- Order Items Container -->
                        <div id="order-items-container">
                            <div class="order-item bg-white  rounded-lg p-4 mb-3">
                                <div class="grid grid-cols-12 gap-4 items-end">
                                    <div class="col-span-4">
                                        <label class="form-label">Inventory Item *</label>
                                        <select name="items[0][inventory_items_id]" class="form-control item-select" required>
                                            <option value="">Select Item</option>
                                            @if(isset($inventories))
                                                @foreach($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}" data-price="{{ $inventory->price }}" data-qty="{{ $inventory->qty }}" data-name="{{ $inventory->item_name }}">{{ $inventory->item_name }} (Available: {{ $inventory->qty }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">Quantity *</label>
                                        <input type="number" name="items[0][qty]" class="form-control quantity-input" min="1" required>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">Price *</label>
                                        <input type="number" name="items[0][price]" class="form-control price-input" step="0.01" required>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">Total</label>
                                        <input type="number" class="form-control total-input" step="0.01" readonly>
                                    </div>
                                    <div class="col-span-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm w-full" onclick="removeOrderItem(this)" disabled>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="bg-slate-50 p-4 rounded-lg mt-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-slate-700">Grand Total:</span>
                                <span id="grand-total" class="text-xl font-bold text-primary">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Add Order Modal -->
