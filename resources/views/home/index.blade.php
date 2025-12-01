@extends('layouts.master')

@section('title', 'West Yangon Technological University - Home')

@section('body-class', 'light-blue-bg')

@section('content')
<div class="container py-5">
    <!-- Header Section with Login Buttons -->
    <section class="courses-hero mb-5 custom-padding" style="background-image: url({{ asset('images/hero-bg.png') }});">
        <div class="header-section position-relative">
            
            <!-- Login Buttons positioned above logo -->
            <div class="row justify-content-center mb-4">
                <div class="col-auto">
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <!-- Student Login Button -->
<<<<<<< HEAD
                        <a href="{{ route('student.login') }}" class="btn btn-admin-login btn-lg">
=======
                        <a href="{{ route('login') }}" class="btn btn-admin-login btn-lg">
>>>>>>> 804ca6b01de22ecd4261ad52d2b3976e1dca103c
                            <i class="fas fa-user-graduate me-2"></i>Login<br>
                            <p class="lead mb-0" style="font-size: 0.9rem; opacity: 0.9;">လျောက်လွှာတင်သွင်းမှုပြီးစီးပါက ဤနေရာမှဝင်ရောက်ပါ။</p>
                        </a>
                        
                        <!-- Admin Login Button -->
                        <a href="{{ route('admin.login') }}" 
                        {{-- class="btn btn-admin-login btn-lg" --}}
                        >
                            {{-- <i class="fas fa-users-cog me-2"></i>Admin Login --}}
                        </a>
                    </div>
                </div>
            </div>

            <!-- University Logo and Information -->
            <div class="university-logo">
                <img src="{{ asset('images/logo.png') }}" width="80" alt="WYTU Logo" style="max-width: 100%; height: auto;">
            </div>
            <h1 class="display-5 fw-bold mb-3">West Yangon Technological University (WYTU)</h1>
            <p class="lead mb-0" style="font-size: 1.3rem; opacity: 0.9;">Innovation Through Technology</p>
            <div class="mt-3">
                <span class="badge bg-light text-primary fs-6">Since 1999</span>
            </div>
            
            <!-- Apply Now Section -->
            <div class="apply-section mt-5">
                <div class="position-relative">
                    <h3 class="fw-bold mb-3" style="color: #e65100;">🎓 Apply For Admission</h3>
                    
                    <div class="row justify-content-center g-3">
                        <div class="col-lg-5 col-md-6">
                            <div class="d-grid">
                                <a href="{{ route('new.student.apply') }}" class="btn btn-new-student btn-lg">
                                    <i class="fas fa-user-plus me-2" style="font-size: medium"></i>ကျောင်းသားသစ်-လျောက်လွှာတင်ရန် 
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <div class="d-grid">
                                <a href="{{ route('old.student.apply') }}" class="btn btn-old-student btn-lg">
                                    <i class="fas fa-redo me-2" style="font-size: medium"></i>လက်ရှိကျောင်းသား-လျောက်လွှာတင်ရန်
                                </a>
                            </div>
                        </div>
                    </div>

                    <p class="mt-4 mb-0" style="color: #6d4c41;">
                        <small><i class="fas fa-info-circle me-1"></i>သင့်လျှောက်လွှာကို ပြည့်စုံစွာ ဖြည့်သွင်းပြီး ကျွန်ုပ်တို့၏ တက္ကသိုလ်သို့ တက်ရောက်ခြင်းကို စတင်လိုက်ပါ။</small>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Information Cards (Optional) -->
    <div class="row g-4 mb-5 mt-4">
        <!-- Why Choose WYTU Card -->
        <div class="col-md-6">
            <div class="card option-card student-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-graduation-cap feature-icon"></i>
                    </div>
                    <h4 class="card-title fw-bold text-primary mb-3">Quality Education</h4>
                    <p class="card-text text-muted">
                        Industry-focused curriculum with modern teaching methodologies and state-of-the-art facilities.
                    </p>
                </div>
            </div>
        </div>

        <!-- Career Opportunities Card -->
        <div class="col-md-6">
            <div class="card option-card admin-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-briefcase feature-icon" style="color: #388e3c;"></i>
                    </div>
                    <h4 class="card-title fw-bold text-success mb-3">Career Ready</h4>
                    <p class="card-text text-muted">
                        Strong industry connections and placement support to launch your engineering career.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Additional styles specific to home page */
    .light-blue-bg {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        min-height: 100vh;
    }

    .header-section {
        background: linear-gradient(135deg, #1976d2, #42a5f5);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        margin-bottom: 2rem;
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

    /* Login Buttons Styles */
    .btn-student-login {
        background: linear-gradient(135deg, #1976d2, #42a5f5);
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
    }

    .btn-student-login:hover {
        background: linear-gradient(135deg, #1565c0, #1e88e5);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(33, 150, 243, 0.4);
        color: white;
    }

    .btn-admin-login {
        background: linear-gradient(135deg, #388e3c, #4caf50);
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        color: white;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .btn-admin-login:hover {
        background: linear-gradient(135deg, #2e7d32, #43a047);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(76, 175, 80, 0.4);
        color: white;
    }

    /* Apply Section Styles */
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
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
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

    /* Card Styles */
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

    .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #1976d2;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation to cards on scroll
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

        // Observe all cards and apply section
        const cards = document.querySelectorAll('.option-card, .apply-section');
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Add animation to login buttons
        const loginButtons = document.querySelectorAll('.btn-student-login, .btn-admin-login');
        loginButtons.forEach(button => {
            button.style.opacity = '0';
            button.style.transform = 'translateY(20px)';
            button.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(button);
        });
    });
</script>
@endsection