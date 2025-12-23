<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>KomikVerse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">KomikVerse</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('comics.index') }}">Komik</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('borrowings.index') }}">Pinjamanku</a></li>
                        @if (auth()->user()->is_admin)
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.borrowings.index') }}">Admin
                                    Peminjaman</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Logout</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register.show') }}">Register</a></li>
                    @endauth
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('comics.index') }}">Komik</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('borrowings.index') }}">Pinjamanku</a></li>
                        @if (auth()->user()->is_admin)
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.borrowings.index') }}">Admin
                                    Peminjaman</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Logout</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

</body>

</html>
