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
        <div class="hidden md:block mx-auto text-slate-500">Showing {{ $inventories->count() }} entries</div>
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
                            <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal" data-tw-target="#view-inventory-modal" data-inventory-id="{{ $inventory->id }}"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View 
                            </a>
                            <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal" data-tw-target="#edit-inventory-modal" data-inventory-id="{{ $inventory->id }}"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> Edit 
                            </a>
                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal" data-inventory-id="{{ $inventory->id }}"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete 
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
            <form id="add-inventory-form" action="{{ route('inventory.store') }}" method="POST" onsubmit="handleFormSubmit(event);">
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
                                    <button type="button" class="btn btn-danger btn-sm remove-item-btn">
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

@endsection
@section('scripts')
<script src="{{ asset('js/inventory/inventory.js') }}"></script>
<script>
// Simple test to verify JavaScript is working
document.addEventListener('DOMContentLoaded', function() {
    console.log('Blade template scripts loaded');
    
    // Add a test button
    const testBtn = document.createElement('button');
    testBtn.textContent = '🧪 Test JS';
    testBtn.className = 'btn btn-warning btn-sm ml-2';
    testBtn.onclick = function() {
        console.log('Test button clicked!');
        alert('JavaScript is working!');
    };
    
    // Add to the page header
    const header = document.querySelector('h2.intro-y');
    if (header) {
        header.appendChild(testBtn);
    }
    
    // Test if the add item button exists
    const addItemBtn = document.getElementById('add-item-btn');
    console.log('Add item button found:', addItemBtn);
    
    // Test if the modal exists
    const modal = document.getElementById('add-inventory-modal');
    console.log('Modal found:', modal);
    
    // Test if our functions are available
    console.log('testAddItem function available:', typeof testAddItem);
    console.log('inlineAddItem function available:', typeof inlineAddItem);
    console.log('removeItemRow function available:', typeof removeItemRow);
});
</script>
@endsection