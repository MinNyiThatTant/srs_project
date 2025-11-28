<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Login Type - West Yangon Technological University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            margin: 2rem auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .university-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .login-options {
            padding: 3rem 2rem;
        }
        
        .option-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        
        .option-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .student-card {
            border-top: 5px solid var(--primary-color);
        }
        
        .admin-card {
            border-top: 5px solid var(--success-color);
        }
        
        .apply-section {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            text-align: center;
        }
        
        .btn-student {
            background: linear-gradient(135deg, var(--primary-color), #0b5ed7);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .btn-admin {
            background: linear-gradient(135deg, var(--success-color), #157347);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .btn-new-student {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
        }
        
        .btn-old-student {
            background: linear-gradient(135deg, var(--info-color), #0ba8cc);
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
        }
        
        .footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 3rem;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        
        .social-icons a:hover {
            color: var(--info-color);
        }
        
        .nav-buttons {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation Menu -->
    <div class="container mt-3">
        <div class="nav-buttons text-center">
            <a href="{{ route('index') }}" class="btn btn-outline-light me-2">
                <i class="fas fa-home"></i> HOME
            </a>
            <a href="{{ route('department') }}" class="btn btn-outline-light me-2">
                <i class="fas fa-building"></i> DEPARTMENTS
            </a>
            <a href="{{ route('about') }}" class="btn btn-outline-light me-2">
                <i class="fas fa-info-circle"></i> ABOUT
            </a>
            <a href="{{ route('univ-info') }}" class="btn btn-outline-light me-2">
                <i class="fas fa-university"></i> UNIVERSITY INFO
            </a>
            <a href="{{ route('courses') }}" class="btn btn-outline-light me-2">
                <i class="fas fa-book"></i> COURSES
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light me-2">
                <i class="fas fa-phone"></i> CONTACT
            </a>
            <a href="{{ route('choose.login') }}" class="btn btn-light">
                <i class="fas fa-sign-in-alt"></i> LOGIN/REGISTER
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="login-container">
                    <!-- Header Section -->
                    <div class="header-section">
                        <div class="university-logo">
                            <i class="fas fa-university text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h1 class="display-5 fw-bold">West Yangon Technological University</h1>
                        <p class="lead mb-0">Innovation Through Technology</p>
                    </div>

                    <!-- Login Options Section -->
                    <div class="login-options">
                        <div class="row text-center mb-5">
                            <div class="col-12">
                                <h2 class="fw-bold text-primary mb-3">Choose Login Type</h2>
                                <p class="lead text-muted">Select your role to access the appropriate portal</p>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <!-- Student Login Card -->
                            <div class="col-md-6">
                                <div class="card option-card student-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-user-graduate text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <h4 class="card-title fw-bold text-primary">Student Login</h4>
                                        <p class="card-text text-muted mb-4">
                                            Access your student dashboard, view courses, check grades, and manage your academic profile.
                                        </p>
                                        <a href="{{ route('login') }}" class="btn btn-student btn-lg">
                                            <i class="fas fa-sign-in-alt me-2"></i>Student Login
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Login Card -->
                            <div class="col-md-6">
                                <div class="card option-card admin-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-users-cog text-success" style="font-size: 3rem;"></i>
                                        </div>
                                        <h4 class="card-title fw-bold text-success">Admin Login</h4>
                                        <p class="card-text text-muted mb-4">
                                            Access admin management panel for system administration, user management, and academic oversight.
                                        </p>
                                        <a href="{{ route('admin.login') }}" class="btn btn-admin btn-lg">
                                            <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Apply Now Section -->
                        <div class="apply-section">
                            <h3 class="fw-bold mb-3">🎓 New to WYTU?</h3>
                            <p class="lead mb-4">Start your journey with West Yangon Technological University by applying for admission</p>
                            
                            <div class="row justify-content-center g-3">
                                <div class="col-lg-5 col-md-6">
                                    <div class="d-grid">
                                        <a href="{{ route('new.student.apply') }}" class="btn btn-new-student btn-lg">
                                            <i class="fas fa-user-plus me-2"></i>FOR NEW STUDENTS - APPLY NOW
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <div class="d-grid">
                                        <a href="{{ route('old.student.apply') }}" class="btn btn-old-student btn-lg">
                                            <i class="fas fa-redo me-2"></i>FOR OLD STUDENTS - APPLY NOW
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="mt-3 mb-0 text-muted">
                                <small>Complete your application and join our community of innovators and leaders</small>
                            </p>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <div class="alert alert-info">
                                    <h5 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Student Registration System (SRS)
                                    </h5>
                                    <p class="mb-0">Streamlining educational administration 2025</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Contact Information</h5>
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        No.(S) Main Rd., Ah Pym Padan Village,<br>
                        <span style="margin-left: 1.5rem;">Hlang Thar Yar, Yangon, Myanmar.</span>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        info@wytu.edu.mm
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        +95 1 234 5678
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li><a href="{{ route('index') }}" class="text-light text-decoration-none">Home</a></li>
                                <li><a href="{{ route('department') }}" class="text-light text-decoration-none">Departments</a></li>
                                <li><a href="{{ route('about') }}" class="text-light text-decoration-none">About</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li><a href="{{ route('univ-info') }}" class="text-light text-decoration-none">University Info</a></li>
                                <li><a href="{{ route('courses') }}" class="text-light text-decoration-none">Courses</a></li>
                                <li><a href="{{ route('contact') }}" class="text-light text-decoration-none">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Follow Us -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h5 class="fw-bold mb-3">Follow Us</h5>
                    <div class="social-icons mb-3">
                        <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                    <p class="mb-0">
                        Stay connected with WYTU for the latest updates, news, and events.
                    </p>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">

            <!-- Copyright -->
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        &copy; 2025 West Yangon Technological University. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        [ Developed © By e-Service (Group-1) ]
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
        });
    </script>
</body>
</html>