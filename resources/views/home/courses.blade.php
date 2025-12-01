@extends('layouts.master')

@section('title', 'West Yangon Technological University - Home')

@section('content')

    <style>
        .hero-section {
            color: white;
            text-align: center;
            min-height: 400px;
            display: flex;
            align-items: center;
        }

        .feature-card {
            transition: transform 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .team-member img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }

        .mission-section {
            background-color: #f8f9fa;
        }

        .course-card {
            height: 100%;
        }

        .course-card img {
            height: 200px; /* Fixed height for uniformity */
            width: 100%;
            object-fit: cover; /* Ensures the image covers the area */
        }
    </style>

    <!-- Hero Section -->
    <section class="courses-hero mb-5 custom-padding" style="background-image: url(images/hero-bg.png);">
        <div class="container mt-4 py-5">
            <h1 class="display-4 font-weight-bold mb-3 text-white">Our Engineering Programs Courses</h1>
            <p class="lead text-white">West Yangon Technological University offers 11 specialized engineering departments
                with modern facilities and industry-focused curriculum.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Courses Grid -->
        <div class="row">

            <!-- Civil Engineering -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Civil Engineering</h4></div>
                    <img src="{{ asset('images/civil.jpg') }}" class="card-img-top" alt="Civil Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Design and construct infrastructure projects like buildings, bridges, and
                            transportation systems.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Computer Science -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Computer Engineering & IT</h4></div>
                    <img src="{{ asset('images/ceit.jpg') }}" class="card-img-top" alt="Computer Engineering & IT">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Master software development, artificial intelligence, and cutting-edge
                            computing technologies.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Electrical Power -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Electrical Power Engineering</h4></div>
                    <img src="{{ asset('images/ep.jpg') }}" class="card-img-top" alt="Electrical Power Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Specialize in power generation, transmission, and renewable energy systems
                            engineering.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Architectural -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Architectural Engineering</h4></div>
                    <img src="{{ asset('images/archi.jpg') }}" class="card-img-top" alt="Architectural Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Combine architectural design with engineering principles to create sustainable
                            buildings.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Mechanical -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Mechanical Engineering</h4></div>
                    <img src="{{ asset('images/mech.jpg') }}" class="card-img-top" alt="Mechanical Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Design and analyze mechanical systems from small components to large industrial
                            machines.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Electronics -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Electronics Engineering</h4></div>
                    <img src="{{ asset('images/ec.jpg') }}" class="card-img-top" alt="Electronics Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Develop expertise in circuit design, embedded systems, and modern electronic
                            devices.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Chemical -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-4 course-card">
                    <div class="card-header"><h4>Chemical Engineering</h4></div>
                    <img src="{{ asset('images/chem.jpg') }}" class="card-img-top" alt="Chemical Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Design and construct processes for producing chemicals and materials.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Agricultural -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Agricultural Engineering</h4></div>
                    <img src="{{ asset('images/agri.jpg') }}" class="card-img-top" alt="Agricultural Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Focus on the design and improvement of agricultural machinery and systems.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Mechatronics -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Mechatronics Engineering</h4></div>
                    <img src="{{ asset('images/mc.png') }}" class="card-img-top" alt="Mechatronics Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Integrate mechanical, electronic, and software engineering to create smart
                            systems.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Textile -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Textile Engineering</h4></div>
                    <img src="{{ asset('images/textile.jpg') }}" class="card-img-top" alt="Textile Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Focus on the design and production of textiles and fabrics.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Metrology -->
            <div class="col-xs-12 col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary mb-3 course-card">
                    <div class="card-header"><h4>Metrology Engineering</h4></div>
                    <img src="{{ asset('images/metro.jpg') }}" class="card-img-top" alt="Metrology Engineering">
                    <div class="card-body">
                        <h5 class="card-title">Bachelor of Engineering</h5>
                        <p class="card-text">Specialize in measurement science and technology.</p>
                        <a href="#" class="btn btn-info btn-sm">Program Details <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <a href="#" class="back-to-top" aria-label="Back to top" title="Back to top">
        <span class="material-icons" aria-hidden="true">arrow_upward</span>
    </a>

    <!-- Call to Action -->
    <section class="bg-light py-5 mt-5">
        <div class="container text-center">
            <h2 class="font-weight-bold mb-4">Interested in Our Engineering Programs?</h2>
            
            <a href="{{route('index')}}" class="btn btn-primary btn-lg">Admission Information</a>
        </div>
    </section>
@endsection