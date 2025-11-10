@extends('layout.app')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Customer Management
</h2>

@if(session('success'))
    <div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-5 h-5 mr-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        {{ session('success') }}
        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle w-5 h-5 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        {{ session('error') }}
        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle w-5 h-5 mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
@endif

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add-customer-modal">Add New Customer</button>
        
        <div class="hidden md:block mx-auto text-slate-500">
            @if(isset($customers) && $customers instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries
            @else
                Showing {{ $customers->count() }} entries
            @endif
        </div>
        
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" id="search-input" class="form-control w-56 box pr-10" placeholder="Search..." onkeyup="debouncedSearch(this.value)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="search" class="lucide lucide-search w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
            </div>
        </div>
    </div>
    
    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">CUSTOMER NAME</th>
                    <th class="whitespace-nowrap">ADDRESS</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="intro-x hover:bg-slate-50">
                    <td>
                        <span class="font-medium whitespace-nowrap">
                            {{ $customer->customer_name }}
                        </span>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-normal max-w-md">
                            {{ $customer->address }}
                        </div>
                    </td>
                    <td class="w-40">
                        <div class="flex items-center justify-center {{ $customer->status === 'active' ? 'text-success' : 'text-danger' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                            </svg> 
                            {{ ucfirst($customer->status) }}
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                         <button class="btn btn-outline-success btn-sm mr-1" data-tw-toggle="modal" data-tw-target="#add-order-modal" onclick="addOrder({{ $customer->id }}, '{{ $customer->customer_name }}')" title="Add Order for {{ $customer->customer_name }}">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-circle">
                                     <circle cx="12" cy="12" r="10"></circle>
                                     <line x1="12" y1="8" x2="12" y2="16"></line>
                                     <line x1="8" y1="12" x2="16" y2="12"></line>
                                 </svg>
                             </button>
                            <button class="btn btn-outline-primary btn-sm mr-1" data-tw-toggle="modal" data-tw-target="#edit-customer-modal" onclick="editCustomer({{ $customer->id }})" title="Edit Customer - {{ $customer->customer_name }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" data-tw-toggle="modal" data-tw-target="#delete-customer-modal" onclick="prepareCustomerDelete({{ $customer->id }}, '{{ $customer->customer_name }}')" title="Delete Customer - {{ $customer->customer_name }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-slate-500">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    @include('_partials.dynamic-pagination')
</div>

<!-- BEGIN: Add Customer Modal -->
<div id="add-customer-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add New Customer</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="add-customer-form" action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label for="customer_name" class="form-label">Customer Name *</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Enter customer name" required>
                        </div>
                        <div class="col-span-12">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" name="address" class="form-control" rows="3" placeholder="Enter customer address" required></textarea>
                        </div>
                        <!-- Hidden status field - automatically set to active -->
                        <input type="hidden" name="status" value="active">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Add Customer Modal -->

<!-- BEGIN: Edit Customer Modal -->
<div id="edit-customer-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Edit Customer</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="edit-customer-form" action="" method="POST" onsubmit="return handleEditFormSubmit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_customer_id" name="customer_id">
                <div class="modal-body">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label for="edit_customer_name" class="form-label">Customer Name *</label>
                            <input type="text" id="edit_customer_name" name="customer_name" class="form-control" placeholder="Enter customer name" required>
                        </div>
                        <div class="col-span-12">
                            <label for="edit_address" class="form-label">Address *</label>
                            <textarea id="edit_address" name="address" class="form-control" rows="3" placeholder="Enter customer address" required></textarea>
                        </div>
                        <!-- Hidden status field - automatically set to active -->
                        <input type="hidden" id="edit_status" name="status" value="active">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" data-tw-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Edit Customer Modal -->

<!-- BEGIN: Delete Customer Modal -->
<div id="delete-customer-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Delete Customer</h2>
                <button class="btn-close" onclick="closeDeleteCustomerModal()" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-slate-500">Are you sure you want to delete this customer? This action cannot be undone.</p>
                <div class="mt-4 p-4 bg-slate-50 rounded-lg">
                    <div class="mb-2">
                        <strong>Customer Name:</strong> <span id="delete-customer-name" class="text-slate-700"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" onclick="closeDeleteCustomerModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmCustomerDelete()">Delete Customer</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Delete Customer Modal -->

@include('customer.add_order_modal')

<script>
// Customer management functions

// Edit customer function
function editCustomer(customerId) {
    // Show loading state
    showNotification('info', 'Loading customer data...');
    
    // Fetch customer data
    fetch(`/customer/${customerId}/edit`, {
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
            // Modal is opened automatically by data-tw-toggle
        } else {
            showNotification('error', data.message || 'Error loading customer data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    });
}

function populateEditForm(customer) {
    // Set form action
    const form = document.getElementById('edit-customer-form');
    if (form) {
        form.action = `/customer/${customer.id}`;
    }
    
    // Set customer ID
    const idField = document.getElementById('edit_customer_id');
    if (idField) {
        idField.value = customer.id;
    }
    
    // Set form fields
    const nameField = document.getElementById('edit_customer_name');
    if (nameField) {
        nameField.value = customer.customer_name || '';
    }
    
    const addressField = document.getElementById('edit_address');
    if (addressField) {
        addressField.value = customer.address || '';
    }
    
    const statusField = document.getElementById('edit_status');
    if (statusField) {
        statusField.value = customer.status || 'active';
    }
}

function handleEditFormSubmit(e) {
    e.preventDefault();
    console.log('Edit form submission started...');
    
    // Get form data
    const form = document.getElementById('edit-customer-form');
    const formData = new FormData(form);
    
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
            showNotification('success', 'Customer updated successfully!');
            // Close modal using Tailwind's approach
            const modal = document.querySelector('#edit-customer-modal');
            const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
            modalInstance.hide();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error occurred while updating customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
    
    return false;
}

// Delete customer functions
let currentDeleteCustomerId = null;

function prepareCustomerDelete(customerId, customerName) {
    currentDeleteCustomerId = customerId;
    document.getElementById('delete-customer-name').textContent = customerName;
    // Modal is opened automatically by data-tw-toggle
}

function closeDeleteCustomerModal() {
    const modal = document.querySelector('#delete-customer-modal');
    const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
    modalInstance.hide();
    currentDeleteCustomerId = null;
}

function confirmCustomerDelete() {
    if (!currentDeleteCustomerId) {
        console.error('No delete customer ID set');
        showNotification('error', 'Error: No customer selected for deletion');
        return;
    }
    
    console.log('Confirming deletion of customer:', currentDeleteCustomerId);
    
    // Show loading state
    const deleteBtn = document.querySelector('#delete-customer-modal .btn-danger');
    const originalText = deleteBtn.textContent;
    deleteBtn.disabled = true;
    deleteBtn.textContent = 'Deleting...';
    
    // Send delete request
    fetch(`/customer/${currentDeleteCustomerId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Delete customer response received:', data);
        
        if (data.success) {
            showNotification('success', 'Customer deleted successfully!');
            closeDeleteCustomerModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message || 'Error occurred while deleting customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Network error occurred. Please try again.');
    })
    .finally(() => {
        deleteBtn.disabled = false;
        deleteBtn.textContent = originalText;
        currentDeleteCustomerId = null;
    });
}

// Search functionality
function searchCustomer(searchTerm) {
    console.log('Searching for:', searchTerm);
    
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    if (!searchTerm || searchTerm.trim() === '') {
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    const searchLower = searchTerm.toLowerCase().trim();
    let visibleCount = 0;
    
    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) {
            row.style.display = 'none';
            return;
        }
        
        const customerName = row.querySelector('td:first-child span')?.textContent?.toLowerCase() || '';
        const address = row.querySelector('td:nth-child(2) div')?.textContent?.toLowerCase() || '';
        const status = row.querySelector('td:nth-child(3) div')?.textContent?.toLowerCase() || '';
        
        const isMatch = customerName.includes(searchLower) || 
                       address.includes(searchLower) || 
                       status.includes(searchLower);
        
        if (isMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
}

// Enhanced search with debouncing
let searchTimeout;
function debouncedSearch(searchTerm) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchCustomer(searchTerm);
    }, 300);
}

        // Add Order functions
        let orderItemCount = 1;

        function addOrder(customerId, customerName) {
            // Set customer information
            document.getElementById('order-customer-id').textContent = customerId;
            document.getElementById('order-customer-name').textContent = customerName;
            document.getElementById('order_customer_id').value = customerId;
            
            // Set current date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_ordered').value = today;
            
            // Reset form
            resetOrderForm();
        }

function addOrderItem() {
    const container = document.getElementById('order-items-container');
    const newItem = document.querySelector('.order-item').cloneNode(true);
    
    // Update name attributes for new item
    const selects = newItem.querySelectorAll('[name^="items"]');
    selects.forEach(select => {
        const name = select.getAttribute('name');
        select.setAttribute('name', name.replace('[0]', `[${orderItemCount}]`));
    });
    
    // Clear values
    newItem.querySelectorAll('input, select').forEach(input => {
        if (input.type !== 'button') {
            input.value = '';
        }
    });
    
    // Enable remove button
    const removeBtn = newItem.querySelector('button[onclick^="removeOrderItem"]');
    if (removeBtn) {
        removeBtn.disabled = false;
    }
    
    container.appendChild(newItem);
    orderItemCount++;
    
    // Add event listeners to new item
    setupOrderItemEvents(newItem);
    updateGrandTotal();
}

function removeOrderItem(button) {
    const item = button.closest('.order-item');
    if (document.querySelectorAll('.order-item').length > 1) {
        item.remove();
        updateGrandTotal();
    }
}

function setupOrderItemEvents(item) {
    const itemSelect = item.querySelector('.item-select');
    const quantityInput = item.querySelector('.quantity-input');
    const priceInput = item.querySelector('.price-input');
    const totalInput = item.querySelector('.total-input');
    
    // Item selection change
    if (itemSelect) {
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            const maxQty = selectedOption.getAttribute('data-qty') || 0;
            
            priceInput.value = price;
            quantityInput.max = maxQty;
            quantityInput.value = '';
            totalInput.value = '';
            updateGrandTotal();
        });
    }
    
    // Quantity change
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            const quantity = parseFloat(this.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = quantity * price;
            totalInput.value = total.toFixed(2);
            updateGrandTotal();
        });
    }
}

function updateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.total-input').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    document.getElementById('grand-total').textContent = `₱${grandTotal.toFixed(2)}`;
}

function resetOrderForm() {
    // Reset all form fields
    document.getElementById('add-order-form').reset();
    
    // Set current date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('order_date').value = today;
    
    // Reset to single item
    const container = document.getElementById('order-items-container');
    const firstItem = container.querySelector('.order-item');
    
    // Remove all items except the first one
    const allItems = container.querySelectorAll('.order-item');
    for (let i = 1; i < allItems.length; i++) {
        allItems[i].remove();
    }
    
    // Reset first item
    firstItem.querySelectorAll('input, select').forEach(input => {
        if (input.type !== 'button') {
            input.value = '';
        }
    });
    
    // Disable remove button for first item
    const removeBtn = firstItem.querySelector('button[onclick^="removeOrderItem"]');
    if (removeBtn) {
        removeBtn.disabled = true;
    }
    
    orderItemCount = 1;
    updateGrandTotal();
}

// Initialize order form events on page load
document.addEventListener('DOMContentLoaded', function() {
    // Setup events for initial order item
    const firstItem = document.querySelector('.order-item');
    if (firstItem) {
        setupOrderItemEvents(firstItem);
    }
    
    // Handle order form submission
    const orderForm = document.getElementById('add-order-form');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleOrderFormSubmit();
        });
    }
});

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
</script>

@endsection

@section('scripts')
<script src="{{ asset('js/customer/add_customer.js') }}"></script>
@endsection
