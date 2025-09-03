// Customer Management JavaScript Functions

document.addEventListener('DOMContentLoaded', function() {
    // Initialize customer management features
    initializeCustomerManagement();
});

function initializeCustomerManagement() {
    console.log('Customer management initialized');
    
    // Set up any event listeners that need to be bound on page load
    setupFormValidation();
    setupAutoCalculation();
}

// Form validation helpers
function setupFormValidation() {
    // Customer form validation
    const customerNameInputs = document.querySelectorAll('input[name="customer_name"]');
    customerNameInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateCustomerName(this);
        });
    });
    
    // Address validation
    const addressInputs = document.querySelectorAll('textarea[name="address"]');
    addressInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateAddress(this);
        });
    });
    
    // Order quantity validation
    const quantityInputs = document.querySelectorAll('input[name="quantity_order"]');
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateQuantity(this);
        });
    });
}

function validateCustomerName(input) {
    const value = input.value.trim();
    const isValid = value.length >= 2 && value.length <= 255;
    
    // Remove any existing validation classes
    input.classList.remove('border-red-500', 'border-green-500');
    
    if (value.length > 0) {
        if (isValid) {
            input.classList.add('border-green-500');
        } else {
            input.classList.add('border-red-500');
        }
    }
    
    return isValid;
}

function validateAddress(input) {
    const value = input.value.trim();
    const isValid = value.length >= 5 && value.length <= 500;
    
    // Remove any existing validation classes
    input.classList.remove('border-red-500', 'border-green-500');
    
    if (value.length > 0) {
        if (isValid) {
            input.classList.add('border-green-500');
        } else {
            input.classList.add('border-red-500');
        }
    }
    
    return isValid;
}

function validateQuantity(input) {
    const value = parseInt(input.value);
    const max = parseInt(input.max);
    const isValid = value > 0 && (!max || value <= max);
    
    // Remove any existing validation classes
    input.classList.remove('border-red-500', 'border-green-500');
    
    if (input.value.length > 0) {
        if (isValid) {
            input.classList.add('border-green-500');
        } else {
            input.classList.add('border-red-500');
        }
    }
    
    return isValid;
}

// Auto calculation setup
function setupAutoCalculation() {
    // Add event listeners for order total calculation
    const inventorySelects = document.querySelectorAll('select[name="inventory_quantity_id"]');
    inventorySelects.forEach(select => {
        select.addEventListener('change', function() {
            if (this.id.includes('edit')) {
                updateEditItemDetails();
            } else {
                updateItemDetails();
            }
        });
    });
    
    const quantityInputs = document.querySelectorAll('input[name="quantity_order"]');
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.id.includes('edit')) {
                calculateEditTotal();
            } else {
                calculateTotal();
            }
        });
    });
}

// Advanced search functions
function performAdvancedSearch(filters) {
    const {
        customerName = '',
        address = '',
        status = '',
        hasOrders = null,
        orderStatus = ''
    } = filters;
    
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        // Skip detail rows and rows with colspan
        if (row.id.startsWith('details-') || row.querySelector('td[colspan]')) {
            return;
        }
        
        const rowCustomerName = row.querySelector('td:first-child span')?.textContent?.toLowerCase() || '';
        const rowAddress = row.querySelector('td:nth-child(2) div')?.textContent?.toLowerCase() || '';
        const rowStatus = row.querySelector('td:nth-child(4) div')?.textContent?.toLowerCase() || '';
        const orderCount = parseInt(row.querySelector('td:nth-child(3) span')?.textContent || '0');
        
        let isMatch = true;
        
        // Check customer name filter
        if (customerName && !rowCustomerName.includes(customerName.toLowerCase())) {
            isMatch = false;
        }
        
        // Check address filter
        if (address && !rowAddress.includes(address.toLowerCase())) {
            isMatch = false;
        }
        
        // Check status filter
        if (status && !rowStatus.includes(status.toLowerCase())) {
            isMatch = false;
        }
        
        // Check has orders filter
        if (hasOrders !== null) {
            if (hasOrders && orderCount === 0) {
                isMatch = false;
            } else if (!hasOrders && orderCount > 0) {
                isMatch = false;
            }
        }
        
        if (isMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    return visibleCount;
}

// Customer stats functions
function getCustomerStats() {
    const tableBody = document.querySelector('tbody');
    const customerRows = tableBody.querySelectorAll('tr:not([id^="details-"]):not([colspan])');
    
    let totalCustomers = 0;
    let activeCustomers = 0;
    let customersWithOrders = 0;
    let totalOrders = 0;
    
    customerRows.forEach(row => {
        if (row.querySelector('td[colspan]')) return; // Skip "no data" rows
        
        totalCustomers++;
        
        const status = row.querySelector('td:nth-child(4) div')?.textContent?.toLowerCase() || '';
        if (status.includes('active')) {
            activeCustomers++;
        }
        
        const orderCount = parseInt(row.querySelector('td:nth-child(3) span')?.textContent || '0');
        if (orderCount > 0) {
            customersWithOrders++;
            totalOrders += orderCount;
        }
    });
    
    return {
        totalCustomers,
        activeCustomers,
        customersWithOrders,
        totalOrders,
        averageOrdersPerCustomer: totalOrders / (customersWithOrders || 1)
    };
}

// Export customer data (for potential future use)
function exportCustomerData(format = 'csv') {
    const stats = getCustomerStats();
    const timestamp = new Date().toISOString().split('T')[0];
    
    let content = '';
    
    if (format === 'csv') {
        content = 'Customer Name,Address,Status,Order Count\n';
        
        const tableBody = document.querySelector('tbody');
        const customerRows = tableBody.querySelectorAll('tr:not([id^="details-"]):not([colspan])');
        
        customerRows.forEach(row => {
            if (row.querySelector('td[colspan]')) return;
            
            const name = row.querySelector('td:first-child span')?.textContent || '';
            const address = row.querySelector('td:nth-child(2) div')?.textContent || '';
            const status = row.querySelector('td:nth-child(4) div')?.textContent || '';
            const orderCount = row.querySelector('td:nth-child(3) span')?.textContent || '0';
            
            content += `"${name}","${address}","${status}","${orderCount}"\n`;
        });
    }
    
    return {
        content,
        filename: `customers_${timestamp}.${format}`,
        stats
    };
}

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatDateTime(dateString) {
    return new Date(dateString).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Order management helpers
function getOrderStatusColor(status) {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-blue-100 text-blue-800',
        'delivered': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
    };
    
    return colors[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
}

function calculateOrderTotal(price, quantity) {
    return (parseFloat(price) || 0) * (parseInt(quantity) || 0);
}

// Debug functions (for development)
function debugCustomerData() {
    console.log('Customer Management Debug Info:');
    console.log('Stats:', getCustomerStats());
    console.log('Available inventory:', window.inventoryQuantities || 'Not loaded');
}

// Global functions for console access
window.customerDebug = {
    getStats: getCustomerStats,
    exportData: exportCustomerData,
    advancedSearch: performAdvancedSearch,
    debug: debugCustomerData
};

console.log('Customer.js loaded successfully');
