let itemCounter = 1;

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    console.log('Current itemCounter:', itemCounter);
    
    // Test if the modal exists
    const modalElement = document.getElementById('add-inventory-modal');
    console.log('Modal found:', modalElement);
    
    // Test if the add item button exists
    const addItemBtn = document.getElementById('add-item-btn');
    console.log('Add item button found:', addItemBtn);
    
    initializeInventoryForm();
    
    // Also listen for modal shown event to reinitialize
    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function() {
            console.log('Modal shown, reinitializing...');
            initializeInventoryForm();
        });
        
        modalElement.addEventListener('hidden.bs.modal', function() {
            console.log('Modal hidden, resetting form...');
            resetForm();
        });
    }
    
    // Use event delegation for dynamic buttons (add/remove items)
    document.addEventListener('click', function(e) {
        // Add Item button
        if (e.target && e.target.id === 'add-item-btn') {
            console.log('Add item button clicked via event delegation!');
            e.preventDefault();
            e.stopPropagation();
            testAddItem();
        }
        
        // Remove Item button
        if (e.target && e.target.classList.contains('remove-item-btn')) {
            console.log('Remove item button clicked via event delegation!');
            e.preventDefault();
            e.stopPropagation();
            removeItemRow(e.target);
        }
    });
    
    // Debug: Check if modal elements exist
    console.log('Checking modal elements...');
    console.log('Delete modal:', document.getElementById('delete-confirmation-modal'));
    console.log('Client name span:', document.getElementById('delete-client-name'));
    console.log('Client address span:', document.getElementById('delete-client-address'));
    console.log('Confirm delete button:', document.getElementById('confirm-delete-btn'));
});

// Delete inventory functions - moved outside DOMContentLoaded to be globally available
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value
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

function initializeInventoryForm() {
    const form = document.getElementById('add-inventory-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
        console.log('Form initialized');
    } else {
        console.log('Form not found');
    }
}

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
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value
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

// Edit inventory functions
function editInventory(inventoryId) {
    console.log('Editing inventory:', inventoryId);
    
    // Show loading state
    showNotification('info', 'Loading inventory data...');
    
    // Check if modal exists
    const modal = document.getElementById('edit-inventory-modal');
    console.log('Edit modal found:', modal);
    
    if (!modal) {
        console.error('Edit modal not found!');
        showNotification('error', 'Edit modal not found');
        return;
    }
    
    // Fetch inventory data
    fetch(`/inventory/${inventoryId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Edit data received:', data);
        if (data.success) {
            populateEditForm(data.data);
            console.log('Edit form populated successfully');
            
            // Bind the form submission handler
            const editForm = document.getElementById('edit-inventory-form');
            if (editForm) {
                editForm.onsubmit = handleEditFormSubmit;
                console.log('Edit form submission handler bound');
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value
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

// Placeholder functions for future implementation
function viewInventory(id) {
    // TODO: Implement view inventory functionality
    console.log('View inventory:', id);
}

function deleteInventory(id) {
    // TODO: Implement delete inventory functionality
    console.log('Delete inventory:', id);
}

// Test function to manually test delete functionality
function testDeleteFunctionality() {
    console.log('Testing delete functionality...');
    
    // Test prepareDelete
    prepareDelete(999, 'Test Client', 'Test Address');
    
    // Check if modal elements are populated
    const clientNameElement = document.getElementById('delete-client-name');
    const clientAddressElement = document.getElementById('delete-client-address');
    
    console.log('Client name element:', clientNameElement);
    console.log('Client address element:', clientAddressElement);
    
    if (clientNameElement && clientAddressElement) {
        console.log('Client name text:', clientNameElement.textContent);
        console.log('Client address text:', clientAddressElement.textContent);
    }
    
    // Test currentDeleteId
    console.log('Current delete ID:', currentDeleteId);
}
