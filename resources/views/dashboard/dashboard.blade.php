@extends('layout.app')

@section('content')

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 2xl:col-span-12">
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Dashboard Overview
                    </h2>
                    <a href="{{ route('dashboard') }}" class="ml-auto flex items-center text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw w-4 h-4 mr-3">
                            <path d="M3 2v6h6"></path>
                            <path d="M21 12A9 9 0 006 5.3L3 8"></path>
                            <path d="M21 22v-6h-6"></path>
                            <path d="M3 12a9 9 0 0015 6.7l3-2.7"></path>
                        </svg>
                        Reload Data
                    </a>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <!-- Total Customers -->
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users report-box__icon text-primary">
                                        <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 00-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 010 7.75"></path>
                                    </svg>
                                    <div class="ml-auto">
                                        <div class="report-box__indicator {{ $dashboardData['customerGrowth'] >= 0 ? 'bg-success' : 'bg-danger' }} tooltip cursor-pointer">
                                            {{ $dashboardData['customerGrowth'] >= 0 ? '+' : '' }}{{ $dashboardData['customerGrowth'] }}%
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-{{ $dashboardData['customerGrowth'] >= 0 ? 'up' : 'down' }} w-4 h-4 ml-0.5">
                                                <polyline points="{{ $dashboardData['customerGrowth'] >= 0 ? '18 15 12 9 6 15' : '6 9 12 15 18 9' }}"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($dashboardData['totalCustomers']) }}</div>
                                <div class="text-base text-slate-500 mt-1">Total Customers</div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Customers -->
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-check report-box__icon text-success">
                                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                        <path d="M10 19l2 2 4-4"></path>
                                    </svg>
                                    <div class="ml-auto">
                                        <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                            {{ $dashboardData['totalCustomers'] > 0 ? round(($dashboardData['activeCustomers'] / $dashboardData['totalCustomers']) * 100) : 0 }}%
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up w-4 h-4 ml-0.5">
                                                <polyline points="18 15 12 9 6 15"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($dashboardData['activeCustomers']) }}</div>
                                <div class="text-base text-slate-500 mt-1">Active Customers</div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Items -->
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package report-box__icon text-warning">
                                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                    <div class="ml-auto">
                                        @if($dashboardData['lowStockItems'] > 0)
                                        <div class="report-box__indicator bg-warning tooltip cursor-pointer" title="Low Stock Items">
                                            {{ $dashboardData['lowStockItems'] }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle w-4 h-4 ml-0.5">
                                                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($dashboardData['totalInventoryItems']) }}</div>
                                <div class="text-base text-slate-500 mt-1">Inventory Items</div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Alert -->
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle report-box__icon {{ $dashboardData['criticalStockItems'] > 0 ? 'text-danger' : 'text-slate-400' }}">
                                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                    <div class="ml-auto">
                                        @if($dashboardData['criticalStockItems'] > 0)
                                        <div class="report-box__indicator bg-danger tooltip cursor-pointer" title="Critical Stock">
                                            {{ $dashboardData['criticalStockItems'] }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle w-4 h-4 ml-0.5">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">{{ number_format($dashboardData['lowStockItems']) }}</div>
                                <div class="text-base text-slate-500 mt-1">Low Stock Items</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($dashboardData['totalCustomers'] == 0)
                <div class="col-span-12 mt-8">
                    <div class="intro-y box p-8 text-center">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-16 h-16 text-slate-400 mb-4">
                                <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 00-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 010 7.75"></path>
                            </svg>
                            <h3 class="text-xl font-medium text-slate-600 mb-2">No Customers Found</h3>
                            <p class="text-slate-500 mb-4">Get started by adding your first customer.</p>
                            <a href="{{ route('customer.add') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4 mr-2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Add First Customer
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- END: General Report -->

            <!-- BEGIN: Recent Customers -->
            <div class="col-span-12 mt-6">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Recent Customers
                    </h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        <a href="{{ route('customer.add') }}" class="btn btn-primary">
                            View All Customers
                        </a>
                    </div>
                </div>
                <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                    <table class="table table-report sm:mt-2">
                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">CUSTOMER NAME</th>
                                <th class="whitespace-nowrap">ADDRESS</th>
                                <th class="text-center whitespace-nowrap">STATUS</th>
                                <th class="text-center whitespace-nowrap">CREATED</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dashboardData['recentCustomers'] as $customer)
                            <tr class="intro-x">
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-primary/10 flex items-center justify-center">
                                            <span class="text-primary font-medium text-lg">{{ strtoupper(substr($customer->customer_name, 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium whitespace-nowrap">{{ $customer->customer_name }}</div>
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">ID: {{ $customer->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-slate-500 text-sm">{{ Str::limit($customer->address, 50) }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center">
                                        @if($customer->status == 'active')
                                        <div class="flex items-center text-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-4 h-4 mr-2">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                            </svg> Active
                                        </div>
                                        @else
                                        <div class="flex items-center text-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle w-4 h-4 mr-2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                                <line x1="9" y1="9" x2="15" y2="15"></line>
                                            </svg> Inactive
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="text-slate-500 text-sm">{{ $customer->created_at->format('M d, Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox w-12 h-12 text-slate-400 mb-3">
                                            <polyline points="22,12 18,12 14,15 10,15 6,12 2,12"></polyline>
                                            <path d="M5.45 5.11L2 12v6a2 2 0 002 2h16a2 2 0 002-2v-6l-3.45-6.89A2 2 0 0016.76 4H7.24a2 2 0 00-1.79 1.11z"></path>
                                        </svg>
                                        <span class="text-lg font-medium">No customers found</span>
                                        <span class="text-sm">Start by adding your first customer</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END: Recent Customers -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/dashboard/dashboard.js') }}"></script>
@endsection