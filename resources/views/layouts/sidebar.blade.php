{{-- Universal Sidebar for Multi-Role --}}
<div class="sidebar" id="sidebar">
  <div class="brand">ASSA <span>Organization</span></div>

  <div class="toggle-btn d-md-none" id="toggleSidebar">
    <i class="bi bi-list"></i>
  </div>

  @auth

    {{-- ================= ADMIN MENU ================= --}}
    @if(auth()->user()->role === 'admin')
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
      </a>

      <a href="{{ route('admin.activities.index') }}" class="{{ request()->is('admin/activities*') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i> <span>Kegiatan</span>
      </a>

      <a href="{{ route('admin.scholarship.index') }}" class="{{ request()->is('admin/scholarship*') ? 'active' : '' }}">
        <i class="bi bi-mortarboard"></i> <span>Beasiswa</span>
      </a>

      <a href="{{ route('admin.partnerships.index') }}" class="{{ request()->is('admin/partnerships*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> <span>Kerja Sama</span>
      </a>

      <a href="{{ route('admin.achievements.index') }}" class="{{ request()->is('admin/achievements*') ? 'active' : '' }}">
        <i class="bi bi-trophy"></i> <span>Poin</span>
      </a>

      <a href="{{ route('profile.edit') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
        <i class="bi bi-person-gear"></i> <span>Profil</span>
      </a>
    @endif


    {{-- ================= ANGGOTA MENU (SAMAKAN LABEL DENGAN ADMIN) ================= --}}
    @if(auth()->user()->role === 'anggota')
      <a href="{{ route('anggota.dashboard') }}" class="{{ request()->routeIs('anggota.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
      </a>

      <a href="{{ route('anggota.activities.index') }}" class="{{ request()->routeIs('anggota.activities.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> <span>Kegiatan</span>
      </a>

      <a href="{{ route('anggota.scholarship.index') }}" class="{{ request()->routeIs('anggota.scholarship.*') ? 'active' : '' }}">
        <i class="bi bi-mortarboard"></i> <span>Beasiswa</span>
      </a>

      <a href="{{ route('anggota.partnerships.index') }}" class="{{ request()->routeIs('anggota.partnerships.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> <span>Kerja Sama</span>
      </a>

      <a href="{{ route('anggota.achievements.index') }}" class="{{ request()->routeIs('anggota.achievements.*') ? 'active' : '' }}">
        <i class="bi bi-trophy"></i> <span>Poin</span>
      </a>

      <a href="{{ route('profile.edit') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
        <i class="bi bi-person-gear"></i> <span>Profil</span>
      </a>
    @endif


    {{-- ================= LOGOUT (SEMUA ROLE) ================= --}}
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form-universal').submit();">
      <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
    </a>

    <form id="logout-form-universal" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>

  @endauth
</div>
