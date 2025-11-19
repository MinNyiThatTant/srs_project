<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'West Yangon Technological University')</title>

    <!-- for top arrow -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/toparrow.css') }}" />

    <!--fav-icon-->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
        }

        /* Light Blue Background for Home Page */
        .light-blue-bg {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(33, 150, 243, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            margin: 2rem auto;
            border: 1px solid #e1f5fe;
        }

        .header-section {
            background: linear-gradient(135deg, #1976d2, #42a5f5);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23097bed' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .university-logo {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 10px;
        }

        .login-options {
            padding: 3rem 2rem;
        }

        .option-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.1);
            height: 100%;
            border: 1px solid #e3f2fd;
        }

        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(33, 150, 243, 0.2);
            border-color: #90caf9;
        }

        .student-card {
            border-top: 4px solid #1976d2;
        }

        .admin-card {
            border-top: 4px solid #388e3c;
        }

        .apply-section {
            background: linear-gradient(135deg, #fff3e0, #ffecb3);
            padding: 2.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
            border: 1px solid #ffe0b2;
            position: relative;
            overflow: hidden;
        }

        .apply-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(180deg);
            }
        }

        .btn-student {
            background: linear-gradient(135deg, #1976d2, #42a5f5);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-student:hover {
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.4);
            color: white;
        }

        .btn-admin {
            background: linear-gradient(135deg, #388e3c, #4caf50);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            background: linear-gradient(135deg, #2e7d32, #43a047);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
            color: white;
        }

        .btn-new-student {
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-new-student:hover {
            background: linear-gradient(135deg, #f57c00, #ffa726);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
            color: white;
        }

        .btn-old-student {
            background: linear-gradient(135deg, #0288d1, #03a9f4);
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-old-student:hover {
            background: linear-gradient(135deg, #0277bd, #039be5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(3, 169, 244, 0.4);
            color: white;
        }

        .welcome-message {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            color: #2e7d32;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
            border: 1px solid #c8e6c9;
        }

        .section-title {
            color: #1976d2;
            border-bottom: 2px solid #bbdefb;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #1976d2;
        }

        /* Default section background for other pages */
        .section {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
    </style>

    <!-- Additional styles for specific pages -->
    @yield('styles')
</head>

<body class="@yield('body-class', '')">

    <nav>
        <a href="#" class="logo">
            <img src="{{ asset('images/logo.png') }}" width="320px" alt="WYTU Logo" />
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
            @if (Auth::guard('admin')->check())
                {{-- Admin is logged in --}}
                <li><a href="{{ route('admin.dashboard') }}" class="text-black">Admin Dashboard</a></li>

                {{-- Role-specific navigation links --}}
                @if (Auth::guard('admin')->user()->role === 'haa_admin')
                    {{-- <li><a href="{{ route('admin.haa') }}" class="text-black">Academic Affairs</a></li> --}}
                @endif

                @if (Auth::guard('admin')->user()->role === 'hsa_admin')
                    {{-- <li><a href="{{ route('admin.hsa') }}" class="text-black">Staff Affairs</a></li> --}}
                @endif

                @if (Auth::guard('admin')->user()->role === 'teacher_admin')
                    {{-- <li><a href="{{ route('admin.teacher') }}" class="text-black">Teacher Admin</a></li> --}}
                @endif

                @if (Auth::guard('admin')->user()->role === 'fa_admin')
                    {{-- <li><a href="{{ route('admin.fa') }}" class="text-black">Finance Admin</a></li> --}}
                @endif

                {{-- HOD Admin specific link --}}
                @if (Auth::guard('admin')->user()->role === 'hod_admin')
                    {{-- <li><a href="{{ route('admin.hod.dashboard') }}" class="text-black">My Department</a></li> --}}
                @endif

                {{-- Global Admin specific links --}}
                @if (Auth::guard('admin')->user()->role === 'global_admin')
                    {{-- <li><a href="{{ route('admin.global') }}" class="text-black">Global Admin</a></li> --}}
                    {{-- <li><a href="{{ route('admin.users') }}" class="text-black">Manage Users</a></li> --}}
                @endif

                {{-- Logout --}}
                <li>
                    <a class="active" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"
                        style="width:auto; border-radius: 5px; cursor: pointer;">
                        Logout
                    </a>
                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                </li>
            @elseif(Auth::check())
                {{-- Student is logged in --}}
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
            @else
                {{-- No one is logged in --}}
                {{-- Login buttons removed from nav since they're on home page --}}
            @endif
        </ul>
    </nav>

    <section class="section @yield('section-class', '')">
        @yield('content')
    </section>

    <a href="#" class="back-to-top" aria-label="Back to top" title="Back to top">
        <span class="material-icons" aria-hidden="true">arrow_upward</span>
    </a>

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

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Smooth scrolling for back to top
            const backToTop = document.querySelector('.back-to-top');
            if (backToTop) {
                backToTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // Add animation to cards on scroll for home page
            if (document.querySelector('.option-card')) {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);

                const cards = document.querySelectorAll('.option-card, .apply-section');
                cards.forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(card);
                });
            }
        });
    </script>

    <!-- Additional scripts for specific pages -->
    @yield('scripts')
</body>

</html>
