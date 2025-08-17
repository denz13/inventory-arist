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
    
    // Use event delegation for buttons that might be added dynamically
    document.addEventListener('click', function(e) {
        // Add Item button
        if (e.target && e.target.id === 'add-item-btn') {
            console.log('Add item button clicked via event delegation!');
            e.preventDefault();
            e.stopPropagation();
            addItemRow();
        }
        
        // Remove Item button
        if (e.target && e.target.classList.contains('remove-item-btn')) {
            console.log('Remove item button clicked via event delegation!');
            e.preventDefault();
            e.stopPropagation();
            removeItemRow(e.target);
        }
        
        // View inventory button
        if (e.target && e.target.closest('[data-tw-target="#view-inventory-modal"]')) {
            const button = e.target.closest('[data-tw-target="#view-inventory-modal"]');
            const inventoryId = button.getAttribute('data-inventory-id');
            console.log('View inventory clicked:', inventoryId);
            viewInventory(inventoryId);
        }
        
        // Edit inventory button
        if (e.target && e.target.closest('[data-tw-target="#edit-inventory-modal"]')) {
            const button = e.target.closest('[data-tw-target="#edit-inventory-modal"]');
            const inventoryId = button.getAttribute('data-inventory-id');
            console.log('Edit inventory clicked:', inventoryId);
            editInventory(inventoryId);
        }
        
        // Delete inventory button
        if (e.target && e.target.closest('[data-tw-target="#delete-confirmation-modal"]')) {
            const button = e.target.closest('[data-tw-target="#delete-confirmation-modal"]');
            const inventoryId = button.getAttribute('data-inventory-id');
            console.log('Delete inventory clicked:', inventoryId);
            deleteInventory(inventoryId);
        }
    });
    
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
});

function initializeInventoryForm() {
    const form = document.getElementById('add-inventory-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
        console.log('Form initialized');
    } else {
        console.log('Form not found');
    }
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    // Disable submit button and show loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    // Send AJAX request
    fetch('/inventory', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('success', data.message);
            
            // Close modal
            const modal = document.getElementById('add-inventory-modal');
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
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
}

function addItemRow() {
    console.log('addItemRow function called, itemCounter:', itemCounter);
    
    const container = document.getElementById('items-container');
    if (!container) {
        console.error('Items container not found!');
        return;
    }
    
    console.log('Container found, creating new row...');
    
    const newRow = document.createElement('div');
    newRow.className = 'item-row grid grid-cols-12 gap-4 mb-3 p-3 border rounded';
    newRow.innerHTML = `
        <div class="col-span-6">
            <label class="form-label">Description *</label>
            <input type="text" name="items[${itemCounter}][description]" class="form-control" required>
        </div>
        <div class="col-span-2">
            <label class="form-label">Quantity *</label>
            <input type="number" name="items[${itemCounter}][quantity]" class="form-control" min="1" required>
        </div>
        <div class="col-span-2">
            <label class="form-label">Rec Meter</label>
            <input type="number" name="items[${itemCounter}][rec_meter]" class="form-control" min="0" step="0.01">
        </div>
        <div class="col-span-2">
            <label class="form-label">Qty</label>
            <input type="number" name="items[${itemCounter}][qty]" class="form-control" min="0">
        </div>
        <div class="col-span-12 flex justify-end">
            <button type="button" class="btn btn-danger btn-sm remove-item-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
            </button>
        </div>
    `;
    
    container.appendChild(newRow);
    console.log('New row added to container');
    
    itemCounter++;
    console.log('Item counter incremented to:', itemCounter);
}

function removeItemRow(button) {
    const itemRow = button.closest('.item-row');
    const container = document.getElementById('items-container');
    const itemRows = container.querySelectorAll('.item-row');
    
    // Don't allow removing the last item row
    if (itemRows.length <= 1) {
        showNotification('error', 'At least one item is required');
        return;
    }
    
    if (itemRow) {
        itemRow.remove();
    }
}

function resetForm() {
    const form = document.getElementById('add-inventory-form');
    if (form) {
        form.reset();
        
        // Reset items container to only one row
        const container = document.getElementById('items-container');
        container.innerHTML = `
            <div class="item-row grid grid-cols-12 gap-4 mb-3 p-3 border rounded">
                <div class="col-span-6">
                    <label class="form-label">Description *</label>
                    <input type="text" name="items[0][description]" class="form-control" required>
                </div>
                <div class="col-span-2">
                    <label class="form-label">Quantity *</label>
                    <input type="number" name="items[0][quantity]" class="form-control" min="1" required>
                </div>
                <div class="col-span-2">
                    <label class="form-label">Rec Meter</label>
                    <input type="number" name="items[0][rec_meter]" class="form-control" min="0" step="0.01">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Qty</label>
                    <input type="number" name="items[0][qty]" class="form-control" min="0">
                </div>
                <div class="col-span-12 flex justify-end">
                    <button type="button" class="btn btn-danger btn-sm remove-item-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash-2" class="lucide lucide-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Remove
                    </button>
                </div>
            </div>
        `;
        
        // Rebind the remove button event listener
        const removeBtn = container.querySelector('.remove-item-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                removeItemRow(this);
            });
        }
        
        itemCounter = 1;
    }
}

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

// Placeholder functions for future implementation
function viewInventory(id) {
    // TODO: Implement view inventory functionality
    console.log('View inventory:', id);
}

function editInventory(id) {
    // TODO: Implement edit inventory functionality
    console.log('Edit inventory:', id);
}

function deleteInventory(id) {
    // TODO: Implement delete inventory functionality
    console.log('Delete inventory:', id);
}
