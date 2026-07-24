<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Yashvi Enterprise Blog')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fc; }
        .navbar-brand { font-weight: 700; color: #4b2e83 !important; }
        .hero { background: linear-gradient(135deg, #4b2e83, #6f42c1); color: #fff; padding: 60px 0; }
        .blog-card { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.08); transition: .2s; }
        .blog-card:hover { transform: translateY(-5px); }
        .blog-card img { height: 200px; object-fit: cover; width: 100%; }
        footer { background: #2d1b4e; color: #fff; padding: 24px 0; margin-top: 60px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('front.home') }}">Yashvi Enterprise</a>
        </div>
    </nav>

    @yield('content')

    <footer class="text-center">
        <p class="mb-0">&copy; {{ date('Y') }} Yashvi Enterprise. All rights reserved.</p>
    </footer>
</body>
</html>
