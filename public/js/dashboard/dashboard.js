// Dashboard JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded successfully!');
    
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
        
        // Example: Initialize charts or other dashboard widgets
        // You can add more dashboard functionality here
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
    
    // Function to show client details (for the View button)
    window.showClientDetails = function(clientId) {
        // You can implement a modal or redirect to client details page
        console.log('Showing details for client ID:', clientId);
        
        // For now, just redirect to the ledger show page
        // You can enhance this with a modal later
        window.location.href = `/ledger/${clientId}`;
    };
    
    // Auto-refresh dashboard every 5 minutes (300000 ms) - optional
    // setInterval(reloadDashboardData, 300000);
});
