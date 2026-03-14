@extends('layouts.app')

@section('title', 'Company Settings - Quotation App')

@section('styles')
    <style>
        .companies-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .companies-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .companies-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .companies-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 24px;
        }

        .companies-table {
            width: 100%;
            border-collapse: collapse;
        }

        .companies-table thead {
            background: #f9fafb;
            color: #374151;
        }

        .companies-table th {
            padding: 16px 12px;
            text-align: left;
            font-weight: 700;
            color: #374151;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: none;
        }

        .companies-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .companies-table tbody tr:last-child {
            border-bottom: none;
        }

        .companies-table tbody tr:hover {
            background: #f9fafb;
        }

        .companies-table td {
            padding: 14px 12px;
            color: #6b7280;
            font-size: 14px;
        }

        .companies-table td:first-child {
            color: #1f2937;
            font-weight: 600;
            width: 50px;
        }

        .company-name {
            color: #1f2937;
            font-weight: 600;
        }

        .company-logo {
            max-width: 50px;
            max-height: 50px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .status-inactive {
            background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%);
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-sm {
            padding: 7px 14px;
            font-size: 12px;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: inline-block;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #6b7280;
            font-weight: 600;
        }

        .empty-state p {
            font-size: 14px;
            margin: 0;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .no-logo {
            color: #9ca3af;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .companies-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .companies-table {
                font-size: 12px;
            }

            .companies-table th,
            .companies-table td {
                padding: 10px 8px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 6px;
            }

            .btn-sm {
                width: 100%;
                text-align: center;
                padding: 6px 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="companies-container">
        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="companies-header">
            <h1>Company Settings</h1>
            <a href="{{ route('companies.create') }}" class="btn btn-primary">+ Add Company</a>
        </div>

        <div class="companies-card">
            @if($companies->count() > 0)
                <div class="table-responsive">
                    <table class="companies-table">
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
                                            @php
                                                $logoPath = $company->logo_path;
                                                if (strpos($logoPath, 'storage/') === 0) {
                                                    $logoPath = substr($logoPath, 8);
                                                }
                                            @endphp
                                            <img src="/get-image/{{ $logoPath }}" alt="{{ $company->company_name }}"
                                                class="company-logo">
                                        @else
                                            <span class="no-logo">No Logo</span>
                                        @endif
                                    </td>
                                    <td class="company-name">{{ $company->company_name }}</td>
                                    <td>{{ $company->city }}, {{ $company->state }}</td>
                                    <td>{{ $company->phone_number }}</td>
                                    <td>{{ $company->email }}</td>
                                    <td>
                                        @if($company->is_active)
                                            <span class="status-badge status-active">Active</span>
                                        @else
                                            <span class="status-badge status-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('companies.edit', $company) }}" class="btn-sm btn-edit">Edit</a>
                                            <form method="POST" action="{{ route('companies.destroy', $company) }}"
                                                style="display: inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this company?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <h3>No companies added yet</h3>
                    <p>Click the "Add Company" button to add your first company.</p>
                </div>
            @endif
        </div>
    </div>
@endsection