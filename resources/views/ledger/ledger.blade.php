@extends('layout.app')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
Client LEDGER
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <button class="btn btn-secondary shadow-md mr-2" onclick="testModal()">Test Modal</button>
        <div class="hidden md:block mx-auto text-slate-500">
            @if(isset($clients) && $clients instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} entries
            @else
                Showing {{ $clients->count() }} entries
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
                    <th class="whitespace-nowrap">CLIENT NAME</th>
                    <th class="whitespace-nowrap">ADDRESS</th>
                    <th class="text-center whitespace-nowrap">ITEMS COUNT</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr class="intro-x">
                    <td>
                        <a href="" class="font-medium whitespace-nowrap">{{ $client->client_name }}</a>
                    </td>
                    <td>
                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $client->address }}</div>
                    </td>
                    <td class="text-center">{{ $client->items->count() }}</td>
                    <td class="w-40">
                        <div class="flex items-center justify-center {{ $client->status === 'active' ? 'text-success' : 'text-danger' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-square" data-lucide="check-square" class="lucide lucide-check-square w-4 h-4 mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path></svg> 
                            {{ ucfirst($client->status) }}
                        </div>
                    </td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="javascript:;" data-tw-toggle="modal" data-tw-target="#view-client-modal" onclick="viewClient({{ $client->id }})"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="eye" class="lucide lucide-eye w-4 h-4 mr-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> View 
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-500">No clients found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- END: Data List -->
    
    @include('_partials.dynamic-pagination')
</div>

<!-- BEGIN: View Client Modal -->
<div id="view-client-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Client Details</h2>
                <button class="btn-close" data-tw-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" class="lucide lucide-x w-4 h-4" data-lucide="x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <!-- Header Section -->
                <div class="border-b border-slate-200 pb-4 mb-6">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <div class="mb-3">
                                <label class="text-sm font-semibold text-slate-600">Client Name:</label>
                                <p class="text-lg font-medium text-slate-800" id="view-client-name"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-600">Address:</label>
                                <p class="text-base text-slate-800" id="view-client-address"></p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <label class="text-sm font-semibold text-slate-600">Status:</label>
                                <p class="text-base text-slate-800" id="view-client-status"></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-600">Date Created:</label>
                                <p class="text-base text-slate-800" id="view-client-created-date"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Items Section -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Inventory Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border border-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="border border-slate-200 px-4 py-3 text-left font-semibold text-slate-700">Description</th>
                                    <th class="border border-slate-200 px-4 py-3 text-center font-semibold text-slate-700 w-24">Quantity</th>
                                    <th class="border border-slate-200 px-4 py-3 text-left font-semibold text-slate-700">Rec Meter</th>
                                    <th class="border border-slate-200 px-4 py-3 text-center font-semibold text-slate-700 w-24">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="view-items-container" class="bg-white">
                                <!-- Items will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-tw-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END: View Client Modal -->

@endsection

@section('scripts')
<script src="{{ asset('js/ledger/ledger.js') }}"></script>
@endsection