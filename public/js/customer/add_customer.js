/**
 * Customer Management JavaScript
 * 
 * This file contains additional customer-specific functionality
 * Main functions are in the blade template for now
 */

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Customer management page loaded');
    
    // Add form submission handler
    const addForm = document.getElementById('add-customer-form');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            // Basic client-side validation
            const customerName = document.getElementById('customer_name').value.trim();
            const address = document.getElementById('address').value.trim();
            
            if (!customerName) {
                e.preventDefault();
                showNotification('error', 'Customer name is required');
                return false;
            }
            
            if (!address) {
                e.preventDefault();
                showNotification('error', 'Address is required');
                return false;
            }
            
            console.log('Form validation passed');
        });
    }
    
    // Clear form on modal close
    const addModal = document.getElementById('add-customer-modal');
    if (addModal) {
        addModal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('add-customer-form');
            if (form) {
                form.reset();
            }
        });
    }
});

// Additional utility functions can be added here

