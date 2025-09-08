// Edit category functions
let currentEditCategoryId = null;

function editCategory(categoryId) {
    console.log('Editing category:', categoryId);
    currentEditCategoryId = categoryId;
    
    // Show loading state
    showNotification('info', 'Loading category data...');
    
    // Fetch category data
    fetch(`/categories/${categoryId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateEditForm(data.data);
            console.log('Edit form populated successfully');
            
            // Show the modal using open_modal function
            open_modal('#edit-category-modal');
        } else {
            showNotification('error', data.message || 'Error loading category data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

function populateEditForm(category) {
    console.log('Populating edit form with:', category);
    
    // Set form action
    document.getElementById('edit-category-form').action = `/categories/${category.id}`;
    
    // Set category ID
    document.getElementById('edit_category_id').value = category.id;
    
    // Set basic fields
    document.getElementById('edit_category_name').value = category.category_name;
    document.getElementById('edit_status').value = category.status;
}

function handleEditFormSubmit(e) {
    e.preventDefault();
    console.log('Edit form submission started...');
    
    // Get form data
    const form = document.getElementById('edit-category-form');
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Edit response received:', data);
        
        if (data.success) {
            // Show success message
            showNotification('success', 'Category updated successfully!');
            
            // Close modal
            close_modal('#edit-category-modal');
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            showNotification('error', data.message || 'Error occurred while updating category');
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
    const form = document.getElementById('edit-category-form');
    const categoryName = form.querySelector('#edit_category_name').value.trim();
    
    if (!categoryName) {
        alert('Please enter Category Name');
        return false;
    }
    
    return true;
}

// Delete category functions
let currentDeleteCategoryId = null;

function prepareCategoryDelete(categoryId, categoryName) {
    currentDeleteCategoryId = categoryId;
    document.getElementById('delete-category-name').textContent = categoryName;
    open_modal('#delete-category-modal');
}

function closeDeleteCategoryModal() {
    close_modal('#delete-category-modal');
    currentDeleteCategoryId = null;
}

function confirmCategoryDelete() {
    if (!currentDeleteCategoryId) {
        console.error('No delete category ID set');
        showNotification('error', 'Error: No category selected for deletion');
        return;
    }
    
    console.log('Confirming deletion of category:', currentDeleteCategoryId);
    
    // Show loading state
    const deleteBtn = document.querySelector('#delete-category-modal .btn-danger');
    const originalText = deleteBtn.textContent;
    deleteBtn.disabled = true;
    deleteBtn.textContent = 'Deleting...';
    
    // Send delete request
    fetch(`/categories/${currentDeleteCategoryId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delete category response received:', data);
        
        if (data.success) {
            // Show success message
            showNotification('success', 'Category deleted successfully!');
            
            // Close modal
            closeDeleteCategoryModal();
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            showNotification('error', data.message || 'Error occurred while deleting category');
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
        currentDeleteCategoryId = null;
    });
}

// Search function for categories
function searchCategories(searchTerm) {
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
        // Skip rows with colspan (like "No categories found") and no-data-message
        if (row.querySelector('td[colspan]') || row.classList.contains('no-data-message')) {
            row.style.display = 'none';
            return;
        }
        
        const categoryName = row.querySelector('td:first-child span')?.textContent?.toLowerCase() || '';
        const status = row.querySelector('td:nth-child(2) div')?.textContent?.toLowerCase() || '';
        const createdDate = row.querySelector('td:nth-child(3) span')?.textContent?.toLowerCase() || '';
        
        // Check if any field contains the search term
        const isMatch = categoryName.includes(searchLower) || 
                       status.includes(searchLower) || 
                       createdDate.includes(searchLower);
        
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
    
    if (resultsText) {
        if (count === totalCount) {
            resultsText.textContent = `Showing ${totalCount} entries`;
        } else {
            resultsText.textContent = `Showing ${count} of ${totalCount} entries`;
        }
    }
}

// Enhanced search with debouncing
let searchTimeout;
function debouncedSearch(searchTerm) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchCategories(searchTerm);
    }, 300); // Wait 300ms after user stops typing
}

// Show/hide "No data available" message
function showNoDataMessage(show) {
    let noDataRow = document.querySelector('tr.no-data-message');
    
    if (show) {
        if (!noDataRow) {
            noDataRow = document.createElement('tr');
            noDataRow.className = 'no-data-message';
            noDataRow.innerHTML = '<td colspan="4" class="text-center py-8 text-slate-500">No data available</td>';
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
