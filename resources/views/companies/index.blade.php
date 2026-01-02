@extends('layouts.app')

@section('title', 'Company Settings - Quotation App')

@section('styles')
<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
    th { background: #f9fafb; font-weight: 600; color: #374151; }
    tr:hover { background: #f9fafb; }
    td { color: #6b7280; }
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
    .empty-state h3 { font-size: 16px; margin-bottom: 8px; color: #6b7280; }
    .status { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; background: #d1fae5; color: #065f46; }
    .actions { display: flex; gap: 8px; }
    .company-logo { max-width: 50px; max-height: 50px; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Company Settings</h1>
        <div>
            <a href="{{ route('companies.create') }}" class="btn btn-primary">+ Add Company</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($companies->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Logo</th>
                        <th>Company Name</th>
                        <th>City, State</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($company->logo_path)
                                    <img src="{{ asset($company->logo_path) }}" alt="{{ $company->company_name }}" class="company-logo">
                                @else
                                    <span style="color: #9ca3af;">No Logo</span>
                                @endif
                            </td>
                            <td style="color: #374151; font-weight: 500;">{{ $company->company_name }}</td>
                            <td>{{ $company->city }}, {{ $company->state }}</td>
                            <td>{{ $company->phone_number }}</td>
                            <td>{{ $company->email }}</td>
                            <td>
                                @if($company->is_active)
                                    <span class="status">Active</span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('companies.destroy', $company) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this company?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <h3>No companies added yet</h3>
                <p>Click the "Add Company" button to add your first company.</p>
            </div>
        @endif
    </div>
</div>
@endsection
