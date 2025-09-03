console.log('Inventory JavaScript file loaded');

// Simple toggle function - works directly
window.simpleToggle = function(checkbox) {
    console.log('simpleToggle called with checked:', checkbox.checked);
    
    const newSection = document.getElementById('new-item-name-section');
    const existingSection = document.getElementById('existing-item-name-section');
    const toggleLabel = document.getElementById('toggle-label');
    const toggleStatus = document.getElementById('toggle-status');
    
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
        
        console.log('Switched to INPUT mode');
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
        
        console.log('Switched to DROPDOWN mode');
    }
};

// Also define it as a regular function for backup
function simpleToggle(checkbox) {
    return window.simpleToggle(checkbox);
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing inventory management...');
    
    // Initialize form handlers
    initializeInventoryForm();
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize modal event listeners
    initializeModals();
    
    // Initialize automatic low stock detection
    initializeLowStockDetection();

    // Initialize toggle handlers (no inline onchange)
    initializeItemNameToggles();
});

function initializeInventoryForm() {
    const form = document.getElementById('add-inventory-form');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
        console.log('Add inventory form initialized');
    } else {
        console.log('Add inventory form not found');
    }
}

function initializeSearch() {
    // Search functionality is now handled in the blade file
    console.log('Search functionality handled by blade file');
}

function initializeModals() {
    // Add modal event listeners
    const addModal = document.getElementById('add-inventory-modal');
    const editModal = document.getElementById('edit-inventory-modal');
    
    if (addModal) {
        addModal.addEventListener('hidden.bs.modal', function() {
            resetForm();
        });
        
        // Re-initialize toggles when modal is shown
        addModal.addEventListener('shown.bs.modal', function() {
            console.log('Add modal shown, re-initializing toggles');
            initializeAddModalToggles();
        });
    }
    
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            // Reset edit form if needed
        });
        
        // Re-initialize toggles when modal is shown
        editModal.addEventListener('shown.bs.modal', function() {
            console.log('Edit modal shown, re-initializing toggles');
            initializeEditModalToggles();
        });
    }
    
    // For non-Bootstrap modals, use custom event or MutationObserver
    // Check if modals are shown by observing style changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                const target = mutation.target;
                if (target.id === 'add-inventory-modal' && target.style.display === 'block') {
                    console.log('Add modal displayed, re-initializing toggles');
                    setTimeout(initializeAddModalToggles, 100);
                } else if (target.id === 'edit-inventory-modal' && target.style.display === 'block') {
                    console.log('Edit modal displayed, re-initializing toggles');
                    setTimeout(initializeEditModalToggles, 100);
                }
            }
        });
    });
    
    if (addModal) observer.observe(addModal, { attributes: true });
    if (editModal) observer.observe(editModal, { attributes: true });
}
function initializeItemNameToggles() {
    initializeAddModalToggles();
    initializeEditModalToggles();

    // Fallback: delegate in case the element is re-rendered by the modal
    document.body.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'toggle-item-name-checkbox') {
            toggleItemNameInput(e);
        }
        if (e.target && e.target.id === 'edit-toggle-item-name-checkbox') {
            toggleEditItemNameInput(e);
        }
    });
}

function initializeAddModalToggles() {
    const toggleCheckbox = document.getElementById('toggle-item-name-checkbox');
    const newSection = document.getElementById('new-item-name-section');
    const existingSection = document.getElementById('existing-item-name-section');
    
    console.log('initializeAddModalToggles called');
    console.log('Elements found:', {
        toggleCheckbox: !!toggleCheckbox,
        newSection: !!newSection,
        existingSection: !!existingSection
    });
    
    if (toggleCheckbox) {
        // Remove existing listeners
        toggleCheckbox.removeEventListener('change', toggleItemNameInput);
        toggleCheckbox.removeEventListener('click', toggleItemNameInput);
        
        // Add fresh listeners
        toggleCheckbox.addEventListener('change', function(e) {
            console.log('Toggle changed:', e.target.checked);
            toggleItemNameInput(e);
        });
        
        console.log('Add modal toggle initialized, current state:', toggleCheckbox.checked);
        // Set initial state
        toggleItemNameInput();
    } else {
        console.error('Toggle checkbox not found!');
    }
}

function initializeEditModalToggles() {
    // Remove existing listeners to prevent duplicates
    const editToggleCheckbox = document.getElementById('edit-toggle-item-name-checkbox');
    if (editToggleCheckbox) {
        // Clone and replace to remove all existing listeners
        const newToggle = editToggleCheckbox.cloneNode(true);
        editToggleCheckbox.parentNode.replaceChild(newToggle, editToggleCheckbox);
        
        // Add fresh listeners
        newToggle.addEventListener('change', toggleEditItemNameInput);
        newToggle.addEventListener('click', toggleEditItemNameInput);
        
        console.log('Edit modal toggle initialized');
        // Set initial state
        toggleEditItemNameInput();
    }
}


function initializeLowStockDetection() {
    // Add event listeners for quantity fields in add form
    const addQuantityField = document.getElementById('quantity');
    if (addQuantityField) {
        addQuantityField.addEventListener('input', function() {
            checkLowStock(this.value, 'is_low_stocks');
        });
    }
    
    // Add event listeners for quantity fields in edit form
    const editQuantityField = document.getElementById('edit_quantity');
    if (editQuantityField) {
        editQuantityField.addEventListener('input', function() {
            checkLowStock(this.value, 'edit_is_low_stocks');
        });
    }
    
    console.log('Low stock detection initialized');
}

function checkLowStock(quantity, checkboxId) {
    const checkbox = document.getElementById(checkboxId);
    if (checkbox) {
        const qty = parseInt(quantity) || 0;
        if (qty <= 5) {
            checkbox.checked = true;
            console.log(`Quantity ${qty} is low stock, checkbox automatically checked`);
        } else {
            checkbox.checked = false;
            console.log(`Quantity ${qty} is not low stock, checkbox unchecked`);
        }
    }
}

// Form submission handler for adding new inventory items
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
            // Show success message
            showNotification('success', 'Inventory item created successfully!');
            
            // Close modal
            const modal = document.getElementById('add-inventory-modal');
            if (modal) {
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
            
            // Redirect back to inventory page instead of reloading
            setTimeout(() => {
                window.location.href = '/inventory';
            }, 1000);
            
        } else {
            showNotification('error', data.message || 'Error occurred while creating inventory item');
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
    const categoryId = form.querySelector('#category_id').value;
    const newItemName = form.querySelector('#item_name').value.trim();
    const existingItemName = form.querySelector('#existing_item_name').value;
    const quantity = form.querySelector('#quantity').value;
    
    if (!categoryId) {
        alert('Please select a Category');
        return false;
    }
    
    // Check if either new item name or existing item ID is provided
    const newItemSection = document.getElementById('new-item-name-section');
    if (newItemSection.style.display !== 'none') {
        // New item name mode
        if (!newItemName) {
            alert('Please enter Item Name');
            return false;
        }
    } else {
        // Existing item mode
        const existingItemId = form.querySelector('#existing_item_id').value;
        if (!existingItemId) {
            alert('Please select an existing Item');
            return false;
        }
    }
    
    if (!quantity || quantity < 0) {
        alert('Please enter valid Quantity');
        return false;
    }
    
    return true;
}

// Reset form
function resetForm() {
    const form = document.getElementById('add-inventory-form');
    if (form) {
        form.reset();
        console.log('Form reset successfully');
    }
}

// Search functionality is now handled in the blade file

// Delete inventory functions are now handled in the blade file

// View inventory functions are now handled in the blade file

// Edit inventory functions are now handled in the blade file

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

// Pagination functions are now handled in the blade file

// Toggle functions for Item Name input
console.log('toggleItemNameInput function defined');
function toggleItemNameInput(e) {
    console.log('toggleItemNameInput invoked');
    const newSection = document.getElementById('new-item-name-section');
    const existingSection = document.getElementById('existing-item-name-section');
    const toggleCheckbox = document.getElementById('toggle-item-name-checkbox');
    const toggleLabel = document.getElementById('toggle-label');
    const toggleStatus = document.getElementById('toggle-status');
    
    console.log('Elements found in toggle:', {
        newSection: !!newSection,
        existingSection: !!existingSection,
        toggleCheckbox: !!toggleCheckbox,
        toggleLabel: !!toggleLabel,
        toggleStatus: !!toggleStatus
    });
    
    const isChecked = e && e.target ? e.target.checked : !!(toggleCheckbox && toggleCheckbox.checked);
    console.log('toggleItemNameInput state:', isChecked);
    console.log('Checkbox actual checked value:', toggleCheckbox ? toggleCheckbox.checked : 'no checkbox');
    
    if (isChecked) {
        // Switch to Input New Name
        console.log('Switching to INPUT NEW NAME');
        if (newSection) {
            newSection.style.display = 'block';
            newSection.style.visibility = 'visible';
            console.log('New section shown');
        }
        if (existingSection) {
            existingSection.style.display = 'none';
            existingSection.style.visibility = 'hidden';
            console.log('Existing section hidden');
        }
        if (toggleLabel) toggleLabel.textContent = 'Input New Name';
        if (toggleStatus) toggleStatus.textContent = 'Currently: Input New Name';
        
        // Set required attribute on new input, remove from existing
        const newInput = document.getElementById('item_name');
        const existingSelect = document.getElementById('existing_item_name');
        if (newInput) newInput.required = true;
        if (existingSelect) existingSelect.required = false;
        
        // Clear existing selection
        if (existingSelect) existingSelect.value = '';
        
    } else {
        // Switch to Select Existing Item
        console.log('Switching to SELECT EXISTING');
        if (newSection) {
            newSection.style.display = 'none';
            newSection.style.visibility = 'hidden';
            console.log('New section hidden');
        }
        if (existingSection) {
            existingSection.style.display = 'block';
            existingSection.style.visibility = 'visible';
            console.log('Existing section shown');
        }
        if (toggleLabel) toggleLabel.textContent = 'Select Existing';
        if (toggleStatus) toggleStatus.textContent = 'Currently: Select Existing Item';
        
        // Set required attribute on existing select, remove from new input
        const newInput = document.getElementById('item_name');
        const existingSelect = document.getElementById('existing_item_name');
        if (newInput) newInput.required = false;
        if (existingSelect) existingSelect.required = true;
        
        // Clear new input
        if (newInput) newInput.value = '';
    }
}

console.log('toggleEditItemNameInput function defined');
function toggleEditItemNameInput(e) {
    console.log('toggleEditItemNameInput invoked');
    const newSection = document.getElementById('edit-new-item-name-section');
    const existingSection = document.getElementById('edit-existing-item-name-section');
    const toggleCheckbox = document.getElementById('edit-toggle-item-name-checkbox');
    const toggleLabel = document.getElementById('edit-toggle-label');
    const toggleStatus = document.getElementById('edit-toggle-status');
    const isChecked = e && e.target ? e.target.checked : !!(toggleCheckbox && toggleCheckbox.checked);
    console.log('toggleEditItemNameInput state:', isChecked);
    
    if (isChecked) {
        // Switch to Input New Name
        if (newSection) { newSection.classList.remove('hidden'); newSection.style.display = 'block'; }
        if (existingSection) { existingSection.classList.add('hidden'); existingSection.style.display = 'none'; }
        toggleLabel.textContent = 'Input New Name';
        toggleStatus.textContent = 'Currently: Input New Name';
        
        // Set required attribute on new input, remove from existing
        const newInput = document.getElementById('edit_item_name');
        const existingSelect = document.getElementById('edit_existing_item_name');
        if (newInput) newInput.required = true;
        if (existingSelect) existingSelect.required = false;
        
        // Clear existing selection
        if (existingSelect) existingSelect.value = '';
        
    } else {
        // Switch to Select Existing Item
        if (newSection) { newSection.classList.add('hidden'); newSection.style.display = 'none'; }
        if (existingSection) { existingSection.classList.remove('hidden'); existingSection.style.display = 'block'; }
        toggleLabel.textContent = 'Select Existing';
        toggleStatus.textContent = 'Currently: Select Existing Item';
        
        // Set required attribute on existing select, remove from new input
        const newInput = document.getElementById('edit_item_name');
        const existingSelect = document.getElementById('edit_existing_item_name');
        if (newInput) newInput.required = false;
        if (existingSelect) existingSelect.required = true;
        
        // Clear new input
        if (newInput) newInput.value = '';
    }
}

// Make functions globally accessible
window.toggleItemNameInput = toggleItemNameInput;
window.toggleEditItemNameInput = toggleEditItemNameInput;

// Verify functions are accessible
console.log('Global functions assigned:', {
    toggleItemNameInput: typeof window.toggleItemNameInput,
    toggleEditItemNameInput: typeof window.toggleEditItemNameInput
});

// Add error handling for missing elements
document.addEventListener('DOMContentLoaded', function() {
    // Check if toggle elements exist
    const toggleCheckbox = document.getElementById('toggle-item-name-checkbox');
    const editToggleCheckbox = document.getElementById('edit-toggle-item-name-checkbox');
    
    if (toggleCheckbox) {
        console.log('Add form toggle checkbox found');
    } else {
        console.error('Add form toggle checkbox not found');
    }
    
    if (editToggleCheckbox) {
        console.log('Edit form toggle checkbox found');
    } else {
        console.error('Edit form toggle checkbox not found');
    }
});
