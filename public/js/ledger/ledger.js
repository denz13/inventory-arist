// Ledger JavaScript functionality

// Search functionality
function debouncedSearch(searchTerm) {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        handleSearchWithPagination(searchTerm);
    }, 500);
}

// Function to handle search with pagination
function handleSearchWithPagination(searchTerm) {
    const currentUrl = new URL(window.location);
    
    if (searchTerm && searchTerm.trim()) {
        currentUrl.searchParams.set('search', searchTerm.trim());
    } else {
        currentUrl.searchParams.delete('search');
    }
    
    // Reset to first page when searching
    currentUrl.searchParams.delete('page');
    
    // Preserve per_page setting
    const perPageSelect = document.getElementById('per-page-select');
    if (perPageSelect) {
        currentUrl.searchParams.set('per_page', perPageSelect.value);
    }
    
    // Use AJAX update if the function exists, otherwise fallback to page reload
    if (typeof updateTableWithPagination === 'function') {
        updateTableWithPagination(currentUrl.toString());
    } else {
        window.location.href = currentUrl.toString();
    }
}

// AJAX table updates
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
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
    console.log('Table handlers reinitialized');
}

// View client function
function viewClient(clientId) {
    console.log('Viewing client:', clientId);
    
    // Show loading state
    showNotification('info', 'Loading client details...');
    
    // Debug: Log the URL being fetched
    const url = `/ledger/${clientId}`;
    console.log('Fetching from URL:', url);
    
    // Fetch client data from the correct endpoint
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('View data received:', data);
        if (data.success) {
            console.log('Data is successful, populating modal...');
            populateViewModal(data.data);
            console.log('View modal populated successfully');
            // Modal will be shown automatically by Tailwind CSS due to data-tw-toggle and data-tw-target
        } else {
            console.error('Data not successful:', data.message);
            showNotification('error', data.message || 'Error loading client details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

// Function to show modal - no longer needed with Tailwind CSS
// function showModal() {
//     // This function is no longer needed as Tailwind CSS handles modal display
// }

// Function to hide modal - no longer needed with Tailwind CSS
// function hideModal() {
//     // This function is no longer needed as Tailwind CSS handles modal hiding
// }

function populateViewModal(client) {
    console.log('Populating view modal with:', client);
    console.log('Client object keys:', Object.keys(client));
    console.log('Client items:', client.items);
    
    try {
        // Populate basic information
        const clientNameElement = document.getElementById('view-client-name');
        const clientAddressElement = document.getElementById('view-client-address');
        const clientStatusElement = document.getElementById('view-client-status');
        const clientDateElement = document.getElementById('view-client-created-date');
        
        console.log('Found elements:', {
            clientNameElement,
            clientAddressElement,
            clientStatusElement,
            clientDateElement
        });
        
        if (clientNameElement) {
            clientNameElement.textContent = client.client_name || 'N/A';
            console.log('Set client name to:', client.client_name || 'N/A');
        }
        if (clientAddressElement) {
            clientAddressElement.textContent = client.address || 'N/A';
            console.log('Set client address to:', client.address || 'N/A');
        }
        if (clientStatusElement) {
            clientStatusElement.textContent = (client.status || 'N/A').charAt(0).toUpperCase() + (client.status || 'N/A').slice(1);
            console.log('Set client status to:', client.status || 'N/A');
        }
        
        // Format created date
        if (clientDateElement && client.created_at) {
            const createdDate = new Date(client.created_at);
            const formattedDate = createdDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            clientDateElement.textContent = formattedDate;
            console.log('Set created date to:', formattedDate);
        }
        
        // Populate items table
        const viewItemsContainer = document.getElementById('view-items-container');
        console.log('Items container found:', viewItemsContainer);
        
        if (viewItemsContainer) {
            viewItemsContainer.innerHTML = '';
            
            if (client.items && client.items.length > 0) {
                console.log('Found items:', client.items.length);
                client.items.forEach((item, index) => {
                    console.log('Processing item:', index, item);
                    const itemRow = document.createElement('tr');
                    itemRow.className = index % 2 === 0 ? 'bg-white' : 'bg-slate-50';
                    itemRow.innerHTML = `
                        <td class="border border-slate-200 px-4 py-3 text-slate-800">${item.description || 'N/A'}</td>
                        <td class="border border-slate-200 px-4 py-3 text-center text-slate-800 font-medium">${item.quantity || 'N/A'}</td>
                        <td class="border border-slate-200 px-4 py-3 text-slate-800">${item.rec_meter || 'N/A'}</td>
                        <td class="border border-slate-200 px-4 py-3 text-center text-slate-800 font-medium">${item.qty || 'N/A'}</td>
                    `;
                    viewItemsContainer.appendChild(itemRow);
                    console.log('Added item row:', index);
                });
            } else {
                console.log('No items found for this client');
                // Show "no items" message
                const noItemsRow = document.createElement('tr');
                noItemsRow.innerHTML = '<td colspan="4" class="border border-slate-200 px-4 py-8 text-center text-slate-500 bg-slate-50">No items found</td>';
                viewItemsContainer.appendChild(noItemsRow);
                console.log('Added no items message');
            }
        } else {
            console.error('view-items-container not found');
        }
        
        console.log('View modal populated successfully');
    } catch (error) {
        console.error('Error populating view modal:', error);
        showNotification('error', 'Error populating modal data');
    }
}

// Edit client function
function editClient(clientId) {
    console.log('Editing client:', clientId);
    
    // Redirect to inventory edit page or show edit modal
    window.location.href = `/inventory?edit=${clientId}`;
}

// Delete functions
let currentDeleteId = null;

function prepareDelete(clientId, clientName, clientAddress) {
    console.log('prepareDelete called with:', { clientId, clientName, clientAddress });
    
    // Store the current delete ID
    currentDeleteId = clientId;
    
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
        showNotification('error', 'Error: No client selected for deletion');
        return;
    }
    
    console.log('Confirming deletion of client:', currentDeleteId);
    
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delete response received:', data);
        
        if (data.success) {
            // Show success message
            showNotification('success', 'Client deleted successfully!');
            
            // Close modal
            const modal = document.getElementById('delete-confirmation-modal');
            if (modal) {
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
            showNotification('error', data.message || 'Error occurred while deleting client');
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

// Notification function
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

// Test modal function
function testModal() {
    console.log('Testing modal...');
    
    // Create test data
    const testClient = {
        client_name: 'Test Client',
        address: 'Test Address 123',
        status: 'active',
        created_at: new Date().toISOString(),
        items: [
            {
                description: 'Test Item 1',
                quantity: 5,
                rec_meter: '100m',
                qty: 3
            },
            {
                description: 'Test Item 2',
                quantity: 10,
                rec_meter: '200m',
                qty: 7
            }
        ]
    };
    
    // Populate modal with test data
    populateViewModal(testClient);
    
    // Show modal using Tailwind CSS
    const modal = document.getElementById('view-client-modal');
    if (modal) {
        // Use Tailwind CSS modal show
        if (typeof window.twModal !== 'undefined') {
            window.twModal.show(modal);
        } else {
            // Fallback: show modal manually
            modal.style.display = 'block';
            modal.classList.add('show');
            modal.style.zIndex = '1050';
            document.body.classList.add('modal-open');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ledger system initialized');
    
    // Add click handlers to pagination links for better UX
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        if (link.href) {
            link.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default navigation
                
                // Add loading state
                const loadingIndicator = document.createElement('div');
                loadingIndicator.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                loadingIndicator.innerHTML = `
                    <div class="bg-white p-4 rounded-lg">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <p class="mt-2 text-sm">Loading...</p>
                    </div>
                `;
                document.body.appendChild(loadingIndicator);
                
                // Use AJAX update if the function exists, otherwise fallback to page reload
                if (typeof updateTableWithPagination === 'function') {
                    updateTableWithPagination(link.href);
                    // Remove loading indicator after AJAX update
                    setTimeout(() => {
                        if (loadingIndicator.parentNode) {
                            loadingIndicator.parentNode.removeChild(loadingIndicator);
                        }
                    }, 500);
                } else {
                    // Fallback to page reload
                    window.location.href = link.href;
                }
            });
        }
    });
});

// Make functions globally available
window.viewClient = viewClient;
// window.showModal = showModal; // This line is no longer needed
// window.hideModal = hideModal; // This line is no longer needed
window.populateViewModal = populateViewModal;
window.testModal = testModal;
window.showNotification = showNotification;
