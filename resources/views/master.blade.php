<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, intial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home page</title>

    <!-- for top arrow -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />


    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/toparrow.css') }}" />
    <!--fav-icon-->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

</head>

<body>



    <nav>
    <a href="#" class="logo">
        <img src="{{ asset('images/logo.png') }}" width="320px" />
    </a>
    <input class="menu-btn" type="checkbox" id="menu-btn" />
    <label class="menu-icon" for="menu-btn">
        <span class="nav-icon"></span>
    </label>
    <ul class="menu" style="border-radius: 5px;">
        <li><a href="{{ route('index') }}">Home</a></li>
        <li><a href="{{ route('department') }}">Departments</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('univ-info') }}">University-Info</a></li>
        <li><a href="{{ route('courses') }}">Courses</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>

        {{-- Authentication Links --}}
        @auth
            {{-- Check if user is any type of admin --}}
            @if(in_array(auth()->user()->role, ['global_admin', 'hod_admin', 'haa_admin', 'hsa_admin', 'teacher_admin', 'fa_admin']))
                {{-- Admin user logged in --}}
                <li><a href="{{ route('admin.dashboard') }}" class="text-white">Admin Dashboard</a></li>

                {{-- Show only specific role links --}}
                @if (auth()->user()->role === 'haa_admin')
                    <li><a href="{{ route('admin.haa') }}" class="text-white">Academic Affairs</a></li>
                @endif

                @if (auth()->user()->role === 'hsa_admin')
                    <li><a href="{{ route('admin.hsa') }}" class="text-white">Staff Affairs</a></li>
                @endif

                @if (auth()->user()->role === 'teacher_admin')
                    <li><a href="{{ route('admin.teacher') }}" class="text-white">Teacher Admin</a></li>
                @endif

                @if (auth()->user()->role === 'fa_admin')
                    <li><a href="{{ route('admin.fa') }}" class="text-white">Finance Admin</a></li>
                @endif

                @if (auth()->user()->role === 'hod_admin')
                    <li><a href="{{ route('admin.hod') }}" class="text-white">My Department</a></li>
                @endif

                {{-- Logout for Admin --}}
                <li>
                    <a class="active" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        style="width:auto; border-radius: 5px; cursor: pointer;">
                        Logout (Admin)
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                {{-- Regular student logged in --}}
                <li><a href="{{ route('dashboard') }}" class="text-white">Dashboard</a></li>
                <li>
                    <a class="active" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        style="width:auto; border-radius: 5px; cursor: pointer;">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @endif
        @else
            {{-- No one logged in --}}
            {{-- <li>
                <a class="active" href="{{ route('login') }}"
                    style="width:auto; border-radius: 5px; cursor: pointer;">Student Login</a>
            </li>
            <li>
                <a class="active" href="{{ route('admin.login') }}"
                    style="width:auto; border-radius: 5px; cursor: pointer; background-color: #dc3545;">Admin Login</a>
            </li> --}}
        @endauth
    </ul>
</nav>


    <section class="section">
        @yield('content')
    </section>

    <a href="#" class="back-to-top" aria-label="Back to top" title="Back to top">
        <span class="material-icons" aria-hidden="true">arrow_upward</span>
    </a>


    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5>Student Registration System (SRS)</h5>
                    <p class="mb-0 text-white">Streamlining educational administration <span id="year"></span>.</p>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('index') }}" class="text-white">Home</a></li>
                        <li><a href="{{ route('department') }}" class="text-white">Departments</a></li>
                        <li><a href="{{ route('about') }}" class="text-white">About</a></li>
                        <li><a href="{{ route('univ-info') }}" class="text-white">University-Info</a></li>
                        <li><a href="{{ route('courses') }}" class="text-white">Courses</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <h5>Contact</h5>
                    <address>
                        No.(5) Main Rd., Ah Pyin Padan Village,<br>
                        Hlaing Thar Yar, Yangon, Myanmar.<br>
                        <a href="mailto:info@wytu.edu.mm" class="text-white">info@wytu.edu.mm</a>
                    </address>
                </div>
                <div class="col-md-3">
                    <h5>Follow Us</h5>
                    <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                    <a href="https://wytu.edu.mm/" target="_blank" class="text-white me-2"><i
                            class="bi bi-globe-americas"></i></a>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-12 text-center">
                    <p style="color: white;">Copyright (C) - <span id="year"></span> | Developed <span
                            class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span> By <a href="#"
                            style="color: white;"><b>e-Service (Group-1)</b> </a> </p>
                </div>
            </div>
        </div>
    </footer>


    <script>
        /* for copyright footer - date */
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>


    <script src="{{ asset('js/toparrow.js') }}"></script>

    <script src="{{ asset('js/text.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>

</html>
