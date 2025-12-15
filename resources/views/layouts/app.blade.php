<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'ASSA Organization') }}</title>

  {{-- Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  {{-- Bootstrap (opsional, untuk komponen umum) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  {{-- Tailwind (CDN) untuk utilitas di view Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { colors: { 'assa-blue': '#1E3A8A' } } } }
  </script>

  {{-- Font Awesome (karena beberapa view pakai fas fa-*) --}}
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkfZ5sPT8J9GZx1nVHm0I1WQpwf0i0C2B1h0Pp+Z8P3wLbc01xB1r6jJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- ============= GLOBAL CSS ONLY ============= --}}
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f9fafc; overflow-x: hidden; }

    /* Sidebar shell (universal untuk semua role) */
    .sidebar {
      position: fixed; top: 0; left: 0; height: 100vh; width: 250px;
      background: linear-gradient(180deg, #1e3a8a, #1e40af, #2563eb);
      color: white; padding: 1.5rem 1rem; display: flex; flex-direction: column;
      transition: all .3s ease; z-index: 100;
    }
    .sidebar.collapsed { width: 80px; }
    .sidebar .brand { font-weight: 700; font-size: 1.35rem; text-align: center; margin-bottom: 2rem; letter-spacing: .5px; }
    .sidebar .brand span { color: #93c5fd; }
    .sidebar a { color: #e0e7ff; text-decoration: none; padding: 12px 18px; border-radius: 12px;
                 display: flex; align-items: center; gap: 12px; margin-bottom: 6px; font-weight: 500;
                 transition: all .25s ease; }
    .sidebar a i { font-size: 1.2rem; transition: all .25s ease; }
    .sidebar a:hover { background: rgba(255,255,255,.15); transform: translateX(4px); color: #fff; }
    .sidebar a.active { background: linear-gradient(90deg, #3b82f6, #06b6d4); color: #fff;
                        box-shadow: 0 2px 8px rgba(59,130,246,.3); }
    .sidebar a.active i { color: #fff; transform: scale(1.1); }
    .toggle-btn {
      position: absolute; top: 1.2rem; right: -15px; background: #fff; color: #1e3a8a;
      border-radius: 50%; padding: 4px 7px; box-shadow: 0 2px 8px rgba(0,0,0,.15);
      cursor: pointer; transition: all .3s ease;
    }
    .toggle-btn:hover { background: #3b82f6; color: white; }

    /* main-content menyesuaikan dengan sidebar */
    .main-content { margin-left: 250px; padding: 25px; transition: margin-left .3s ease; }
    .sidebar.collapsed ~ .main-content { margin-left: 80px; }

    @media (max-width: 768px) {
      .sidebar { width: 200px; left: -200px; }
      .sidebar.show { left: 0; }
      .main-content { margin-left: 0; }
      .toggle-btn { right: -30px; top: 1rem; }
    }

    /* Utilitas global yang dipakai di dashboard Tailwind */
    .gradient-bg{
      background: linear-gradient(135deg, #2563eb, #0ea5e9, #22d3ee);
      background-size: 300% 300%; animation: gradientShift 8s ease infinite;
    }
    @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    .card-hover{ transition: all .3s ease }
    .card-hover:hover{ transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.08) }
  </style>

  @stack('styles')
</head>
<body>
  <div id="app" class="d-flex">
    {{-- Universal Sidebar (otomatis sesuai role user) --}}
    @auth
      @include('layouts.sidebar')
    @endauth

    {{-- Konten utama --}}
    <main class="main-content w-100">
      @yield('content')

      {{-- Global alerts --}}
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
             style="z-index:1050;min-width:280px;">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
             style="z-index:1050;min-width:280px;">
          <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
    </main>
  </div>

  {{-- JS untuk toggle sidebar mobile --}}
  <script>
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('#toggleSidebar');
      if (!btn) return;
      const sidebar = document.getElementById('sidebar');
      if (sidebar) sidebar.classList.toggle('show');
    });
  </script>

  @stack('scripts')
</body>
</html>
