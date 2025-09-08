// Dashboard JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sales Dashboard loaded successfully!');
    
    // Initialize dashboard components
    initializeDashboard();
    
    // Add any dashboard-specific functionality here
    function initializeDashboard() {
        // Add click handlers for dashboard elements
        const reloadButton = document.getElementById('reloadDashboard');
        if (reloadButton) {
            reloadButton.addEventListener('click', function(e) {
                e.preventDefault();
                reloadDashboardData();
            });
        }
        
        // Initialize tooltips for sales data
        initializeSalesData();
        
        // Initialize export functionality
        initializeExportButtons();
    }
    
    // Initialize sales data functionality
    function initializeSalesData() {
        // Add hover effects to sales cards
        const salesCards = document.querySelectorAll('.box');
        salesCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Add click handlers for customer rows
        const customerRows = document.querySelectorAll('tbody tr:not(.intro-x:empty)');
        customerRows.forEach(row => {
            if (!row.querySelector('td[colspan]')) { // Skip empty state rows
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('a')) { // Don't trigger if clicking on links
                        const customerName = this.querySelector('.font-medium').textContent;
                        showCustomerDetails(customerName);
                    }
                });
                
                row.style.cursor = 'pointer';
            }
        });
    }
    
    // Initialize export functionality
    function initializeExportButtons() {
        const exportButtons = document.querySelectorAll('button[class*="btn box"]');
        exportButtons.forEach(button => {
            if (button.textContent.includes('Export')) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const exportType = this.textContent.includes('Excel') ? 'excel' : 'pdf';
                    exportSalesData(exportType);
                });
            }
        });
    }
    
    // Function to reload dashboard data
    function reloadDashboardData() {
        const reloadButton = document.getElementById('reloadDashboard');
        const originalText = reloadButton.innerHTML;
        
        // Show loading state
        reloadButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Reloading...
        `;
        reloadButton.disabled = true;
        
        // Reload the page to get fresh data
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
    
    // Function to show customer details
    window.showCustomerDetails = function(customerName) {
        // You can implement a modal or redirect to customer details page
        console.log('Showing details for customer:', customerName);
        
        // For now, just redirect to the customer page
        // You can enhance this with a modal later
        window.location.href = '/customer';
    };
    
    // Function to export sales data
    function exportSalesData(type) {
        console.log(`Exporting sales data as ${type}`);
        
        // Show notification
        showNotification('info', `Preparing ${type.toUpperCase()} export...`);
        
        // You can implement actual export functionality here
        // For now, just simulate the export process
        setTimeout(() => {
            showNotification('success', `Sales data exported to ${type.toUpperCase()} successfully!`);
        }, 2000);
    }
    
    // Function to show notifications
    function showNotification(type, message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${getNotificationClass(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${getNotificationIcon(type)}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <span class="sr-only">Close</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
    
    function getNotificationClass(type) {
        switch(type) {
            case 'success': return 'bg-green-50 border border-green-200 text-green-800';
            case 'error': return 'bg-red-50 border border-red-200 text-red-800';
            case 'warning': return 'bg-yellow-50 border border-yellow-200 text-yellow-800';
            default: return 'bg-blue-50 border border-blue-200 text-blue-800';
        }
    }
    
    function getNotificationIcon(type) {
        switch(type) {
            case 'success': return '<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            case 'error': return '<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            case 'warning': return '<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            default: return '<svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
        }
    }
    
    // Auto-refresh dashboard every 5 minutes (300000 ms) - optional
    // setInterval(reloadDashboardData, 300000);
});
