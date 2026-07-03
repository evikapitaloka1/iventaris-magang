@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <!-- 1. Page Heading (Sudah Digabung & Dirapikan) -->
        <div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div class="page-heading-copy d-flex align-items-center gap-3">
                <span class="page-icon">
                    <i class="bi bi-speedometer2 fs-3" aria-hidden="true"></i>
                </span>
                <div>
                    <p class="eyebrow mb-1 text-muted">Overview</p>
                    <h1 class="h3 mb-1">Dashboard</h1>
                    <p class="text-muted mb-0">
                        Welcome back, <strong>{{ auth()->user()->name }}</strong> 👋 | Monitor performance, sales, users, and support.
                    </p>
                </div>
            </div>
            
            <div class="heading-actions">
                <button class="btn btn-outline-secondary btn-sm" type="button">
                    <i class="bi bi-download" aria-hidden="true"></i> Export
                </button>
                <button class="btn btn-primary btn-sm" type="button">
                    <i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Create Report
                </button>
            </div>
        </div>

        <!-- 2. Dynamic User Information Panel -->
        <div class="panel mb-4">
            <div class="panel-header">
                <h2 class="h5 mb-0"><i class="bi bi-person-badge me-2"></i>User Information</h2>
            </div>
            <div class="panel-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="180" class="text-muted">Name</th>
                        <td class="fw-medium">{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Role</th>
                        <td>
                            <span class="badge text-bg-primary">{{ auth()->user()->role ?? 'No Role Assigned' }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- 3. Dashboard Metrics -->
        <section class="row g-3 mb-4" aria-label="Dashboard metrics">
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-primary">
                    <div class="metric-top">
                        <span class="metric-label">Revenue</span>
                        <span class="metric-icon"><i class="bi bi-currency-dollar" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">$48,240</div>
                    <div class="metric-meta">
                        <span class="text-success">+12.5%</span>
                        <span>from last month</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-success">
                    <div class="metric-top">
                        <span class="metric-label">Orders</span>
                        <span class="metric-icon"><i class="bi bi-bag-check" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">1,284</div>
                    <div class="metric-meta">
                        <span class="text-success">+8.2%</span>
                        <span>new orders</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-warning">
                    <div class="metric-top">
                        <span class="metric-label">Customers</span>
                        <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">8,742</div>
                    <div class="metric-meta">
                        <span class="text-success">+5.1%</span>
                        <span>active users</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-danger">
                    <div class="metric-top">
                        <span class="metric-label">Tickets</span>
                        <span class="metric-icon"><i class="bi bi-life-preserver" aria-hidden="true"></i></span>
                    </div>
                    <div class="metric-value">36</div>
                    <div class="metric-meta">
                        <span class="text-danger">3 urgent</span>
                        <span>need review</span>
                    </div>
                </article>
            </div>
        </section>

        <!-- 4. Charts & Team Activity -->
        <section class="row g-3 mb-4">
            <div class="col-12 col-xl-8">
                <div class="panel h-100">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Sales Performance</span>
                            </h2>
                            <p class="text-muted mb-0">Monthly revenue compared with operational targets.</p>
                        </div>
                        <a class="btn btn-light btn-sm" href="charts.html">View Details</a>
                    </div>
                    <div class="chart-bars p-3" aria-label="Sales performance chart">
                        <div class="chart-column bar-42"><span></span><small>Jan</small></div>
                        <div class="chart-column bar-58"><span></span><small>Feb</small></div>
                        <div class="chart-column bar-51"><span></span><small>Mar</small></div>
                        <div class="chart-column bar-72"><span></span><small>Apr</small></div>
                        <div class="chart-column bar-66"><span></span><small>May</small></div>
                        <div class="chart-column bar-83"><span></span><small>Jun</small></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="panel h-100">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-activity" aria-hidden="true"></i><span>Team Activity</span>
                            </h2>
                            <p class="text-muted mb-0">Recent operational updates.</p>
                        </div>
                    </div>
                    <div class="activity-list p-3">
                        <div class="activity-item mb-3">
                            <span class="activity-dot bg-primary"></span>
                            <div>
                                <p class="mb-1 fw-semibold">New campaign launched</p>
                                <p class="text-muted small mb-0">Marketing team published the May offer.</p>
                            </div>
                        </div>
                        <div class="activity-item mb-3">
                            <span class="activity-dot bg-success"></span>
                            <div>
                                <p class="mb-1 fw-semibold">Payment batch cleared</p>
                                <p class="text-muted small mb-0">246 invoices were processed successfully.</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <span class="activity-dot bg-warning"></span>
                            <div>
                                <p class="mb-1 fw-semibold">Support queue rising</p>
                                <p class="text-muted small mb-0">Average first response time is 18 minutes.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Recent Users Table -->
        <section class="panel mb-4">
            <div class="panel-header">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-people" aria-hidden="true"></i><span>Recent Users</span>
                    </h2>
                    <p class="text-muted mb-0">Latest account activity across the workspace.</p>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="users.html">Manage Users</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col">Role</th>
                            <th scope="col">Team</th>
                            <th scope="col">Status</th>
                            <th scope="col">Joined</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img class="avatar-img avatar-sm rounded-circle" src="../assets/images/avatar/avatar-1.jpg" alt="Sarah Ahmed" width="32" height="32">
                                    <div>
                                        <p class="fw-semibold mb-0">Sarah Ahmed</p>
                                        <p class="text-muted small mb-0">sarah@example.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>Admin</td>
                            <td>Operations</td>
                            <td><span class="badge text-bg-success">Active</span></td>
                            <td>Jan 12, 2026</td>
                            <td class="text-end"><a class="btn btn-light btn-sm" href="user-details.html">View</a></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img class="avatar-img avatar-sm rounded-circle" src="../assets/images/avatar/avatar-2.jpg" alt="Rafi Khan" width="32" height="32">
                                    <div>
                                        <p class="fw-semibold mb-0">Rafi Khan</p>
                                        <p class="text-muted small mb-0">rafi@example.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>Manager</td>
                            <td>Sales</td>
                            <td><span class="badge text-bg-success">Active</span></td>
                            <td>Feb 03, 2026</td>
                            <td class="text-end"><a class="btn btn-light btn-sm" href="user-details.html">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</main>
@endsection