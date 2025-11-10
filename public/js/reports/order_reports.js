// Order Reports JavaScript

// Print order function
function printOrder(orderId) {
    // Show loading notification
    showNotification('info', 'Generating PDF...');

    // Create URL for PDF review
    const printUrl = `/reports/order/${orderId}/print`;

    // Open the PDF in a new tab instead of popup window
    const printWindow = window.open(printUrl, '_blank');

    if (printWindow) {
        printWindow.onload = function() {
            showNotification('success', 'PDF opened in new tab!');
        };

        printWindow.onerror = function() {
            showNotification('error', 'Error opening PDF. Please try again.');
        };
    } else {
        showNotification('error', 'Popup blocked. Please allow popups for this site.');
    }
}

// Toggle order details function
function toggleOrderDetails(customerId) {
    const detailsRow = document.getElementById(`order-details-${customerId}`);
    if (detailsRow) {
        if (detailsRow.classList.contains('hidden')) {
            detailsRow.classList.remove('hidden');
            showNotification('info', 'Order details expanded');
        } else {
            detailsRow.classList.add('hidden');
            showNotification('info', 'Order details collapsed');
        }
    }
}

// Filter orders based on selected criteria
function filterOrders() {
    const status = document.getElementById('status-filter').value;
    const customer = document.getElementById('customer-filter').value;
    const dateFrom = document.getElementById('date-from').value;
    const dateTo = document.getElementById('date-to').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (customer) params.append('customer_id', customer);
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    // Show loading state
    showNotification('info', 'Filtering orders...');
    
    // Make AJAX request
    fetch(`/reports/order?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateOrdersTable(data.data);
            updateStatusCounts(data.status_counts);
            updateTotalRevenue(data.total_revenue);
            showNotification('success', 'Orders filtered successfully!');
        } else {
            showNotification('error', data.message || 'Error filtering orders');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

// Update orders table with filtered data
function updateOrdersTable(groupedOrders) {
    const tbody = document.getElementById('orders-tbody');
    
    if (groupedOrders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-8 text-slate-500">No orders found</td></tr>';
        return;
    }
    
    tbody.innerHTML = '';
    
    groupedOrders.forEach(groupedOrder => {
        // Status badge classes
        const statusClasses = {
            'delivered': 'bg-green-100 text-green-800',
            'confirmed': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800',
            'pending': 'bg-yellow-100 text-yellow-800'
        };

        // Main row
        const mainRow = document.createElement('tr');
        mainRow.className = 'intro-x hover:bg-slate-50';
        mainRow.innerHTML = `
            <td>
                <div class="font-medium text-primary">${groupedOrder.order_count} Order(s)</div>
                <div class="text-xs text-slate-500">Click to expand</div>
            </td>
            <td>
                <div class="font-medium whitespace-nowrap">${groupedOrder.customer?.customer_name || 'N/A'}</div>
                <div class="text-xs text-slate-500">${groupedOrder.customer?.address || 'N/A'}</div>
            </td>
            <td>
                <div class="font-medium">${groupedOrder.order_count} Item(s)</div>
                <div class="text-xs text-slate-500">Multiple items</div>
            </td>
            <td>
                <div class="text-slate-500 text-sm">Mixed Categories</div>
            </td>
            <td class="text-center">
                <span class="font-medium">${groupedOrder.total_quantity}</span>
            </td>
            <td class="text-center">
                <span class="font-medium text-green-600">₱${parseFloat(groupedOrder.total_amount || 0).toFixed(2)}</span>
            </td>
            <td class="text-center">
                <span class="text-slate-500">${groupedOrder.delivery_date ? new Date(groupedOrder.delivery_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A'}</span>
            </td>
            <td class="text-center">
                <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClasses[groupedOrder.status] || 'bg-gray-100 text-gray-800'}">
                    ${groupedOrder.status.charAt(0).toUpperCase() + groupedOrder.status.slice(1)}
                </span>
            </td>
            <td class="text-center">
                <button class="btn btn-outline-primary btn-sm" onclick="toggleOrderDetails(${groupedOrder.customer_id})" title="View Details">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </td>
        `;
        
        tbody.appendChild(mainRow);

        // Details row
        const detailsRow = document.createElement('tr');
        detailsRow.id = `order-details-${groupedOrder.customer_id}`;
        detailsRow.className = 'hidden';
        
        let ordersHtml = '';
        groupedOrder.orders.forEach(order => {
            ordersHtml += `
                <tr>
                    <td class="font-medium text-primary">#${order.id}</td>
                    <td>${order.inventory_quantity?.inventory?.item_name || 'N/A'}</td>
                    <td>${order.inventory_quantity?.inventory?.category?.category_name || 'N/A'}</td>
                    <td class="text-center">${order.quantity_order || 0}</td>
                    <td class="text-center text-green-600">₱${parseFloat(order.total_amount_price || 0).toFixed(2)}</td>
                    <td class="text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClasses[order.status] || 'bg-gray-100 text-gray-800'}">
                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-outline-primary btn-sm" onclick="printOrder(${order.id})" title="Print Order">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        </button>
                    </td>
                </tr>
            `;
        });

        detailsRow.innerHTML = `
            <td colspan="9" class="bg-slate-50 p-0">
                <div class="p-4">
                    <h4 class="font-medium text-slate-700 mb-3">Order Details for ${groupedOrder.customer?.customer_name || 'N/A'}</h4>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${ordersHtml}
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        `;
        
        tbody.appendChild(detailsRow);
    });
}

// Update status counts in the summary cards
function updateStatusCounts(statusCounts) {
    // This would require updating the summary cards dynamically
    // For now, we'll just log the counts
    console.log('Updated status counts:', statusCounts);
}

// Update total revenue display
function updateTotalRevenue(totalRevenue) {
    const revenueElement = document.querySelector('.text-3xl.font-bold.text-green-600');
    if (revenueElement) {
        revenueElement.textContent = `₱${parseFloat(totalRevenue).toFixed(2)}`;
    }
}

// Clear all filters
function clearFilters() {
    document.getElementById('status-filter').value = '';
    document.getElementById('customer-filter').value = '';
    document.getElementById('date-from').value = '';
    document.getElementById('date-to').value = '';
    
    // Reload the page to show all orders
    window.location.reload();
}

// Search functionality
function searchOrders(searchTerm) {
    const tableBody = document.getElementById('orders-tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    if (!searchTerm || searchTerm.trim() === '') {
        // Show all rows if search is empty
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    const searchLower = searchTerm.toLowerCase().trim();
    let visibleCount = 0;
    
    rows.forEach(row => {
        // Skip rows with colspan (like "No orders found")
        if (row.querySelector('td[colspan]')) {
            row.style.display = 'none';
            return;
        }
        
        // Get text content from all cells
        const cells = row.querySelectorAll('td');
        let rowText = '';
        cells.forEach(cell => {
            rowText += cell.textContent.toLowerCase() + ' ';
        });
        
        // Check if any field contains the search term
        const isMatch = rowText.includes(searchLower);
        
        if (isMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update search results count
    if (searchTerm && visibleCount === 0) {
        // Show "No results found" message
        const noResultsRow = document.createElement('tr');
        noResultsRow.innerHTML = '<td colspan="9" class="text-center py-8 text-slate-500">No orders found matching your search</td>';
        noResultsRow.id = 'no-results-row';
        
        // Remove existing no-results row if it exists
        const existingNoResults = document.getElementById('no-results-row');
        if (existingNoResults) {
            existingNoResults.remove();
        }
        
        tableBody.appendChild(noResultsRow);
    } else {
        // Remove no-results row if it exists
        const existingNoResults = document.getElementById('no-results-row');
        if (existingNoResults) {
            existingNoResults.remove();
        }
    }
}

// Enhanced search with debouncing
let searchTimeout;
function debouncedSearch(searchTerm) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchOrders(searchTerm);
    }, 300); // Wait 300ms after user stops typing
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

// Export functions for CSV/Excel (future enhancement)
function exportOrders(format = 'csv') {
    // This would implement CSV/Excel export functionality
    showNotification('info', `Export to ${format.toUpperCase()} feature coming soon!`);
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set default date range (last 30 days)
    const today = new Date();
    const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    
    document.getElementById('date-from').value = thirtyDaysAgo.toISOString().split('T')[0];
    document.getElementById('date-to').value = today.toISOString().split('T')[0];
    
    console.log('Order Reports page initialized');
});
