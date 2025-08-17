@extends('layout.app')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
Inventory
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-inventory-modal">Add New Inventory</button>
        <!-- <div class="dropdown">
            <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                <span class="w-5 h-5 flex items-center justify-center"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="plus" class="lucide lucide-plus w-4 h-4" data-lucide="plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> </span>
            </button>
            <div class="dropdown-menu w-40">
                <ul class="dropdown-content">
                    <li>
                        <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="printer" data-lucide="printer" class="lucide lucide-printer w-4 h-4 mr-2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> Print </a>
                    </li>
                    <li>
                        <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg> Export to Excel </a>
                    </li>
                    <li>
                        <a href="" class="dropdown-item"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text w-4 h-4 mr-2"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg> Export to PDF </a>
                    </li>
                </ul>
            </div>
        </div> -->
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
                 <!-- <button type="button" id="clear-search" class="absolute right-8 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600" onclick="clearSearch()" style="display: none;">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                 </button> -->
             </div>
        </div>
    </div>
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">CLIENT NAME</th>
                    <th class="whitespace-nowrap">ADDRESS</th>
                    <th class="text-center whitespace-nowrap">ITEMS COUNT</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inventory)
                <tr class="intro-x">
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ $inventory->client_name }}</a>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $inventory->address }}</div>
                    </td>
                    <td class="text-center">{{ $inventory->items->count() }}</td>
                    <td class="w-40">
                        <div class="flex items-center justify-center {{ $inventory->status === 'active' ? 'text-success' : 'text-danger' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> 
                            {{ ucfirst($inventory->status) }}
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal" data-tw-target="#view-inventory-modal" onclick="viewInventory({{ $inventory->id }})"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View 
                            </a>
                        <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal" data-tw-target="#edit-inventory-modal" onclick="editInventory({{ $inventory->id }})"> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Edit 
                        </a>
                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal" onclick="prepareDelete({{ $inventory->id }}, '{{ $inventory->client_name }}', '{{ $inventory->address }}')"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete 
                            </a>
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
                <h2 class="font-medium text-base mr-auto">Add New Inventory</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="add-inventory-form" action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label for="client_name" class="form-label">Client Name *</label>
                            <input type="text" id="client_name" name="client_name" class="form-control" required>
                        </div>
                                                 <div class="col-span-6" style="display: none;">
                             <input type="hidden" id="status" name="status" value="active">
                         </div>
                        <div class="col-span-12">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium">Inventory Items</h3>
                            <button type="button" class="btn btn-secondary btn-sm" id="add-item-btn" style="cursor: pointer; z-index: 1000;" onclick="testAddItem();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="plus" class="lucide lucide-plus w-4 h-4 mr-1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add Item
                            </button>
                        </div>
                        
                        <script>
                        // Define the function right here to ensure it's available
                        function testAddItem() {
                            console.log('testAddItem function called!');
                            inlineAddItem();
                        }
                        
                        function inlineAddItem() {
                            console.log('inlineAddItem function called');
                            
                            const container = document.getElementById('items-container');
                            if (!container) {
                                console.error('Items container not found!');
                                return;
                            }
                            
                            // Get current item count
                            const currentRows = container.querySelectorAll('.item-row');
                            const newIndex = currentRows.length;
                            
                            console.log('Creating new row with index:', newIndex);
                            
                            const newRow = document.createElement('div');
                            newRow.className = 'item-row grid grid-cols-12 gap-4 mb-3 p-3 border rounded';
                            newRow.innerHTML = `
                                <div class="col-span-6">
                                    <label class="form-label">Description *</label>
                                    <input type="text" name="items[${newIndex}][description]" class="form-control" required>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Quantity *</label>
                                    <input type="number" name="items[${newIndex}][quantity]" class="form-control" min="1" required>
                                </div>
                                    <div class="col-span-6">
                                     <label class="form-label">Rec Meter</label>
                                     <input type="text" name="items[${newIndex}][rec_meter]" class="form-control">
                                 </div>
                                <div class="col-span-6">
                                    <label class="form-label">Qty</label>
                                    <input type="number" name="items[${newIndex}][qty]" class="form-control" min="0">
                                </div>
                                <div class="col-span-12 flex justify-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn" onclick="removeItemRow(this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
                                    </button>
                                </div>
                            `;
                            
                            container.appendChild(newRow);
                            console.log('New row added successfully!');
                        }
                        
                        function removeItemRow(button) {
                            const itemRow = button.closest('.item-row');
                            const container = document.getElementById('items-container');
                            const itemRows = container.querySelectorAll('.item-row');
                            
                            // Don't allow removing the last item row
                            if (itemRows.length <= 1) {
                                alert('At least one item is required');
                                return;
                            }
                            
                            if (itemRow) {
                                itemRow.remove();
                                console.log('Item row removed');
                            }
                        }
                        
                        // Form submission handler
                        function handleFormSubmit(e) {
                            e.preventDefault();
                            console.log('Form submission started...');
                            
                            // Get form data
                            const form = document.getElementById('add-inventory-form');
                            const formData = new FormData(form);
                            
                            // Debug: Log form data
                            console.log('Form data being sent:');
                            for (let [key, value] of formData.entries()) {
                                console.log(key, value);
                            }
                            
                            // Validate form
                            if (!validateForm()) {
                                return false;
                            }
                            
                            // Show loading state
                            const submitBtn = form.querySelector('button[type="submit"]');
                            const originalText = submitBtn.textContent;
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Saving...';
                            
                            // Send AJAX request
                            fetch('{{ route("inventory.store") }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Response received:', data);
                                
                                if (data.success) {
                                    // Show success message with details
                                    const successMessage = `Inventory saved successfully! 
                                        Client: ${data.data.client_name}
                                        Address: ${data.data.address}
                                        Status: ${data.data.status}
                                        Items: ${data.data.items.length}`;
                                    
                                    showNotification('success', successMessage);
                                    
                                    // Close modal - try multiple approaches
                                    const modal = document.getElementById('add-inventory-modal');
                                    if (modal) {
                                        // Try Bootstrap first
                                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                            const modalInstance = bootstrap.Modal.getInstance(modal);
                                            if (modalInstance) {
                                                modalInstance.hide();
                                            }
                                        } else {
                                            // Fallback: hide modal manually
                                            modal.style.display = 'none';
                                            modal.classList.remove('show');
                                            document.body.classList.remove('modal-open');
                                            
                                            // Remove backdrop if exists
                                            const backdrop = document.querySelector('.modal-backdrop');
                                            if (backdrop) {
                                                backdrop.remove();
                                            }
                                        }
                                    }
                                    
                                    // Reset form
                                    resetForm();
                                    
                                    // Reload page to show new data
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                    
                                } else {
                                    showNotification('error', data.message || 'Error occurred while saving inventory');
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
                        
                        // Form validation
                        function validateForm() {
                            const form = document.getElementById('add-inventory-form');
                            const clientName = form.querySelector('#client_name').value.trim();
                            const address = form.querySelector('#address').value.trim();
                            const status = form.querySelector('#status').value;
                            
                            if (!clientName) {
                                alert('Please enter Client Name');
                                return false;
                            }
                            
                            if (!address) {
                                alert('Please enter Address');
                                return false;
                            }
                            
                                                         // Status is automatically set to 'active', no validation needed
                            
                            // Validate items
                            const itemRows = document.querySelectorAll('.item-row');
                            for (let i = 0; i < itemRows.length; i++) {
                                const description = itemRows[i].querySelector('input[name*="[description]"]').value.trim();
                                const quantity = itemRows[i].querySelector('input[name*="[quantity]"]').value;
                                
                                if (!description) {
                                    alert(`Please enter Description for Item ${i + 1}`);
                                    return false;
                                }
                                
                                if (!quantity || quantity < 1) {
                                    alert(`Please enter valid Quantity for Item ${i + 1}`);
                                    return false;
                                }
                            }
                            
                            return true;
                        }
                        
                        // Reset form
                        function resetForm() {
                            const form = document.getElementById('add-inventory-form');
                            form.reset();
                            
                            // Reset items container to only one row
                            const container = document.getElementById('items-container');
                            container.innerHTML = `
                                <div class="item-row grid grid-cols-12 gap-4 mb-3 p-3 border rounded">
                                    <div class="col-span-6">
                                        <label class="form-label">Description *</label>
                                        <input type="text" name="items[0][description]" class="form-control" required>
                                    </div>
                                        <div class="col-span-6">
                                        <label class="form-label">Quantity *</label>
                                        <input type="number" name="items[0][quantity]" class="form-control" min="1" required>
                                    </div>
                                    <div class="col-span-6">
                                        <label class="form-label">Rec Meter</label>
                                        <input type="text" name="items[0][rec_meter]" class="form-control">
                                    </div>
                                    <div class="col-span-6">
                                        <label class="form-label">Qty</label>
                                        <input type="number" name="items[0][qty]" class="form-control" min="0">
                                    </div>
                                    <div class="col-span-12 flex justify-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-item-btn" onclick="removeItemRow(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
                                        </button>
                                    </div>
                                </div>
                            `;
                        }
                        
                                                 // Show notification
                         function showNotification(type, message) {
                             // Create notification element
                             const notification = document.createElement('div');
                             notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                                 type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
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
                         
                         // Search function for inventory with pagination
                         function searchInventory(searchTerm) {
                             console.log('Searching for:', searchTerm);
                             
                             // Use the pagination search function if available
                             if (typeof handleSearchWithPagination === 'function') {
                                 handleSearchWithPagination(searchTerm);
                                 return;
                             }
                             
                             // Fallback to client-side search if pagination functions not available
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
                                 
                                 const clientName = row.querySelector('td:first-child a')?.textContent?.toLowerCase() || '';
                                 const address = row.querySelector('td:nth-child(2) div')?.textContent?.toLowerCase() || '';
                                 const itemsCount = row.querySelector('td:nth-child(3)')?.textContent || '';
                                 const status = row.querySelector('td:nth-child(4) div')?.textContent?.toLowerCase() || '';
                                 
                                 // Check if any field contains the search term
                                 const isMatch = clientName.includes(searchLower) || 
                                                address.includes(searchLower) || 
                                                itemsCount.includes(searchLower) || 
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
                             const clearButton = document.getElementById('clear-search');
                             
                             if (resultsText) {
                                 if (count === totalCount) {
                                     resultsText.textContent = `Showing ${totalCount} entries`;
                                     if (clearButton) clearButton.style.display = 'none';
                                 } else {
                                     resultsText.textContent = `Showing ${count} of ${totalCount} entries`;
                                     if (clearButton) clearButton.style.display = 'block';
                                 }
                             }
                         }
                         
                         // Clear search function
                         function clearSearch() {
                             const searchInput = document.getElementById('search-input');
                             if (searchInput) {
                                 searchInput.value = '';
                                 searchInventory('');
                             }
                         }
                         
                         // Enhanced search with debouncing and pagination support
                         let searchTimeout;
                         function debouncedSearch(searchTerm) {
                             clearTimeout(searchTimeout);
                             searchTimeout = setTimeout(() => {
                                 // Use pagination search if available, otherwise use regular search
                                 if (typeof debouncedSearchWithPagination === 'function') {
                                     debouncedSearchWithPagination(searchTerm);
                                 } else {
                                     searchInventory(searchTerm);
                                 }
                             }, 500); // Wait 500ms after user stops typing for better UX
                         }
                         
                                                   // Show/hide "No data available" message
                          function showNoDataMessage(show) {
                              let noDataRow = document.querySelector('tr.no-data-message');
                              
                              if (show) {
                                  if (!noDataRow) {
                                      noDataRow = document.createElement('tr');
                                      noDataRow.className = 'no-data-message';
                                      noDataRow.innerHTML = '<td colspan="5" class="text-center py-8 text-slate-500">No data available</td>';
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
                          
                          // Edit inventory functions
                                                     function editInventory(inventoryId) {
                               console.log('Editing inventory:', inventoryId);
                               
                               // Show loading state
                               showNotification('info', 'Loading inventory data...');
                               
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
                                       // Show the edit modal
                                       const modal = document.getElementById('edit-inventory-modal');
                                       if (modal) {
                                           // Try Bootstrap first
                                           if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                               const modalInstance = new bootstrap.Modal(modal);
                                               modalInstance.show();
                                           } else {
                                               // Fallback: show modal manually
                                               modal.style.display = 'block';
                                               modal.classList.add('show');
                                               document.body.classList.add('modal-open');
                                               
                                               // Add backdrop
                                               const backdrop = document.createElement('div');
                                               backdrop.className = 'modal-backdrop fade show';
                                               document.body.appendChild(backdrop);
                                           }
                                       }
                                   } else {
                                       showNotification('error', data.message || 'Error loading inventory data');
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
                              document.getElementById('edit_client_name').value = inventory.client_name;
                              document.getElementById('edit_address').value = inventory.address;
                              document.getElementById('edit_status').value = inventory.status;
                              
                              // Populate items
                              const editItemsContainer = document.getElementById('edit-items-container');
                              editItemsContainer.innerHTML = '';
                              
                              inventory.items.forEach((item, index) => {
                                  const itemRow = document.createElement('div');
                                  itemRow.className = 'edit-item-row grid grid-cols-12 gap-4 mb-3 p-3 border rounded';
                                  itemRow.innerHTML = `
                                      <div class="col-span-6">
                                          <label class="form-label">Description *</label>
                                          <input type="text" name="items[${index}][description]" class="form-control" value="${item.description}" required>
                                      </div>
                                                                             <div class="col-span-6">
                                           <label class="form-label">Quantity *</label>
                                           <input type="number" name="items[${index}][quantity]" class="form-control" min="1" value="${item.quantity}" required>
                                       </div>
                                      <div class="col-span-6">
                                          <label class="form-label">Rec Meter</label>
                                          <input type="text" name="items[${index}][rec_meter]" class="form-control" value="${item.rec_meter || ''}">
                                      </div>
                                      <div class="col-span-6">
                                          <label class="form-label">Qty</label>
                                          <input type="number" name="items[${index}][qty]" class="form-control" min="0" value="${item.qty || ''}">
                                      </div>
                                      <div class="col-span-12 flex justify-end">
                                          <button type="button" class="btn btn-danger btn-sm remove-edit-item-btn" onclick="removeEditItemRow(this)">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
                                          </button>
                                      </div>
                                  `;
                                  editItemsContainer.appendChild(itemRow);
                              });
                          }
                          
                          function editAddItem() {
                              console.log('editAddItem function called');
                              
                              const container = document.getElementById('edit-items-container');
                              if (!container) {
                                  console.error('Edit items container not found!');
                                  return;
                              }
                              
                              // Get current item count
                              const currentRows = container.querySelectorAll('.edit-item-row');
                              const newIndex = currentRows.length;
                              
                              console.log('Creating new edit row with index:', newIndex);
                              
                              const newRow = document.createElement('div');
                              newRow.className = 'edit-item-row grid grid-cols-12 gap-4 mb-3 p-3 border rounded';
                              newRow.innerHTML = `
                                  <div class="col-span-6">
                                      <label class="form-label">Description *</label>
                                      <input type="text" name="items[${newIndex}][description]" class="form-control" required>
                                  </div>
                                  <div class="col-span-6">
                                      <label class="form-label">Quantity *</label>
                                      <input type="number" name="items[${newIndex}][quantity]" class="form-control" min="1" required>
                                  </div>
                                  <div class="col-span-6">
                                      <label class="form-label">Rec Meter</label>
                                      <input type="text" name="items[${newIndex}][rec_meter]" class="form-control">
                                  </div>
                                  <div class="col-span-6">
                                      <label class="form-label">Qty</label>
                                      <input type="number" name="items[${newIndex}][qty]" class="form-control" min="0">
                                  </div>
                                  <div class="col-span-12 flex justify-end">
                                      <button type="button" class="btn btn-danger btn-sm remove-edit-item-btn" onclick="removeEditItemRow(this)">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
                                      </button>
                                  </div>
                              `;
                              
                              container.appendChild(newRow);
                              console.log('New edit row added successfully!');
                          }
                          
                          function removeEditItemRow(button) {
                              const itemRow = button.closest('.edit-item-row');
                              const container = document.getElementById('edit-items-container');
                              const itemRows = container.querySelectorAll('.edit-item-row');
                              
                              // Don't allow removing the last item row
                              if (itemRows.length <= 1) {
                                  alert('At least one item is required');
                                  return;
                              }
                              
                              if (itemRow) {
                                  itemRow.remove();
                                  console.log('Edit item row removed');
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
                                      showNotification('success', 'Inventory updated successfully!');
                                      
                                      // Close modal
                                      const modal = document.getElementById('edit-inventory-modal');
                                      if (modal) {
                                          modal.style.display = 'none';
                                          modal.classList.remove('show');
                                          document.body.classList.remove('modal-open');
                                          
                                          // Remove backdrop if exists
                                          const backdrop = document.querySelector('.modal-backdrop');
                                          if (backdrop) {
                                              backdrop.remove();
                                          }
                                      }
                                      
                                      // Reload page to show updated data
                                      setTimeout(() => {
                                          window.location.reload();
                                      }, 1500);
                                      
                                  } else {
                                      showNotification('error', data.message || 'Error occurred while updating inventory');
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
                              const clientName = form.querySelector('#edit_client_name').value.trim();
                              const address = form.querySelector('#edit_address').value.trim();
                              
                              if (!clientName) {
                                  alert('Please enter Client Name');
                                  return false;
                              }
                              
                              if (!address) {
                                  alert('Please enter Address');
                                  return false;
                              }
                              
                              // Validate items
                              const itemRows = document.querySelectorAll('.edit-item-row');
                              for (let i = 0; i < itemRows.length; i++) {
                                  const description = itemRows[i].querySelector('input[name*="[description]"]').value.trim();
                                  const quantity = itemRows[i].querySelector('input[name*="[quantity]"]').value;
                                  
                                  if (!description) {
                                      alert(`Please enter Description for Item ${i + 1}`);
                                      return false;
                                  }
                                  
                                  if (!quantity || quantity < 1) {
                                      alert(`Please enter valid Quantity for Item ${i + 1}`);
                                      return false;
                                  }
                              }
                              
                              return true;
                          }
                        </script>
                        
                        <div id="items-container">
                            <div class="item-row grid grid-cols-12 gap-4 mb-3 p-3 border rounded">
                                <div class="col-span-6">
                                    <label class="form-label">Description *</label>
                                    <input type="text" name="items[0][description]" class="form-control" required>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" class="form-control" min="1" required>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Rec Meter</label>
                                    <input type="text" name="items[0][rec_meter]" class="form-control">
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Qty</label>
                                    <input type="number" name="items[0][qty]" class="form-control" min="0">
                                </div>
                                <div class="col-span-12 flex justify-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn" onclick="removeItemRow(this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Inventory</button>
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
                  <h2 class="font-medium text-base mr-auto">Edit Inventory</h2>
                  <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <form id="edit-inventory-form" action="" method="POST" onsubmit="return handleEditFormSubmit(event)">
                  @csrf
                  @method('PUT')
                  <input type="hidden" id="edit_inventory_id" name="inventory_id">
                  <div class="modal-body">
                      <div class="grid grid-cols-12 gap-4">
                          <div class="col-span-12">
                              <label for="edit_client_name" class="label">Client Name *</label>
                              <input type="text" id="edit_client_name" name="client_name" class="form-control" required>
                          </div>
                          <div class="col-span-6" style="display: none;">
                              <input type="hidden" id="edit_status" name="status" value="active">
                          </div>
                          <div class="col-span-12">
                              <label for="edit_address" class="form-label">Address *</label>
                              <textarea id="edit_address" name="address" class="form-control" rows="3" required></textarea>
                          </div>
                      </div>
                      
                      <div class="mt-6">
                          <div class="flex items-center justify-between mb-4">
                              <h3 class="text-lg font-medium">Inventory Items</h3>
                              <button type="button" class="btn btn-secondary btn-sm" id="edit-add-item-btn" style="cursor: pointer; z-index: 1000;" onclick="editAddItem();">
                                  <svg xmlns="http://www/w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="plus" class="lucide lucide-plus w-4 h-4 mr-1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Add Item
                              </button>
                          </div>
                          
                          <div id="edit-items-container">
                              <!-- Items will be populated here -->
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Update Inventory</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <!-- END: Edit Inventory Modal -->
  
  <!-- BEGIN: Delete Confirmation Modal -->
  <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h2 class="font-medium text-base mr-auto">Delete Inventory</h2>
                  <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  </button>
              </div>
              <div class="modal-body">
                  <p class="text-slate-500">Are you sure you want to delete this inventory? This action cannot be undone.</p>
                  <div class="mt-4">
                      <strong>Client:</strong> <span id="delete-client-name"></span><br>
                      <strong>Address:</strong> <span id="delete-client-address"></span>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Inventory</button>
              </div>
          </div>
      </div>
  </div>
  <!-- END: Delete Confirmation Modal -->
  
  <!-- BEGIN: View Inventory Modal -->
  <div id="view-inventory-modal" class="modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                  <h2 class="font-medium text-base mr-auto">Inventory Details</h2>
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
                                  <label class="text-sm font-semibold text-slate-600">Client Name:</label>
                                  <p class="text-lg font-medium text-slate-800" id="view-client-name"></p>
                              </div>
                              <div>
                                  <label class="text-sm font-semibold text-slate-600">Address:</label>
                                  <p class="text-base text-slate-800" id="view-client-address"></p>
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
                  
                  <!-- Items Section -->
                  <div>
                      <h3 class="text-lg font-semibold text-slate-800 mb-4">Inventory Items</h3>
                      <div class="overflow-x-auto">
                          <table class="w-full border border-slate-200">
                              <thead class="bg-slate-50">
                                  <tr>
                                      <th class="border border-slate-200 px-4 py-3 text-left font-semibold text-slate-700">Description</th>
                                      <th class="border border-slate-200 px-4 py-3 text-center font-semibold text-slate-700 w-24">Quantity</th>
                                      <th class="border border-slate-200 px-4 py-3 text-left font-semibold text-slate-700">Rec Meter</th>
                                      <th class="border border-slate-200 px-4 py-3 text-center font-semibold text-slate-700 w-24">Qty</th>
                                  </tr>
                              </thead>
                              <tbody id="view-items-container" class="bg-white">
                                  <!-- Items will be populated here -->
                              </tbody>
                          </table>
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
  
  // Delete functions defined directly in HTML to ensure availability
  let currentDeleteId = null;
  
  function prepareDelete(inventoryId, clientName, clientAddress) {
      console.log('prepareDelete called with:', { inventoryId, clientName, clientAddress });
      
      // Store the current delete ID
      currentDeleteId = inventoryId;
      
      // Populate the delete confirmation modal
      const clientNameElement = document.getElementById('delete-client-name');
      const clientAddressElement = document.getElementById('delete-client-address');
      
      if (clientNameElement && clientAddressElement) {
          clientNameElement.textContent = clientName || 'N/A';
          clientAddressElement.textContent = clientAddress || 'N/A';
          console.log('Modal populated successfully');
      } else {
          console.error('Could not find modal elements:', { clientNameElement, clientAddressElement });
      }
  }
  
  function confirmDelete() {
      if (!currentDeleteId) {
          console.error('No delete ID set');
          showNotification('error', 'Error: No inventory selected for deletion');
          return;
      }
      
      console.log('Confirming deletion of inventory:', currentDeleteId);
      
      // Show loading state
      const deleteBtn = document.querySelector('#delete-confirmation-modal .btn-danger');
      const originalText = deleteBtn.textContent;
      deleteBtn.disabled = true;
      deleteBtn.textContent = 'Deleting...';
      
      // Send delete request
      fetch(`/inventory/${currentDeleteId}`, {
          method: 'DELETE',
          headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
          }
      })
      .then(response => response.json())
      .then(data => {
          console.log('Delete response received:', data);
          
          if (data.success) {
              // Show success message
              showNotification('success', 'Inventory deleted successfully!');
              
              // Close modal
              const modal = document.getElementById('delete-confirmation-modal');
              if (modal) {
                  // Use Tailwind modal dismiss
                  const closeBtn = modal.querySelector('[data-tw-dismiss="modal"]');
                  if (closeBtn) {
                      closeBtn.click();
                  }
              }
              
              // Reload page to show updated data
              setTimeout(() => {
                  window.location.reload();
              }, 1500);
              
          } else {
              showNotification('error', data.message || 'Error occurred while deleting inventory');
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
          currentDeleteId = null;
      });
  }
  
  // View inventory function
  function viewInventory(inventoryId) {
      console.log('Viewing inventory:', inventoryId);
      
      // Show loading state
      showNotification('info', 'Loading inventory details...');
      
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
              showNotification('error', data.message || 'Error loading inventory details');
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
      document.getElementById('view-client-name').textContent = inventory.client_name;
      document.getElementById('view-client-address').textContent = inventory.address;
      document.getElementById('view-status').textContent = inventory.status.charAt(0).toUpperCase() + inventory.status.slice(1);
      
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
      
      // Populate items table
      const viewItemsContainer = document.getElementById('view-items-container');
      viewItemsContainer.innerHTML = '';
      
      if (inventory.items && inventory.items.length > 0) {
          inventory.items.forEach((item, index) => {
              const itemRow = document.createElement('tr');
              itemRow.className = index % 2 === 0 ? 'bg-white' : 'bg-slate-50';
              itemRow.innerHTML = `
                  <td class="border border-slate-200 px-4 py-3 text-slate-800">${item.description}</td>
                  <td class="border border-slate-200 px-4 py-3 text-center text-slate-800 font-medium">${item.quantity}</td>
                  <td class="border border-slate-200 px-4 py-3 text-slate-800">${item.rec_meter || 'N/A'}</td>
                  <td class="border border-slate-200 px-4 py-3 text-center text-slate-800 font-medium">${item.qty || 'N/A'}</td>
              `;
              viewItemsContainer.appendChild(itemRow);
          });
      } else {
          // Show "no items" message
          const noItemsRow = document.createElement('tr');
          noItemsRow.innerHTML = '<td colspan="4" class="border border-slate-200 px-4 py-8 text-center text-slate-500 bg-slate-50">No items found</td>';
          viewItemsContainer.appendChild(noItemsRow);
      }
  }
  
  // Notification function for view modal
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