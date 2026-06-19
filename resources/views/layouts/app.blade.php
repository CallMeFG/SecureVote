<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVote - Pemilihan Gubernur ITSA PCR</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #0B1120; /* Deep navy */
            --accent-glow: #38bdf8; /* Cyan */
            --accent-secondary: #818cf8; /* Purple */
            --glass-bg: rgba(30, 41, 59, 0.6);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--primary-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(129, 140, 248, 0.15) 0px, transparent 50%);
            background-attachment: fixed;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        /* Premium Glassmorphism Card */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
            color: #f8fafc;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(56, 189, 248, 0.15);
        }

        /* Navbar customization */
        .navbar-custom {
            background: rgba(11, 17, 32, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
        }

        .navbar-custom .nav-link {
            color: #cbd5e1;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: var(--accent-glow);
            text-shadow: 0 0 10px rgba(56, 189, 248, 0.5);
            transform: translateY(-1px);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(to right, var(--accent-glow), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-glow) 0%, var(--accent-secondary) 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 24px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(56, 189, 248, 0.5);
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        }

        .btn-outline-light {
            border-color: var(--glass-border);
            color: #e2e8f0;
            border-radius: 10px;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .btn-logout {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.8) 0%, rgba(185, 28, 28, 1) 100%);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 8px 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.6);
            background: linear-gradient(135deg, rgba(239, 68, 68, 1) 0%, rgba(185, 28, 28, 1) 100%);
            color: white;
        }

        /* Inputs */
        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 10px;
            padding: 12px 16px;
        }

        .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--accent-glow);
            box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25);
            color: white;
        }
        
        .form-control::placeholder {
            color: #64748b;
        }

        /* Custom Text Colors */
        .text-gradient {
            background: linear-gradient(to right, var(--accent-glow), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .text-muted {
            color: #94a3b8 !important;
        }

        /* Tables for Dashboards */
        .table {
            color: #e2e8f0;
        }
        .table-dark {
            background-color: rgba(15, 23, 42, 0.8);
            border-color: var(--glass-border);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }
        td, th {
            border-color: var(--glass-border);
            background-color: transparent !important;
        }
        
        /* Stats Cards */
        .stat-card {
            border-radius: 15px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid var(--glass-border);
        }
        
        hr {
            border-color: var(--glass-border);
            opacity: 1;
        }
    </style>
</head>
<body class="bg-dark text-white font-sans antialiased d-flex flex-column min-vh-100" style="background: radial-gradient(circle at top, #1e293b 0%, var(--primary-bg) 70%);">

<nav class="navbar navbar-expand-lg navbar-custom shadow-sm mb-5 py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('public.beranda') }}">
            <i class="bi bi-shield-lock-fill me-2"></i>SecureVote
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list text-white fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ps-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.beranda') ? 'active' : '' }}" href="{{ route('public.beranda') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.kandidat') ? 'active' : '' }}" href="{{ route('public.kandidat') }}">Kandidat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.jadwal') ? 'active' : '' }}" href="{{ route('public.jadwal') }}">Jadwal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.hasil') ? 'active' : '' }}" href="{{ route('public.hasil') }}">Hasil Rekapitulasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.riwayat*') ? 'active' : '' }}" href="{{ route('public.riwayat') }}">Riwayat</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="btn btn-primary px-4 ms-lg-3" href="{{ route('login') }}">Masuk</a>
                    </li>
                @else
                    <li class="nav-item">
                        @if(auth()->user()->role->name === 'admin')
                            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                        @elseif(auth()->user()->role->name === 'panitia')
                            <a class="nav-link" href="{{ route('panitia.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                        @elseif(auth()->user()->role->name === 'pemilih')
                            <a class="nav-link" href="{{ route('pemilih.dashboard') }}"><i class="bi bi-person-circle me-1"></i> Panel Pemilih</a>
                        @endif
                    </li>
                    <li class="nav-item ms-lg-3">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-logout px-4" type="submit">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-3 flex-grow-1 mb-5">
    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger bg-danger text-white border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @yield('content')
    </div>

    <footer class="py-4 text-center border-top border-light border-opacity-10 mt-auto" style="background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px);">
        <div class="container text-muted small">
            <p class="mb-1">&copy; {{ date('Y') }} <strong>SecureVote</strong> Politeknik Caltex Riau. Seluruh Hak Cipta Dilindungi.</p>
            <p class="mb-0 opacity-50"><i class="bi bi-shield-check text-info me-1"></i> Didukung oleh Enkripsi AES-256 & Logika Proposisional</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('info'))
                Swal.fire({
                    title: 'Informasi',
                    text: "{{ session('info') }}",
                    icon: 'info',
                    background: '#1e293b',
                    color: '#fff',
                    confirmButtonColor: '#38bdf8',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Peringatan',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    background: '#1e293b',
                    color: '#fff',
                    confirmButtonColor: '#ef4444',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    background: '#1e293b',
                    color: '#fff',
                    confirmButtonColor: '#10b981',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>
</body>
</html>
