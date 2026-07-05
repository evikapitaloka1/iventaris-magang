<!-- SIDEBAR -->
<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
  <div class="sidebar-header">
    <a class="brand-mark d-flex align-items-center gap-2" href="{{ route('dashboard') }}" aria-label="adminHMD dashboard">
      
      <!-- Bagian Avatar Sidebar -->
      @php
          $user = auth()->user();
          if ($user->avatar) {
              $avatarSidebar = asset('storage/avatars/' . $user->avatar);
          } else {
              $avatarSidebar = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=ffffff&size=45&bold=true';
          }
      @endphp
      
      <span class="brand-icon">
        <img src="{{ $avatarSidebar }}" 
             alt="{{ $user->name }}" 
             class="rounded-circle object-fit-cover shadow-sm border border-light" 
             style="width: 45px; height: 45px;"
             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=ffffff&size=45&bold=true';">
      </span>
      
      <!-- Bagian Nama & Role -->
      <span class="brand-copy text-start">
        <span class="brand-title d-block fw-bold" style="line-height: 1.2;">{{ $user->name }}</span>
        <span class="brand-subtitle" style="font-size: 0.8rem; opacity: 0.8;">{{ $user->role?->name ?? 'No Role Assigned' }}</span>
      </span>
    </a>
  </div>

  <nav class="sidebar-nav">
    <!-- MENU UMUM: Bisa diakses semua role -->
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
      <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
      <span class="nav-text">Dashboard</span>
    </a>

    <!-- MENU STAFF & ADMIN -->
    @if(in_array(auth()->user()->role?->name, ['Admin', 'Staff']))
    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
      <span class="nav-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
      <span class="nav-text">Kelola Barang</span>
    </a>
    <a class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}" href="{{ route('borrowings.index') }}">
      <span class="nav-icon"><i class="bi bi-arrow-left-right" aria-hidden="true"></i></span>
      <span class="nav-text">Peminjaman</span>
    </a>
    @endif

    <!-- MENU MANAGER & ADMIN -->
    @if(in_array(auth()->user()->role?->name, ['Admin', 'Manager']))
    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
      <span class="nav-icon"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i></span>
      <span class="nav-text">Laporan</span>
    </a>
    @endif

    <!-- MENU ADMIN ONLY (Manajemen User) -->
    @if(auth()->user()->role?->name === 'Admin')
    <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
      <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
      <span class="nav-text">Users</span>
    </a>
    <a class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">
      <span class="nav-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
      <span class="nav-text">Add User</span>
    </a>
    @endif

    <!-- MENU UMUM: Profil -->
    <a class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}" href="{{ route('profile.index') }}">
      <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
      <span class="nav-text">Profile</span>
    </a>
  </nav>
</aside>

<!-- AREA KONTEN UTAMA -->
<main class="admin-main">

  <!-- NAVBAR -->
<nav class="navbar admin-navbar navbar-expand">
    <div class="container-fluid px-3 px-lg-4">
      <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
        <input class="form-control search-input" type="search" placeholder="Search users, orders, reports" aria-label="Search">
      </form>

      <div class="navbar-actions ms-auto">
        <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
          <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
        </button>
        
        <!-- DROPDOWN NOTIFIKASI -->
        <div class="dropdown">
          <button class="icon-button position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
            <i class="bi bi-bell" aria-hidden="true"></i>
            
            {{-- Titik merah indikator (hanya muncul jika ada notifikasi unread) --}}
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            @endif
          </button>
          
          <div class="dropdown-menu dropdown-menu-end notification-menu shadow-sm" style="min-width: 250px;">
            <!-- Header Notifikasi -->
            <div class="dropdown-header fw-bold text-body d-flex justify-content-between align-items-center">
                <span>Notifications</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </div>

            <!-- Looping Notifikasi (Maksimal 5 terbaru) -->
            @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                <a class="dropdown-item border-bottom py-2 {{ $notification->unread() ? 'bg-light' : '' }}" 
                   href="{{ $notification->data['url'] ?? '#' }}">
                    <span class="notification-title d-block text-truncate fw-medium">
                        {{ $notification->data['title'] ?? $notification->data['message'] ?? 'Notifikasi Baru' }}
                    </span>
                    <span class="notification-time text-muted small">
                        <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                    </span>
                </a>
            @empty
                <!-- Tampilan Jika Tidak Ada Notifikasi -->
                <div class="dropdown-item text-center text-muted py-4">
                    <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                    <small>Tidak ada notifikasi baru.</small>
                </div>
            @endforelse

            <!-- Footer Notifikasi -->
            @if(auth()->user()->notifications->count() > 0)
                <a href="#" class="dropdown-item text-center text-primary fw-bold small py-2">
                    Lihat Semua Notifikasi
                </a>
            @endif
          </div>
        </div>

        <!-- DROPDOWN PENGATURAN AKUN -->
        <div class="dropdown">
          <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account settings" title="Account settings">
            <i class="bi bi-gear" aria-hidden="true"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
            <li><a class="dropdown-item" href="#">Account settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Sign out</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- KONTEN HALAMAN -->
  <div class="admin-content-wrapper p-4">
    @include('profile.partials.flash-messages')
    @yield('content')
  </div>

</main>