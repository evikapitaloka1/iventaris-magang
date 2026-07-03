@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-people"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Management</p>
                    <h1 class="h3 mb-1">Users</h1>
                    <p class="text-muted mb-0">
                        Review accounts, roles, account status, and team ownership.
                    </p>
                </div>
            </div>

            <div class="heading-actions">
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i>
                    Export
                </a>

                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus"></i>
                    Add User
                </a>
            </div>
        </div>

        {{-- CARD SUMMARY --}}
        <section class="row g-3 mt-1">

            <div class="col-md-3">
                <div class="metric-card metric-primary">
                    <div class="metric-top">
                        <span>Total Users</span>
                        <i class="bi bi-people"></i>
                    </div>

                    <div class="metric-value">
                        {{ $users->count() }}
                    </div>

                    <div class="metric-meta">
                        Registered Users
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card metric-success">
                    <div class="metric-top">
                        <span>Admins</span>
                        <i class="bi bi-person-check"></i>
                    </div>

                    <div class="metric-value">
                        {{ $users->filter(fn($u) => $u->role && $u->role->name === 'Admin')->count() }}
                    </div>

                    <div class="metric-meta">
                        Administrator
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card metric-warning">
                    <div class="metric-top">
                        <span>Unassigned</span>
                        <i class="bi bi-person"></i>
                    </div>

                    <div class="metric-value">
                        {{ $users->whereNull('role_id')->count() }}
                    </div>

                    <div class="metric-meta">
                        No Role Assigned
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card metric-danger">
                    <div class="metric-top">
                        <span>Active</span>
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div class="metric-value">
                        {{ $users->count() }}
                    </div>

                    <div class="metric-meta">
                        Active Account
                    </div>
                </div>
            </div>

        </section>

        {{-- TABLE --}}
        <section class="panel mt-3">

            <div class="panel-header">

                <div>
                    <h2 class="h5 mb-1">
                        User List
                    </h2>

                    <p class="text-muted mb-0">
                        Manage registered users.
                    </p>
                </div>

                <a href="{{ route('users.create') }}"
                   class="btn btn-primary btn-sm">

                    <i class="bi bi-person-plus"></i>
                    Add User

                </a>

            </div>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="170">Action</th>
                    </tr>

                    </thead>

                    <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                @if($user->role)
                                    <span class="badge text-bg-primary">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge text-bg-secondary">No Role</span>
                                @endif
                            </td>

                            <td>

                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center">
                                No Data
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </div>
</main>

@endsection