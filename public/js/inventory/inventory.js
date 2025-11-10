console.log('Simplified Inventory JavaScript file loaded');

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing simplified inventory management...');
    
    // Initialize form handlers
    initializeInventoryForm();
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
            
            // Close modal manually
            const modal = document.getElementById('add-inventory-modal');
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
            
            // Reset form
            resetForm();
            
            // Reload page to show new item
            setTimeout(() => {
                window.location.reload();
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

// Form validation is now handled in the blade file

// Reset form
function resetForm() {
    const form = document.getElementById('add-inventory-form');
    if (form) {
        form.reset();
        console.log('Form reset successfully');
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
