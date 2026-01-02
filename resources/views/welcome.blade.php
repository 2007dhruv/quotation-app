@extends('layouts.app')

@section('title', 'Dashboard - Quotation App')

@section('styles')
<style>
    .dashboard-container { max-width: 800px; margin: 0 auto; }
    .welcome-card { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px; 
        padding: 40px; 
        text-align: center; 
        color: #fff;
        margin-bottom: 30px;
    }
    .welcome-card h1 { font-size: 28px; margin-bottom: 8px; }
    .welcome-card p { opacity: 0.9; font-size: 16px; }
    
    .quick-actions { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
        gap: 20px; 
    }
    .action-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .action-card .icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .action-card .icon svg {
        width: 28px;
        height: 28px;
        color: #fff;
    }
    .action-card.customers .icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .action-card.products .icon { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .action-card.quotations .icon { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    
    .action-card h3 { font-size: 16px; color: #333; margin-bottom: 16px; }
    .action-links { display: flex; flex-direction: column; gap: 8px; }
    .action-links a {
        display: block;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    .action-links .btn-add {
        background: #2563eb;
        color: #fff;
    }
    .action-links .btn-add:hover {
        background: #1d4ed8;
    }
    .action-links .btn-view {
        background: #f3f4f6;
        color: #374151;
    }
    .action-links .btn-view:hover {
        background: #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- <div class="welcome-card">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Manage your customers, products and quotations</p>
    </div> -->

    <div class="quick-actions">
        <div class="action-card customers">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3>Customers</h3>
            <div class="action-links">
                <a href="{{ route('customers.create') }}" class="btn-add">+ Add Customer</a>
                <a href="{{ route('customers.index') }}" class="btn-view">View All Customers</a>
            </div>
        </div>

        <div class="action-card products">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3>Products</h3>
            <div class="action-links">
                <a href="{{ route('products.create') }}" class="btn-add">+ Add Product</a>
                <a href="{{ route('products.index') }}" class="btn-view">View All Products</a>
            </div>
        </div>

        <div class="action-card quotations">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3>Quotations</h3>
            <div class="action-links">
                <a href="{{ route('quotations.create') }}" class="btn-add">+ Create Quotation</a>
                <a href="{{ route('quotations.index') }}" class="btn-view">View All Quotations</a>
            </div>
        </div>
    </div>
</div>
@endsection
