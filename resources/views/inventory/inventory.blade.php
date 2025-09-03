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
                    <th class="whitespace-nowrap">CATEGORY</th>
                    <th class="text-center whitespace-nowrap">TOTAL RECORDS</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inventory)
                <!-- Main inventory row -->
                <tr class="intro-x cursor-pointer hover:bg-slate-50" onclick="toggleInventoryDetails({{ $inventory->id }})">
                    <td>
                        <div class="flex items-center">
                            <svg id="icon-{{ $inventory->id }}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transform transition-transform duration-200 mr-2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                            <span class="font-medium whitespace-nowrap">{{ $inventory->item_name }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                            {{ $inventory->category->category_name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="font-medium text-primary">{{ $inventory->quantities->count() }}</span>
                    </td>
                    <td class="w-40">
                        <div class="flex items-center justify-center {{ $inventory->status === 'active' ? 'text-success' : 'text-danger' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> 
                            {{ ucfirst($inventory->status) }}
                        </div>
                    </td>
                    <td class="table-report__action w-56" onclick="event.stopPropagation()">
                        <div class="flex justify-center items-center">
                            <!-- <a class="flex items-center mr-3" href="javascript:;" onclick="editInventory({{ $inventory->id }})"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Edit 
                            </a> -->
                            <button class="btn btn-outline-danger btn-sm" onclick="prepareInventoryDelete({{ $inventory->id }}, '{{ $inventory->item_name }}', '{{ $inventory->category->category_name ?? 'N/A' }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- Collapsible details row -->
                <tr id="details-{{ $inventory->id }}" class="hidden">
                    <td colspan="5" class="bg-slate-50 p-0">
                        <div class="p-4">
                            <h4 class="font-medium mb-3">Quantity Records for {{ $inventory->item_name }}</h4>
                            
                            @if($inventory->quantities->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap">#</th>
                                                <th class="whitespace-nowrap">Quantity</th>
                                                <th class="whitespace-nowrap">Price</th>
                                                <th class="whitespace-nowrap">Price Date</th>
                                                <th class="whitespace-nowrap">Low Stock</th>
                                                <th class="whitespace-nowrap">Status</th>
                                                <th class="whitespace-nowrap">Note</th>
                                                <th class="whitespace-nowrap">Created</th>
                                                <th class="whitespace-nowrap">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inventory->quantities as $index => $qty)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="font-medium {{ $qty->quantity <= 3 ? 'text-red-600' : ($qty->quantity <= 5 ? 'text-yellow-600' : 'text-green-600') }}">
                                                        {{ $qty->quantity }}
                                                    </span>
                                                </td>
                                                <td>{{ $qty->price ? '₱' . number_format($qty->price, 2) : 'N/A' }}</td>
                                                <td>{{ $qty->price_effective_date ? \Carbon\Carbon::parse($qty->price_effective_date)->format('M d, Y') : 'N/A' }}</td>
                                                <td>
                                                    @if($qty->is_low_stocks)
                                                        <span class="text-red-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
                                                        </span>
                                                    @else
                                                        <span class="text-green-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="px-2 py-1 rounded-full text-xs {{ $qty->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($qty->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="max-w-xs truncate" title="{{ $qty->note }}">
                                                        {{ $qty->note ?: 'No notes' }}
                                                    </div>
                                                </td>
                                                <td>{{ $qty->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-outline-primary btn-sm mr-1" data-tw-toggle="modal" data-tw-target="#edit-quantity-modal" onclick="editQuantity({{ $qty->id }}, '{{ $inventory->item_name }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm" data-tw-toggle="modal" data-tw-target="#delete-quantity-modal" onclick="deleteQuantity({{ $qty->id }}, '{{ $inventory->item_name }}', {{ $qty->quantity }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-slate-500 text-center py-4">No quantity records found for this item.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-500">No inventory items found</td>
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
    <div class="modal-dialog modal-lg">
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
                            <label for="category_id" class="form-label">Category *</label>
                            <select id="category_id" name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="item_name" class="form-label">Item Name *</label>
                            
                            <!-- Toggle Button -->
                            <div class="flex items-center mb-2">
                                <div class="flex items-center">
                                    <input class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&[type='radio']]:checked:bg-primary [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-primary [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed [&:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white ml-3 mr-0" type="checkbox" id="toggle-item-name-checkbox" onchange="simpleToggle(this)">
                                    <label for="toggle-item-name-checkbox" class="ml-2 text-sm font-medium text-slate-700 cursor-pointer">
                                        <span id="toggle-label">Input New Name</span>
                                    </label>
                                </div>
                                <span class="text-xs text-slate-500 ml-3" id="toggle-status">Currently: Select Existing Item</span>
                            </div>
                            
                            <!-- Input New Name Field (Hidden by default) -->
                            <div id="new-item-name-section" style="display: none;">
                                <input type="text" id="item_name" name="item_name" class="form-control" placeholder="Enter new item name">
                            </div>
                            
                            <!-- Select Existing Item Field (Default) -->
                            <div id="existing-item-name-section">
                                <select id="existing_item_id" name="existing_item_id" class="form-control" onchange="handleExistingItemSelection(this)">
                                    <option value="">Select existing item</option>
                                    @foreach($existingItems ?? [] as $item)
                                        <option value="{{ $item->id }}" data-category-id="{{ $item->category_id }}" data-category-name="{{ $item->category->category_name ?? 'No Category' }}">{{ $item->item_name }} ({{ $item->category->category_name ?? 'No Category' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" min="0" required>
                        </div>
                        <div class="col-span-6">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" id="price" name="price" class="form-control" min="0" step="0.01">
                        </div>
                        <div class="col-span-6">
                            <label for="price_effective_date" class="form-label">Price Effective Date</label>
                            <input type="date" id="price_effective_date" name="price_effective_date" class="form-control">
                        </div>
                        <div class="col-span-6">
                            <label for="status" class="form-label">Status *</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-span-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <!-- Hidden fields for automatic low stock and note -->
                        <input type="hidden" id="is_low_stocks" name="is_low_stocks" value="0">
                        <input type="hidden" id="note" name="note" value="">
                        
                        <!-- Quantity Warning Display -->
                        <div class="col-span-12">
                            <div id="quantity-warning" class="hidden p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                        <path d="M12 9v4"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                    <span id="warning-message"></span>
                                </div>
                            </div>
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
    <div class="modal-dialog modal-lg">
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
                            <label for="edit_category_id" class="form-label">Category *</label>
                            <select id="edit_category_id" name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_item_name" class="form-label">Item Name *</label>
                            
                            <!-- Toggle Button -->
                            <div class="flex items-center mb-2">
                                <div class="flex items-center">
                                    <input class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&[type='radio']]:checked:bg-primary [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-primary [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed [&:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white ml-3 mr-0" type="checkbox" id="edit-toggle-item-name-checkbox">
                                    <label for="edit-toggle-item-name-checkbox" class="ml-2 text-sm font-medium text-slate-700 cursor-pointer">
                                        <span id="edit-toggle-label">Input New Name</span>
                                    </label>
                                </div>
                                <span class="text-xs text-slate-500 ml-3" id="edit-toggle-status">Currently: Select Existing Item</span>
                            </div>
                            
                            <!-- Input New Name Field (Hidden by default) -->
                            <div id="edit-new-item-name-section" style="display: none;">
                                <input type="text" id="edit_item_name" name="item_name" class="form-control" placeholder="Enter new item name">
                            </div>
                            
                            <!-- Select Existing Item Field (Default) -->
                            <div id="edit-existing-item-name-section">
                                <select id="edit_existing_item_name" name="existing_item_name" class="form-control">
                                    <option value="">Select existing item name</option>
                                    @foreach($existingItems ?? [] as $item)
                                        <option value="{{ $item->item_name }}">{{ $item->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_quantity" class="form-label">Quantity *</label>
                            <input type="number" id="edit_quantity" name="quantity" class="form-control" min="0" required>
                        </div>
                        <div class="col-span-6">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" id="edit_price" name="price" class="form-control" min="0" step="0.01">
                        </div>
                        <div class="col-span-6">
                            <label for="edit_price_effective_date" class="form-label">Price Effective Date</label>
                            <input type="date" id="edit_price_effective_date" name="price_effective_date" class="form-control">
                        </div>
                        <div class="col-span-6">
                            <label for="edit_status" class="form-label">Status *</label>
                            <select id="edit_status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-span-12">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <!-- Hidden fields for automatic low stock and note -->
                        <input type="hidden" id="edit_is_low_stocks" name="is_low_stocks" value="0">
                        <input type="hidden" id="edit_note" name="note" value="">
                        
                        <!-- Quantity Warning Display -->
                        <div class="col-span-12">
                            <div id="edit-quantity-warning" class="hidden p-3 rounded-lg border">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                        <path d="M12 9v4"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                    <span id="edit-warning-message"></span>
                                </div>
                            </div>
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
                <p class="text-slate-500">Are you sure you want to delete this inventory item? This action cannot be undone and will also delete all associated quantity records.</p>
                <div class="mt-4 p-4 bg-slate-50 rounded-lg">
                    <div class="mb-2">
                        <strong>Item Name:</strong> <span id="delete-inventory-item-name" class="text-slate-700"></span>
                    </div>
                    <div class="mb-2">
                        <strong>Category:</strong> <span id="delete-inventory-category" class="text-slate-700"></span>
                    </div>
                    <div class="text-sm text-slate-500 mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle inline mr-1"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        Warning: This will permanently delete all quantity records for this item.
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

<!-- BEGIN: View Inventory Modal -->
<div id="view-inventory-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Inventory Item Details</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <!-- Header Section -->
                <div class="border-b border-slate-200 pb-4 mb-6">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <div class="mb-3">
                                <label class="text-sm font-semibold text-slate-600">Item Name:</label>
                                <p class="text-lg font-medium text-slate-800" id="view-item-name"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-600">Category:</label>
                                <p class="text-base text-slate-800" id="view-category"></p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label class="text-sm font-semibold text-slate-600">Status:</label>
                                <p class="text-base text-slate-800" id="view-status"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-600">Date Created:</label>
                                <p class="text-base text-slate-800" id="view-created-date"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Details Section -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Item Details</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Quantity:</label>
                            <p class="text-lg font-medium text-slate-800" id="view-quantity"></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Price:</label>
                            <p class="text-base text-slate-800" id="view-price"></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Price Effective Date:</label>
                            <p class="text-base text-slate-800" id="view-price-date"></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Low Stock Alert:</label>
                            <p class="text-base text-slate-800" id="view-low-stock"></p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-semibold text-slate-600">Description:</label>
                            <p class="text-base text-slate-800" id="view-description"></p>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-semibold text-slate-600">Note:</label>
                            <p class="text-base text-slate-800" id="view-note"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-tw-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END: View Inventory Modal -->

<!-- BEGIN: Edit Quantity Modal -->
<div id="edit-quantity-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Quantity Record</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="edit-quantity-form" method="POST" onsubmit="return handleEditQuantitySubmit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="quantity_id" name="quantity_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Item:</strong> <span id="quantity-item-name"></span>
                    </div>
                    
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label for="qty_quantity" class="form-label">Quantity *</label>
                            <input type="number" id="qty_quantity" name="quantity" class="form-control" min="0" required onchange="handleQuantityChangeModal()">
                        </div>
                        <div class="col-span-6">
                            <label for="qty_price" class="form-label">Price</label>
                            <input type="number" id="qty_price" name="price" class="form-control" min="0" step="0.01" onchange="handleQuantityChangeModal()">
                        </div>
                        <div class="col-span-6">
                            <label for="qty_price_effective_date" class="form-label">Price Effective Date</label>
                            <input type="date" id="qty_price_effective_date" name="price_effective_date" class="form-control" onchange="handleQuantityChangeModal()">
                        </div>
                        <div class="col-span-6">
                            <label for="qty_status" class="form-label">Status *</label>
                            <select id="qty_status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Hidden fields for automatic low stock and note -->
                    <input type="hidden" id="qty_is_low_stocks" name="is_low_stocks" value="0">
                    <input type="hidden" id="qty_note" name="note" value="">
                    
                    <!-- Quantity Warning Display -->
                    <div class="mt-3">
                        <div id="qty-warning" class="hidden p-3 rounded-lg border">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                                    <path d="M12 9v4"></path>
                                    <path d="M12 17h.01"></path>
                                </svg>
                                <span id="qty-warning-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Quantity</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Edit Quantity Modal -->

<!-- BEGIN: Delete Quantity Modal -->
<div id="delete-quantity-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Delete Quantity Record</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-slate-500">Are you sure you want to delete this quantity record? This action cannot be undone.</p>
                <div class="mt-4">
                    <strong>Item:</strong> <span id="delete-qty-item-name"></span><br>
                    <strong>Quantity:</strong> <span id="delete-qty-quantity"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmQuantityDelete()">Delete Record</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Quantity Modal -->

<script>
// Handle existing item selection
function handleExistingItemSelection(selectElement) {
    const categorySelect = document.getElementById('category_id');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    // Remove any existing hidden category input
    const existingHiddenCategory = document.getElementById('hidden_category_id');
    if (existingHiddenCategory) {
        existingHiddenCategory.remove();
    }
    
    if (selectedOption.value) {
        const categoryId = selectedOption.getAttribute('data-category-id');
        const categoryName = selectedOption.getAttribute('data-category-name');
        
        // Set category and make it readonly
        categorySelect.value = categoryId;
        categorySelect.disabled = true;
        categorySelect.style.backgroundColor = '#f3f4f6';
        categorySelect.style.cursor = 'not-allowed';
        
        // Create hidden input to ensure category_id is sent with form
        const hiddenCategoryInput = document.createElement('input');
        hiddenCategoryInput.type = 'hidden';
        hiddenCategoryInput.id = 'hidden_category_id';
        hiddenCategoryInput.name = 'category_id';
        hiddenCategoryInput.value = categoryId;
        
        // Add hidden input to form
        const form = document.getElementById('add-inventory-form');
        form.appendChild(hiddenCategoryInput);
        
        console.log('Selected existing item, category set to:', categoryName, 'with hidden input');
    } else {
        // Re-enable category selection
        categorySelect.disabled = false;
        categorySelect.style.backgroundColor = '';
        categorySelect.style.cursor = '';
        categorySelect.value = '';
    }
}

// Direct toggle function in the page
function simpleToggle(checkbox) {
    console.log('Direct simpleToggle called with checked:', checkbox.checked);
    
    const newSection = document.getElementById('new-item-name-section');
    const existingSection = document.getElementById('existing-item-name-section');
    const toggleLabel = document.getElementById('toggle-label');
    const toggleStatus = document.getElementById('toggle-status');
    const categorySelect = document.getElementById('category_id');
    
    if (checkbox.checked) {
        // Show input, hide dropdown
        newSection.style.display = 'block';
        existingSection.style.display = 'none';
        toggleLabel.textContent = 'Input New Name';
        toggleStatus.textContent = 'Currently: Input New Name';
        
        // Clear dropdown and set required
        document.getElementById('existing_item_id').value = '';
        document.getElementById('item_name').required = true;
        document.getElementById('existing_item_id').required = false;
        
        // Remove hidden category input if it exists
        const existingHiddenCategory = document.getElementById('hidden_category_id');
        if (existingHiddenCategory) {
            existingHiddenCategory.remove();
        }
        
        // Re-enable category selection for new items
        categorySelect.disabled = false;
        categorySelect.style.backgroundColor = '';
        categorySelect.style.cursor = '';
        categorySelect.value = '';
        
        console.log('Switched to INPUT mode - category enabled');
    } else {
        // Show dropdown, hide input
        newSection.style.display = 'none';
        existingSection.style.display = 'block';
        toggleLabel.textContent = 'Select Existing';
        toggleStatus.textContent = 'Currently: Select Existing Item';
        
        // Clear input and set required
        document.getElementById('item_name').value = '';
        document.getElementById('item_name').required = false;
        document.getElementById('existing_item_id').required = true;
        
        // Remove hidden category input if it exists
        const existingHiddenCategory = document.getElementById('hidden_category_id');
        if (existingHiddenCategory) {
            existingHiddenCategory.remove();
        }
        
        // Reset category when switching to existing mode
        categorySelect.disabled = false;
        categorySelect.style.backgroundColor = '';
        categorySelect.style.cursor = '';
        categorySelect.value = '';
        
        console.log('Switched to DROPDOWN mode - category reset');
    }
}

// Auto handle low stock and notes based on quantity
function handleQuantityChange() {
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price');
    const priceEffectiveDateInput = document.getElementById('price_effective_date');
    const lowStockHidden = document.getElementById('is_low_stocks');
    const noteHidden = document.getElementById('note');
    const warningDiv = document.getElementById('quantity-warning');
    const warningMessage = document.getElementById('warning-message');
    const existingItemSelect = document.getElementById('existing_item_id');
    const newItemInput = document.getElementById('item_name');
    
    const quantity = parseInt(quantityInput.value) || 0;
    const price = priceInput.value || 'N/A';
    const priceDate = priceEffectiveDateInput.value || 'N/A';
    
    // Get item name
    let itemName = 'Item';
    if (existingItemSelect.style.display !== 'none' && existingItemSelect.value) {
        const selectedOption = existingItemSelect.options[existingItemSelect.selectedIndex];
        itemName = selectedOption.text.split(' (')[0]; // Get name before category
    } else if (newItemInput.value) {
        itemName = newItemInput.value;
    }
    
    // Reset warning
    warningDiv.classList.add('hidden');
    warningDiv.classList.remove('border-red-200', 'bg-red-50', 'text-red-700', 'border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
    
    if (quantity <= 1) {
        // Critical low stock
        lowStockHidden.value = '1';
        noteHidden.value = `CRITICAL LOW STOCK: ${itemName} - Price: ₱${price} (Effective: ${priceDate}) - Only ${quantity} remaining!`;
        
        // Show critical warning
        warningDiv.classList.remove('hidden');
        warningDiv.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
        warningMessage.textContent = `CRITICAL: Only ${quantity} ${itemName} remaining!`;
        
    } else if (quantity <= 3) {
        // Low stock but not critical
        lowStockHidden.value = '1';
        noteHidden.value = `Low Stock Alert: ${itemName} - Price: ₱${price} (Effective: ${priceDate}) - ${quantity} remaining`;
        
        // Show warning for quantity 2
        if (quantity === 2) {
            warningDiv.classList.remove('hidden');
            warningDiv.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
            warningMessage.textContent = `Warning: Low stock for ${itemName} - ${quantity} remaining`;
        }
        
    } else if (quantity === 5) {
        // Warning level
        lowStockHidden.value = '0';
        noteHidden.value = '';
        
        // Show warning for quantity 5
        warningDiv.classList.remove('hidden');
        warningDiv.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
        warningMessage.textContent = `Notice: ${itemName} stock is getting low - ${quantity} remaining`;
        
    } else {
        // Normal stock
        lowStockHidden.value = '0';
        noteHidden.value = '';
    }
    
    console.log('Quantity changed:', quantity, 'Low stock:', lowStockHidden.value, 'Note:', noteHidden.value);
}

// Auto handle low stock and notes for EDIT modal
function handleEditQuantityChange() {
    const quantityInput = document.getElementById('edit_quantity');
    const priceInput = document.getElementById('edit_price');
    const priceEffectiveDateInput = document.getElementById('edit_price_effective_date');
    const lowStockHidden = document.getElementById('edit_is_low_stocks');
    const noteHidden = document.getElementById('edit_note');
    const warningDiv = document.getElementById('edit-quantity-warning');
    const warningMessage = document.getElementById('edit-warning-message');
    const existingItemSelect = document.getElementById('edit_existing_item_name');
    const newItemInput = document.getElementById('edit_item_name');
    
    const quantity = parseInt(quantityInput.value) || 0;
    const price = priceInput.value || 'N/A';
    const priceDate = priceEffectiveDateInput.value || 'N/A';
    
    // Get item name
    let itemName = 'Item';
    if (existingItemSelect && existingItemSelect.style.display !== 'none' && existingItemSelect.value) {
        const selectedOption = existingItemSelect.options[existingItemSelect.selectedIndex];
        itemName = selectedOption.text.split(' (')[0]; // Get name before category
    } else if (newItemInput && newItemInput.value) {
        itemName = newItemInput.value;
    }
    
    // Reset warning
    warningDiv.classList.add('hidden');
    warningDiv.classList.remove('border-red-200', 'bg-red-50', 'text-red-700', 'border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
    
    if (quantity <= 1) {
        // Critical low stock
        lowStockHidden.value = '1';
        noteHidden.value = `CRITICAL LOW STOCK: ${itemName} - Price: ₱${price} (Effective: ${priceDate}) - Only ${quantity} remaining!`;
        
        // Show critical warning
        warningDiv.classList.remove('hidden');
        warningDiv.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
        warningMessage.textContent = `CRITICAL: Only ${quantity} ${itemName} remaining!`;
        
    } else if (quantity <= 3) {
        // Low stock but not critical
        lowStockHidden.value = '1';
        noteHidden.value = `Low Stock Alert: ${itemName} - Price: ₱${price} (Effective: ${priceDate}) - ${quantity} remaining`;
        
        // Show warning for quantity 2
        if (quantity === 2) {
            warningDiv.classList.remove('hidden');
            warningDiv.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
            warningMessage.textContent = `Warning: Low stock for ${itemName} - ${quantity} remaining`;
        }
        
    } else if (quantity === 5) {
        // Warning level
        lowStockHidden.value = '0';
        noteHidden.value = '';
        
        // Show warning for quantity 5
        warningDiv.classList.remove('hidden');
        warningDiv.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
        warningMessage.textContent = `Notice: ${itemName} stock is getting low - ${quantity} remaining`;
        
    } else {
        // Normal stock
        lowStockHidden.value = '0';
        noteHidden.value = '';
    }
    
    console.log('Edit quantity changed:', quantity, 'Low stock:', lowStockHidden.value, 'Note:', noteHidden.value);
}

// Add event listeners for quantity changes
document.addEventListener('DOMContentLoaded', function() {
    // Add modal quantity listeners
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price');
    const priceEffectiveDateInput = document.getElementById('price_effective_date');
    
    if (quantityInput) {
        quantityInput.addEventListener('input', handleQuantityChange);
        quantityInput.addEventListener('change', handleQuantityChange);
    }
    
    if (priceInput) {
        priceInput.addEventListener('change', handleQuantityChange);
    }
    
    if (priceEffectiveDateInput) {
        priceEffectiveDateInput.addEventListener('change', handleQuantityChange);
    }
    
    // Edit modal quantity listeners
    const editQuantityInput = document.getElementById('edit_quantity');
    const editPriceInput = document.getElementById('edit_price');
    const editPriceEffectiveDateInput = document.getElementById('edit_price_effective_date');
    
    if (editQuantityInput) {
        editQuantityInput.addEventListener('input', handleEditQuantityChange);
        editQuantityInput.addEventListener('change', handleEditQuantityChange);
    }
    
    if (editPriceInput) {
        editPriceInput.addEventListener('change', handleEditQuantityChange);
    }
    
    if (editPriceEffectiveDateInput) {
        editPriceEffectiveDateInput.addEventListener('change', handleEditQuantityChange);
    }
    
    // Delete inventory modal is now handled by open_modal() and close_modal() functions
});

// Edit quantity functions
let currentQuantityId = null;

function editQuantity(quantityId, itemName) {
    console.log('Editing quantity:', quantityId);
    currentQuantityId = quantityId;
    
    // Set item name
    document.getElementById('quantity-item-name').textContent = itemName;
    
    // Fetch quantity data
    fetch(`/inventory/quantity/${quantityId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateQuantityForm(data.data);
        } else {
            showNotification('error', data.message || 'Error loading quantity data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

function populateQuantityForm(quantity) {
    document.getElementById('quantity_id').value = quantity.id;
    document.getElementById('qty_quantity').value = quantity.quantity;
    document.getElementById('qty_price').value = quantity.price || '';
    document.getElementById('qty_price_effective_date').value = quantity.price_effective_date || '';
    document.getElementById('qty_status').value = quantity.status;
    document.getElementById('qty_is_low_stocks').value = quantity.is_low_stocks ? '1' : '0';
    document.getElementById('qty_note').value = quantity.note || '';
    
    // Trigger quantity change to update warnings
    handleQuantityChangeModal();
}

function handleEditQuantitySubmit(e) {
    e.preventDefault();
    
    const form = document.getElementById('edit-quantity-form');
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    fetch(`/inventory/quantity/${currentQuantityId}`, {
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
            showNotification('success', 'Quantity updated successfully!');
            
            // Close modal
            const modal = document.getElementById('edit-quantity-modal');
            const closeBtn = modal.querySelector('[data-tw-dismiss="modal"]');
            if (closeBtn) closeBtn.click();
            
            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error updating quantity');
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

// Delete quantity functions
let currentDeleteQuantityId = null;

function deleteQuantity(quantityId, itemName, quantity) {
    currentDeleteQuantityId = quantityId;
    document.getElementById('delete-qty-item-name').textContent = itemName;
    document.getElementById('delete-qty-quantity').textContent = quantity;
}

function confirmQuantityDelete() {
    if (!currentDeleteQuantityId) {
        showNotification('error', 'No quantity selected for deletion');
        return;
    }
    
    const deleteBtn = document.querySelector('#delete-quantity-modal .btn-danger');
    const originalText = deleteBtn.textContent;
    deleteBtn.disabled = true;
    deleteBtn.textContent = 'Deleting...';
    
    fetch(`/inventory/quantity/${currentDeleteQuantityId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Quantity deleted successfully!');
            
            // Close modal
            const modal = document.getElementById('delete-quantity-modal');
            const closeBtn = modal.querySelector('[data-tw-dismiss="modal"]');
            if (closeBtn) closeBtn.click();
            
            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error deleting quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        deleteBtn.disabled = false;
        deleteBtn.textContent = originalText;
        currentDeleteQuantityId = null;
    });
}

// Handle quantity change in modal (for warnings)
function handleQuantityChangeModal() {
    const quantityInput = document.getElementById('qty_quantity');
    const priceInput = document.getElementById('qty_price');
    const priceEffectiveDateInput = document.getElementById('qty_price_effective_date');
    const lowStockHidden = document.getElementById('qty_is_low_stocks');
    const noteHidden = document.getElementById('qty_note');
    const warningDiv = document.getElementById('qty-warning');
    const warningMessage = document.getElementById('qty-warning-message');
    const itemNameSpan = document.getElementById('quantity-item-name');
    
    const quantity = parseInt(quantityInput.value) || 0;
    const price = priceInput.value || 'N/A';
    const priceDate = priceEffectiveDateInput.value || 'N/A';
    const itemName = itemNameSpan.textContent || 'Item';
    
    // Reset warning
    warningDiv.classList.add('hidden');
    warningDiv.classList.remove('border-red-200', 'bg-red-50', 'text-red-700', 'border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
    
    if (quantity <= 1) {
        // Critical low stock
        lowStockHidden.value = '1';
        noteHidden.value = `CRITICAL LOW STOCK: ${itemName} - Price: ₱${price} (Effective: ${priceDate}) - Only ${quantity} remaining!`;
        
        // Show critical warning
        warningDiv.classList.remove('hidden');
        warningDiv.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
        warningMessage.textContent = `CRITICAL: Only ${quantity} ${itemName} remaining!`;
        
    } else if (quantity <= 3) {
        // Low stock but not critical
        lowStockHidden.value = '1';
        noteHidden.value = `Low Stock Alert: ${itemName} - Price: ₱${price} (Effective: ${priceDate}) - ${quantity} remaining`;
        
        // Show warning for quantity 2
        if (quantity === 2) {
            warningDiv.classList.remove('hidden');
            warningDiv.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
            warningMessage.textContent = `Warning: Low stock for ${itemName} - ${quantity} remaining`;
        }
        
    } else if (quantity === 5) {
        // Warning level
        lowStockHidden.value = '0';
        noteHidden.value = '';
        
        // Show warning for quantity 5
        warningDiv.classList.remove('hidden');
        warningDiv.classList.add('border-yellow-200', 'bg-yellow-50', 'text-yellow-700');
        warningMessage.textContent = `Notice: ${itemName} stock is getting low - ${quantity} remaining`;
        
    } else {
        // Normal stock
        lowStockHidden.value = '0';
        noteHidden.value = '';
    }
}

// Toggle disclosure panels for inventory details
function toggleInventoryDetails(inventoryId) {
    const detailsPanel = document.getElementById(`details-${inventoryId}`);
    const icon = document.getElementById(`icon-${inventoryId}`);
    
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
</script>

<script>
// Pagination and search functions
function updateTableWithPagination(url) {
    // Show loading state
    const tableContainer = document.querySelector('.intro-y.col-span-12.overflow-auto');
    if (tableContainer) {
        tableContainer.style.opacity = '0.6';
        tableContainer.style.pointerEvents = 'none';
    }
    
    // Fetch updated data
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the table content
            const tableContainer = document.querySelector('.intro-y.col-span-12.overflow-auto');
            if (tableContainer) {
                tableContainer.innerHTML = data.html;
                tableContainer.style.opacity = '1';
                tableContainer.style.pointerEvents = 'auto';
            }
            
            // Update pagination info
            updatePaginationInfo(data.data);
            
            // Reinitialize any necessary event handlers
            initializeTableHandlers();
        }
    })
    .catch(error => {
        console.error('Error updating table:', error);
        // Restore table state
        const tableContainer = document.querySelector('.intro-y.col-span-12.overflow-auto');
        if (tableContainer) {
            tableContainer.style.opacity = '1';
            tableContainer.style.pointerEvents = 'auto';
        }
    });
}

function updatePaginationInfo(paginationData) {
    // Update the entries count display
    const entriesDisplay = document.querySelector('.hidden.md\\:block.mx-auto.text-slate-500');
    if (entriesDisplay && paginationData) {
        entriesDisplay.innerHTML = `Showing ${paginationData.from || 0} to ${paginationData.to || 0} of ${paginationData.total || 0} entries`;
    }
}

function initializeTableHandlers() {
    // Reinitialize any event handlers that might be needed
    // This function can be expanded as needed
    console.log('Table handlers reinitialized');
}

// Delete inventory functions
let currentDeleteInventoryId = null;

function prepareInventoryDelete(inventoryId, itemName, categoryName) {
    currentDeleteInventoryId = inventoryId;
    document.getElementById('delete-inventory-item-name').textContent = itemName;
    document.getElementById('delete-inventory-category').textContent = categoryName;
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delete inventory response received:', data);
        
        if (data.success) {
            // Show success message
            showNotification('success', 'Inventory item deleted successfully!');
            
            // Close modal
            closeDeleteInventoryModal();
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            showNotification('error', data.message || 'Error occurred while deleting inventory item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        // Re-enable delete button
        deleteBtn.disabled = false;
        deleteBtn.textContent = originalText;
        
        // Reset current delete ID
        currentDeleteInventoryId = null;
    });
}

// View inventory function
function viewInventory(inventoryId) {
    console.log('Viewing inventory item:', inventoryId);
    
    // Show loading state
    showNotification('info', 'Loading item details...');
    
    // Fetch inventory data
    fetch(`/inventory/${inventoryId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('View data received:', data);
        if (data.success) {
            populateViewModal(data.data);
            console.log('View modal populated successfully');
        } else {
            showNotification('error', data.message || 'Error loading item details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

function populateViewModal(inventory) {
    console.log('Populating view modal with:', inventory);
    
    // Populate basic information
    document.getElementById('view-item-name').textContent = inventory.item_name;
    document.getElementById('view-category').textContent = inventory.category?.category_name || 'N/A';
    document.getElementById('view-status').textContent = inventory.status.charAt(0).toUpperCase() + inventory.status.slice(1);
    document.getElementById('view-quantity').textContent = inventory.quantity ? inventory.quantity.quantity : 'N/A';
    document.getElementById('view-price').textContent = inventory.quantity && inventory.quantity.price ? '₱' + parseFloat(inventory.quantity.price).toFixed(2) : 'N/A';
    document.getElementById('view-price-date').textContent = inventory.quantity && inventory.quantity.price_effective_date ? inventory.quantity.price_effective_date : 'N/A';
    document.getElementById('view-low-stock').textContent = inventory.quantity && inventory.quantity.is_low_stocks ? 'Yes' : 'No';
    document.getElementById('view-description').textContent = inventory.description || 'No description available';
    document.getElementById('view-note').textContent = inventory.quantity && inventory.quantity.note ? inventory.quantity.note : 'No notes available';
    
    // Format created date
    const createdDate = new Date(inventory.created_at);
    const formattedDate = createdDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('view-created-date').textContent = formattedDate;
}

// Edit inventory functions
function editInventory(inventoryId) {
    console.log('Editing inventory item:', inventoryId);
    
    // Show loading state
    showNotification('info', 'Loading item data...');
    
    // Fetch inventory data
    fetch(`/inventory/${inventoryId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateEditForm(data.data);
            console.log('Edit form populated successfully');
            
            // Show the modal using open_modal function
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
    
    // Set basic fields
    document.getElementById('edit_category_id').value = inventory.category_id;
    document.getElementById('edit_item_name').value = inventory.item_name;
    document.getElementById('edit_description').value = inventory.description || '';
    document.getElementById('edit_status').value = inventory.status;
    
    // Set quantity fields
    if (inventory.quantity) {
        document.getElementById('edit_quantity').value = inventory.quantity.quantity;
        document.getElementById('edit_price').value = inventory.quantity.price || '';
        document.getElementById('edit_price_effective_date').value = inventory.quantity.price_effective_date || '';
        document.getElementById('edit_is_low_stocks').checked = inventory.quantity.is_low_stocks;
        document.getElementById('edit_note').value = inventory.quantity.note || '';
    }
    
    // Check if quantity is low stock and update checkbox accordingly
    if (inventory.quantity) {
        checkLowStock(inventory.quantity.quantity, 'edit_is_low_stocks');
    }
}

function handleEditFormSubmit(e) {
    e.preventDefault();
    console.log('Edit form submission started...');
    
    // Get form data
    const form = document.getElementById('edit-inventory-form');
    const formData = new FormData(form);
    
    // Debug: Log form data
    console.log('Edit form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // Validate form
    if (!validateEditForm()) {
        return false;
    }
    
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Edit response received:', data);
        
        if (data.success) {
            // Show success message
            showNotification('success', 'Inventory item updated successfully!');
            
            // Close modal
            close_modal('#edit-inventory-modal');
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            showNotification('error', data.message || 'Error occurred while updating inventory item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
    
    return false;
}

function validateEditForm() {
    const form = document.getElementById('edit-inventory-form');
    const categoryId = form.querySelector('#edit_category_id').value;
    const itemName = form.querySelector('#edit_item_name').value.trim();
    const quantity = form.querySelector('#edit_quantity').value;
    
    if (!categoryId) {
        alert('Please select a Category');
        return false;
    }
    
    if (!itemName) {
        alert('Please enter Item Name');
        return false;
    }
    
    if (!quantity || quantity < 0) {
        alert('Please enter valid Quantity');
        return false;
    }
    
    return true;
}

// Search function for inventory
function searchInventory(searchTerm) {
    console.log('Searching for:', searchTerm);
    
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    if (!searchTerm || searchTerm.trim() === '') {
        // Show all rows if search is empty
        rows.forEach(row => {
            row.style.display = '';
        });
        
        // Hide "No data available" message when search is cleared
        showNoDataMessage(false);
        
        // Count actual data rows (excluding message rows)
        const dataRows = Array.from(rows).filter(row => 
            !row.querySelector('td[colspan]') && 
            !row.classList.contains('no-data-message')
        );
        updateSearchResults(dataRows.length);
        return;
    }
    
    const searchLower = searchTerm.toLowerCase().trim();
    let visibleCount = 0;
    
    rows.forEach(row => {
        // Skip rows with colspan (like "No inventory items found") and no-data-message
        if (row.querySelector('td[colspan]') || row.classList.contains('no-data-message')) {
            row.style.display = 'none';
            return;
        }
        
        const itemName = row.querySelector('td:first-child a')?.textContent?.toLowerCase() || '';
        const category = row.querySelector('td:nth-child(2) div')?.textContent?.toLowerCase() || '';
        const quantity = row.querySelector('td:nth-child(3)')?.textContent || '';
        const price = row.querySelector('td:nth-child(4)')?.textContent || '';
        const description = row.querySelector('td:nth-child(5) div')?.textContent?.toLowerCase() || '';
        const status = row.querySelector('td:nth-child(7) div')?.textContent?.toLowerCase() || '';
        
        // Check if any field contains the search term
        const isMatch = itemName.includes(searchLower) || 
                       category.includes(searchLower) || 
                       quantity.includes(searchLower) || 
                       price.includes(searchLower) || 
                       description.includes(searchLower) || 
                       status.includes(searchLower);
        
        if (isMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show "No data available" message if no results
    showNoDataMessage(visibleCount === 0);
    
    updateSearchResults(visibleCount);
}

// Update search results count
function updateSearchResults(count) {
    const allRows = document.querySelectorAll('tbody tr');
    const dataRows = Array.from(allRows).filter(row => !row.querySelector('td[colspan]') && !row.classList.contains('no-data-message'));
    const totalCount = dataRows.length;
    
    const resultsText = document.querySelector('.hidden.md\\:block.mx-auto.text-slate-500');
    
    if (resultsText) {
        if (count === totalCount) {
            resultsText.textContent = `Showing ${totalCount} entries`;
        } else {
            resultsText.textContent = `Showing ${count} of ${totalCount} entries`;
        }
    }
}

// Enhanced search with debouncing
let searchTimeout;
function debouncedSearch(searchTerm) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchInventory(searchTerm);
    }, 300); // Wait 300ms after user stops typing
}

// Show/hide "No data available" message
function showNoDataMessage(show) {
    let noDataRow = document.querySelector('tr.no-data-message');
    
    if (show) {
        if (!noDataRow) {
            noDataRow = document.createElement('tr');
            noDataRow.className = 'no-data-message';
            noDataRow.innerHTML = '<td colspan="8" class="text-center py-8 text-slate-500">No data available</td>';
            document.querySelector('tbody').appendChild(noDataRow);
        }
        noDataRow.style.display = '';
    } else {
        if (noDataRow) {
            noDataRow.style.display = 'none';
            // Also remove the row completely if it exists
            if (noDataRow.parentNode) {
                noDataRow.parentNode.removeChild(noDataRow);
            }
        }
    }
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