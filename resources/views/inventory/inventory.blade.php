@extends('layout.app')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Inventory Management
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
        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-inventory-modal">Add New Item</button>
        
        <div class="hidden md:block mx-auto text-slate-500">
            @if(isset($inventories) && $inventories instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                Showing {{ $inventories->firstItem() ?? 0 }} to {{ $inventories->lastItem() ?? 0 }} of {{ $inventories->total() }} entries
            @else
                Showing {{ $inventories->count() }} entries
            @endif
        </div>
        
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" id="search-input" class="form-control w-56 box pr-10" placeholder="Search..." onkeyup="debouncedSearch(this.value)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
            </div>
        </div>
    </div>
    
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">ITEM NAME</th>
                    <th class="whitespace-nowrap">MARKED AS</th>
                    <th class="text-center whitespace-nowrap">QUANTITY</th>
                    <th class="text-center whitespace-nowrap">PRICE</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inventory)
                <tr class="intro-x hover:bg-slate-50 {{ $inventory->marked_as === 'OLD' ? 'text-red-600' : '' }}">
                    <td>
                        <span class="font-medium whitespace-nowrap {{ $inventory->marked_as === 'OLD' ? 'text-red-600' : '' }}">
                            {{ $inventory->item_name }}
                        </span>
                    </td>
                    <td>
                        <div class="text-xs whitespace-nowrap mt-0.5 {{ $inventory->marked_as === 'OLD' ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                            {{ $inventory->marked_as ?: 'N/A' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            <span class="font-medium {{ $inventory->marked_as === 'OLD' ? 'text-red-600' : ($inventory->qty <= 5 ? 'text-red-600' : ($inventory->qty <= 10 ? 'text-yellow-600' : 'text-green-600')) }}">
                                {{ $inventory->qty }}
                            </span>
                            
                            @if($inventory->marked_as !== 'OLD')
                                @if($inventory->qty <= 5)
                                    <!-- Critical Low Stock Warning -->
                                    <div class="ml-2 flex items-center text-red-600" title="CRITICAL: Very Low Stock!">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle">
                                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                            <path d="M12 9v4"></path>
                                            <path d="M12 17h.01"></path>
                                        </svg>
                                        <span class="text-xs font-bold ml-1">CRITICAL</span>
                                    </div>
                                @elseif($inventory->qty <= 10)
                                    <!-- Low Stock Warning -->
                                    <div class="ml-2 flex items-center text-yellow-600" title="Warning: Low Stock">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        <span class="text-xs font-semibold ml-1">LOW</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="font-medium {{ $inventory->marked_as === 'OLD' ? 'text-red-600' : 'text-primary' }}">
                            {{ $inventory->price ? '₱' . number_format($inventory->price, 2) : 'N/A' }}
                        </span>
                    </td>
                    <td class="w-40">
                        <div class="flex items-center justify-center {{ $inventory->marked_as === 'OLD' ? 'text-red-600' : ($inventory->status === 'active' ? 'text-success' : 'text-danger') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> 
                            {{ ucfirst($inventory->status) }}
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <button class="btn btn-outline-primary btn-sm mr-1" onclick="editInventory({{ $inventory->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="prepareInventoryDelete({{ $inventory->id }}, '{{ $inventory->item_name }}', '{{ $inventory->marked_as ?? 'N/A' }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-500">No inventory items found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    @include('_partials.dynamic-pagination')
</div>

<!-- BEGIN: Add Inventory Modal -->
<div id="add-inventory-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Inventory Item</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="add-inventory-form" action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label for="marked_as" class="form-label">Marked As *</label>
                            <select id="marked_as" name="marked_as" class="form-control" required onchange="toggleItemNameInput()">
                                <option value="">Select Type</option>
                                <option value="NEW">NEW</option>
                                @if($hasExistingData)
                                    <option value="EXISTING">EXISTING</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="item_name" class="form-label">Item Name *</label>
                            
                            <!-- Input for NEW items -->
                            <div id="new-item-section" style="display: none;">
                                <input type="text" id="item_name" name="item_name" class="form-control" placeholder="Enter new item name">
                            </div>
                            
                            <!-- Dropdown for EXISTING items -->
                            <div id="existing-item-section" style="display: none;">
                                <select id="existing_item_name" name="existing_item_name" class="form-control">
                                    <option value="">Select existing item</option>
                                    @foreach($existingItems as $item)
                                        <option value="{{ $item->item_name }}">{{ $item->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Default message -->
                            <div id="default-item-section">
                                <input type="text" class="form-control" placeholder="Please select Marked As first" disabled>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label for="qty" class="form-label">Quantity *</label>
                            <input type="number" id="qty" name="qty" class="form-control" min="0" required>
                        </div>
                        <div class="col-span-6">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" id="price" name="price" class="form-control" min="0" step="0.01">
                        </div>
                        <div class="col-span-12">
                            <label for="status" class="form-label">Status *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Add Inventory Modal -->

<!-- BEGIN: Edit Inventory Modal -->
<div id="edit-inventory-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Inventory Item</h2>
                <button class="btn-close" onclick="close_modal('#edit-inventory-modal')" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="edit-inventory-form" action="" method="POST" onsubmit="return handleEditFormSubmit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_inventory_id" name="inventory_id">
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label for="edit_marked_as" class="form-label">Marked As *</label>
                            <select id="edit_marked_as" name="marked_as" class="form-control" required onchange="toggleEditItemNameInput()">
                                <option value="">Select Type</option>
                                <option value="NEW">NEW</option>
                                @if($hasExistingData)
                                    <option value="EXISTING">EXISTING</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_item_name" class="form-label">Item Name *</label>
                            
                            <!-- Input for NEW items -->
                            <div id="edit-new-item-section" style="display: none;">
                                <input type="text" id="edit_item_name" name="item_name" class="form-control" placeholder="Enter new item name">
                            </div>
                            
                            <!-- Dropdown for EXISTING items -->
                            <div id="edit-existing-item-section" style="display: none;">
                                <select id="edit_existing_item_name" name="existing_item_name" class="form-control">
                                    <option value="">Select existing item</option>
                                    @foreach($existingItems as $item)
                                        <option value="{{ $item->item_name }}">{{ $item->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Default message -->
                            <div id="edit-default-item-section">
                                <input type="text" class="form-control" placeholder="Please select Marked As first" disabled>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_qty" class="form-label">Quantity *</label>
                            <input type="number" id="edit_qty" name="qty" class="form-control" min="0" required>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" id="edit_price" name="price" class="form-control" min="0" step="0.01">
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
                    <button type="button" class="btn btn-secondary mr-2" onclick="close_modal('#edit-inventory-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Edit Inventory Modal -->

<!-- BEGIN: Delete Inventory Modal -->
<div id="delete-inventory-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Delete Inventory Item</h2>
                <button class="btn-close" onclick="closeDeleteInventoryModal()" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-slate-500">Are you sure you want to delete this inventory item? This action cannot be undone.</p>
                <div class="mt-4 p-4 bg-slate-50 rounded-lg">
                    <div class="mb-2">
                        <strong>Item Name:</strong> <span id="delete-inventory-item-name" class="text-slate-700"></span>
                    </div>
                    <div class="mb-2">
                        <strong>Marked As:</strong> <span id="delete-inventory-category" class="text-slate-700"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" onclick="closeDeleteInventoryModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmInventoryDelete()">Delete Item</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Inventory Modal -->


<script>
// Simplified inventory management functions

// Toggle Item Name Input based on Marked As selection
function toggleItemNameInput() {
    const markedAsSelect = document.getElementById('marked_as');
    const newItemSection = document.getElementById('new-item-section');
    const existingItemSection = document.getElementById('existing-item-section');
    const defaultItemSection = document.getElementById('default-item-section');
    const newItemInput = document.getElementById('item_name');
    const existingItemSelect = document.getElementById('existing_item_name');
    
    const selectedValue = markedAsSelect.value;
    
    // Reset all sections
    newItemSection.style.display = 'none';
    existingItemSection.style.display = 'none';
    defaultItemSection.style.display = 'none';
    
    // Reset required attributes
    if (newItemInput) newItemInput.required = false;
    if (existingItemSelect) existingItemSelect.required = false;
    
    if (selectedValue === 'NEW') {
        // Show text input for new items
        newItemSection.style.display = 'block';
        if (newItemInput) {
            newItemInput.required = true;
            newItemInput.focus();
        }
        // Clear existing selection
        if (existingItemSelect) existingItemSelect.value = '';
        
    } else if (selectedValue === 'EXISTING') {
        // Show dropdown for existing items
        existingItemSection.style.display = 'block';
        if (existingItemSelect) {
            existingItemSelect.required = true;
            existingItemSelect.focus();
        }
        // Clear new item input
        if (newItemInput) newItemInput.value = '';
        
    } else {
        // Show default message
        defaultItemSection.style.display = 'block';
        // Clear both inputs
        if (newItemInput) newItemInput.value = '';
        if (existingItemSelect) existingItemSelect.value = '';
    }
    
    console.log('Marked As changed to:', selectedValue);
}

// Toggle Edit Item Name Input based on Marked As selection
function toggleEditItemNameInput() {
    const markedAsSelect = document.getElementById('edit_marked_as');
    const newItemSection = document.getElementById('edit-new-item-section');
    const existingItemSection = document.getElementById('edit-existing-item-section');
    const defaultItemSection = document.getElementById('edit-default-item-section');
    const newItemInput = document.getElementById('edit_item_name');
    const existingItemSelect = document.getElementById('edit_existing_item_name');
    
    const selectedValue = markedAsSelect.value;
    
    // Reset all sections
    newItemSection.style.display = 'none';
    existingItemSection.style.display = 'none';
    defaultItemSection.style.display = 'none';
    
    // Reset required attributes
    if (newItemInput) newItemInput.required = false;
    if (existingItemSelect) existingItemSelect.required = false;
    
    if (selectedValue === 'NEW') {
        // Show text input for new items
        newItemSection.style.display = 'block';
        if (newItemInput) {
            newItemInput.required = true;
            newItemInput.focus();
        }
        // Clear existing selection
        if (existingItemSelect) existingItemSelect.value = '';
        
    } else if (selectedValue === 'EXISTING') {
        // Show dropdown for existing items
        existingItemSection.style.display = 'block';
        if (existingItemSelect) {
            existingItemSelect.required = true;
            existingItemSelect.focus();
        }
        // Clear new item input
        if (newItemInput) newItemInput.value = '';
        
    } else {
        // Show default message
        defaultItemSection.style.display = 'block';
        // Clear both inputs
        if (newItemInput) newItemInput.value = '';
        if (existingItemSelect) existingItemSelect.value = '';
    }
    
    console.log('Edit Marked As changed to:', selectedValue);
}

// Edit inventory function
function editInventory(inventoryId) {
    console.log('Editing inventory item:', inventoryId);
    
    // Show loading state
    showNotification('info', 'Loading item data...');
    
    // Fetch inventory data
    fetch(`/inventory/${inventoryId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateEditForm(data.data);
            open_modal('#edit-inventory-modal');
        } else {
            showNotification('error', data.message || 'Error loading item data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

function populateEditForm(inventory) {
    console.log('Populating edit form with:', inventory);
    
    // Set form action
    document.getElementById('edit-inventory-form').action = `/inventory/${inventory.id}`;
    
    // Set inventory ID
    document.getElementById('edit_inventory_id').value = inventory.id;
    
    // Set marked_as dropdown
    document.getElementById('edit_marked_as').value = inventory.marked_as || 'NEW';
    
    // Set other fields
    document.getElementById('edit_qty').value = inventory.qty;
    document.getElementById('edit_price').value = inventory.price || '';
    document.getElementById('edit_status').value = inventory.status;
    
    // Trigger the toggle to show appropriate item name section
    toggleEditItemNameInput();
    
    // Set item name in the appropriate field
    if (inventory.marked_as === 'EXISTING') {
        // Set existing item dropdown
        const existingSelect = document.getElementById('edit_existing_item_name');
        if (existingSelect) {
            existingSelect.value = inventory.item_name;
        }
    } else {
        // Set new item text input (default for NEW or null marked_as)
        const newInput = document.getElementById('edit_item_name');
        if (newInput) {
            newInput.value = inventory.item_name;
        }
    }
}

function handleEditFormSubmit(e) {
    e.preventDefault();
    console.log('Edit form submission started...');
    
    // Get form data
    const form = document.getElementById('edit-inventory-form');
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    // Send AJAX request
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
        console.log('Edit response received:', data);
        
        if (data.success) {
            showNotification('success', 'Inventory item updated successfully!');
            close_modal('#edit-inventory-modal');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error occurred while updating inventory item');
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

// Delete inventory functions
let currentDeleteInventoryId = null;

function prepareInventoryDelete(inventoryId, itemName, markedAs) {
    currentDeleteInventoryId = inventoryId;
    document.getElementById('delete-inventory-item-name').textContent = itemName;
    document.getElementById('delete-inventory-category').textContent = markedAs;
    open_modal('#delete-inventory-modal');
}

function closeDeleteInventoryModal() {
    close_modal('#delete-inventory-modal');
    currentDeleteInventoryId = null;
}

function confirmInventoryDelete() {
    if (!currentDeleteInventoryId) {
        console.error('No delete inventory ID set');
        showNotification('error', 'Error: No inventory item selected for deletion');
        return;
    }
    
    console.log('Confirming deletion of inventory item:', currentDeleteInventoryId);
    
    // Show loading state
    const deleteBtn = document.querySelector('#delete-inventory-modal .btn-danger');
    const originalText = deleteBtn.textContent;
    deleteBtn.disabled = true;
    deleteBtn.textContent = 'Deleting...';
    
    // Send delete request
    fetch(`/inventory/${currentDeleteInventoryId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delete inventory response received:', data);
        
        if (data.success) {
            showNotification('success', 'Inventory item deleted successfully!');
            closeDeleteInventoryModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error occurred while deleting inventory item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        deleteBtn.disabled = false;
        deleteBtn.textContent = originalText;
        currentDeleteInventoryId = null;
    });
}

// Form validation
function validateForm() {
    const form = document.getElementById('add-inventory-form');
    const markedAs = form.querySelector('#marked_as').value;
    const qty = form.querySelector('#qty').value;
    
    if (!markedAs) {
        alert('Please select Marked As type');
        return false;
    }
    
    if (markedAs === 'NEW') {
        const itemName = form.querySelector('#item_name').value.trim();
        if (!itemName) {
            alert('Please enter Item Name');
            return false;
        }
    } else if (markedAs === 'EXISTING') {
        const existingItemName = form.querySelector('#existing_item_name').value;
        if (!existingItemName) {
            alert('Please select an existing item');
            return false;
        }
    }
    
    if (!qty || qty < 0) {
        alert('Please enter valid Quantity');
        return false;
    }
    
    return true;
}

// Search functionality
function searchInventory(searchTerm) {
    console.log('Searching for:', searchTerm);
    
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    if (!searchTerm || searchTerm.trim() === '') {
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    const searchLower = searchTerm.toLowerCase().trim();
    let visibleCount = 0;
    
    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) {
            row.style.display = 'none';
            return;
        }
        
        const itemName = row.querySelector('td:first-child span')?.textContent?.toLowerCase() || '';
        const markedAs = row.querySelector('td:nth-child(2) div')?.textContent?.toLowerCase() || '';
        const quantity = row.querySelector('td:nth-child(3) span')?.textContent || '';
        const price = row.querySelector('td:nth-child(4) span')?.textContent || '';
        const status = row.querySelector('td:nth-child(5) div')?.textContent?.toLowerCase() || '';
        
        const isMatch = itemName.includes(searchLower) || 
                       markedAs.includes(searchLower) || 
                       quantity.includes(searchLower) || 
                       price.includes(searchLower) || 
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
        searchInventory(searchTerm);
    }, 300);
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
</script>


@endsection
@section('scripts')
<script src="{{ asset('js/inventory/inventory.js') }}"></script>
@endsection