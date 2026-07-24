<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Yashvi Enterprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4b2e83;
            --sidebar-width: 260px;
        }
        body { font-family: 'Poppins', sans-serif; background: #f4f6fb; }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, #2d1b4e 100%);
            position: fixed; top: 0; left: 0; color: #fff; z-index: 100;
        }
        .sidebar .brand { padding: 22px 20px; font-size: 20px; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar .brand span { color: #ffc93c; }
        .sidebar a { color: rgba(255,255,255,.8); padding: 12px 22px; display: flex; align-items: center; gap: 10px; text-decoration: none; font-size: 15px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,.12); color: #fff; border-left: 4px solid #ffc93c; }
        .main-content { margin-left: var(--sidebar-width); }
        .topbar { background: #fff; padding: 14px 30px; box-shadow: 0 2px 6px rgba(0,0,0,.05); display: flex; justify-content: space-between; align-items: center; }
        .content-wrap { padding: 28px; }
        .card { border: none; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
        .stat-card { color: #fff; border-radius: 14px; padding: 22px; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #3a2266; border-color: #3a2266; }
        table thead { background: #f8f7fc; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="brand">Yashvi <span>Enterprise</span></div>
        <nav class="mt-3">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a href="{{ route('admin.blogs.index') }}" class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i> Manage Blogs
            </a>
            <a href="{{ route('front.home') }}" target="_blank">
                <i class="fa-solid fa-globe"></i> View Website
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button class="btn btn-outline-danger btn-sm">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout ({{ auth()->user()->name }})
                </button>
            </form>
        </div>
        <div class="content-wrap">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
