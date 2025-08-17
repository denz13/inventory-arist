<!-- BEGIN: Dynamic Pagination -->
@if(isset($inventories) && $inventories instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
<div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
    <nav class="w-full sm:w-auto sm:mr-auto">
        <ul class="pagination">
            {{-- First Page Link --}}
            @if ($inventories->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevrons-left" class="lucide lucide-chevrons-left w-4 h-4" data-lucide="chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $inventories->url(1) }}" rel="first">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevrons-left" class="lucide lucide-chevrons-left w-4 h-4" data-lucide="chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                    </a>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($inventories->previousPageUrl())
                <li class="page-item">
                    <a class="page-link" href="{{ $inventories->previousPageUrl() }}" rel="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-left" class="lucide lucide-chevron-left w-4 h-4" data-lucide="chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-left" class="lucide lucide-chevron-left w-4 h-4" data-lucide="chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </span>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $start = max(1, $inventories->currentPage() - 2);
                $end = min($inventories->lastPage(), $inventories->currentPage() + 2);
            @endphp

            {{-- Show ellipsis if needed before start --}}
            @if ($start > 1)
                <li class="page-item">
                    <span class="page-link">...</span>
                </li>
            @endif

            {{-- Page Numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $inventories->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $inventories->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Show ellipsis if needed after end --}}
            @if ($end < $inventories->lastPage())
                <li class="page-item">
                    <span class="page-link">...</span>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($inventories->nextPageUrl())
                <li class="page-item">
                    <a class="page-link" href="{{ $inventories->nextPageUrl() }}" rel="next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-right" class="lucide lucide-chevron-right w-4 h-4" data-lucide="chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-right" class="lucide lucide-chevron-right w-4 h-4" data-lucide="chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </span>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($inventories->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $inventories->url($inventories->lastPage()) }}" rel="last">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevrons-right" class="lucide lucide-chevrons-right w-4 h-4" data-lucide="chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevrons-right" class="lucide lucide-chevrons-right w-4 h-4" data-lucide="chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Page Size Selection --}}
    <div class="flex items-center space-x-2">
        <select class="w-20 form-select box mt-3 sm:mt-0" id="per-page-select" onchange="changePerPage(this.value)">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
        </select>
    </div>

    
</div>

<script>
// Function to change items per page
function changePerPage(perPage) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page when changing per_page
    
    // Preserve search term if exists
    const searchInput = document.getElementById('search-input');
    if (searchInput && searchInput.value.trim()) {
        currentUrl.searchParams.set('search', searchInput.value.trim());
    }
    
    // Use AJAX update if the function exists, otherwise fallback to page reload
    if (typeof updateTableWithPagination === 'function') {
        updateTableWithPagination(currentUrl.toString());
    } else {
        window.location.href = currentUrl.toString();
    }
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

// Enhanced search function that works with pagination
function debouncedSearchWithPagination(searchTerm) {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        handleSearchWithPagination(searchTerm);
    }, 500);
}

// Update the existing search function to use pagination
if (typeof searchInventory === 'function') {
    // Store the original function
    const originalSearchInventory = searchInventory;
    
    // Override with pagination version
    window.searchInventory = function(searchTerm) {
        if (searchTerm && searchTerm.trim()) {
            // Use pagination search for non-empty searches
            handleSearchWithPagination(searchTerm);
        } else {
            // Clear search and reset to first page
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.delete('search');
            currentUrl.searchParams.delete('page');
            window.location.href = currentUrl.toString();
        }
    };
}

// Function to go to specific page
function goToPage(page) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('page', page);
    window.location.href = currentUrl.toString();
}

// Add click handlers to pagination links for better UX
document.addEventListener('DOMContentLoaded', function() {
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
</script>
@endif
<!-- END: Dynamic Pagination -->