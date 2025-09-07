@extends('master')
@section('content')

<!-- Hero Section -->
    <section class="hero-section mb-5" style="background-image: url(images/hero-bg.png);">
        <div class="container py-5 mt-4">
            <h1 class="display-4 font-weight-bold mb-3 text-white">About Our System</h1>
            <p class="lead text-white">Streamlining student registration for educational institutions</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="container mb-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
            <h2 class="mb-4">Our Story</h2>
            <p class="lead">After four months of intensive training in web development, 
                we created this Student Registration System (SRS) to revolutionize registration at Technological University.</p>
            <p>Our training encompassed essential technologies 
                such as HTML, CSS, Bootstrap, PHP, Laravel, and MySQL database management. 
                This comprehensive curriculum equipped us with the skills needed to tackle real-world challenges 
                in web development.</p>
            <p>Working collaboratively in our group-1, we applied our knowledge to design 
                and develop the SRS.
                Our goal was to create a user-friendly platform that enhances the registration experience 
                for both students and administrators.</p>
            <p>Today, we are proud to say that our SRS is already serving to WYTU
                Our group-1 has not only strengthened our technical skills 
                but also fostered a sense of accomplishment 
                and teamwork - Thank you. </p>
        </div>
            <div class="col-lg-6">
                <img src="/images/wytu.jpg"
                    alt="University campus"
                    class="img-fluid rounded shadow">
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section py-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2>Our Mission & Values</h2>
                    <p class="lead">Guiding principles that drive our innovation</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card p-4 text-center h-100">
                        <div class="card-body">
                            <div class="display-4 mb-3"><i class="bi bi-speedometer2"></i></div>
                            <h4 class="card-title">Efficiency</h4>
                            <p class="card-text">Streamline administrative processes to save time and resources for
                                educational institutions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card p-4 text-center h-100">
                        <div class="card-body">
                            <div class="display-4 mb-3"><i class="bi bi-shield-lock"></i></div>
                            <h4 class="card-title">Security</h4>
                            <p class="card-text">Protecting sensitive student data with industry-leading security measures
                                and encryption.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card p-4 text-center h-100">
                        <div class="card-body">
                            <div class="display-4 mb-3"><i class="bi bi-people"></i></div>
                            <h4 class="card-title">Accessibility</h4>
                            <p class="card-text">Creating intuitive interfaces that work for all users regardless of
                                technical ability.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card p-4 text-center h-100">
                        <div class="card-body">
                            <div class="display-4 mb-3"><i class="bi bi-lightbulb"></i></div>
                            <h4 class="card-title">Innovation</h4>
                            <p class="card-text">Continuously improving our system with new features based on user feedback.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="container mb-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>Meet Our Team</h2>
                <p class="lead">The dedicated professionals behind our system</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 border-0">
                    <div class="card-body">
                        <div class="team-member mx-auto mb-3">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/42a1042b-d6ed-464a-888a-a04f9347cae3.png"
                                alt="Smiling female software developer with short brown hair working at a laptop"
                                class="img-fluid">
                        </div>
                        <h4 class="card-title">Sarah Johnson</h4>
                        <p class="text-primary fw-bold">Lead Developer</p>
                        <p class="card-text">Oversees all technical development and system architecture.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 border-0">
                    <div class="card-body">
                        <div class="team-member mx-auto mb-3">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/d0f2ecbc-9e2b-4fb6-8799-b349d474c9bd.png"
                                alt="Professional male education consultant in a blue shirt reviewing documents"
                                class="img-fluid">
                        </div>
                        <h4 class="card-title">Michael Chen</h4>
                        <p class="text-primary fw-bold">Education Specialist</p>
                        <p class="card-text">Ensures our system meets educational institution requirements.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 border-0">
                    <div class="card-body">
                        <div class="team-member mx-auto mb-3">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/fdb0d81a-f746-4b20-ba20-b154aa69a35a.png"
                                alt="Young female UX designer with glasses presenting wireframes on a tablet"
                                class="img-fluid">
                        </div>
                        <h4 class="card-title">Emily Rodriguez</h4>
                        <p class="text-primary fw-bold">UX Designer</p>
                        <p class="card-text">Creates intuitive interfaces for students and administrators.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card text-center h-100 border-0">
                    <div class="card-body">
                        <div class="team-member mx-auto mb-3">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/994e5baf-07d5-4a5c-90bc-e2389842fe96.png"
                                alt="Senior male IT security expert in a dark shirt working on computer security"
                                class="img-fluid">
                        </div>
                        <h4 class="card-title">David Wilson</h4>
                        <p class="text-primary fw-bold">Security Specialist</p>
                        <p class="card-text">Implements and maintains our robust security protocols.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-primary text-white py-5 mb-5">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <div class="display-4 fw-bold">50+</div>
                    <p class="mb-0">Institutions Served</p>
                </div>
                <div class="col-md-3">
                    <div class="display-4 fw-bold">95%</div>
                    <p class="mb-0">Process Time Reduced</p>
                </div>
                <div class="col-md-3">
                    <div class="display-4 fw-bold">10K+</div>
                    <p class="mb-0">Students Registered</p>
                </div>
                <div class="col-md-3">
                    <div class="display-4 fw-bold">24/7</div>
                    <p class="mb-0">Support Available</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Call to Action -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Transform Your Registration Process?</h2>
            <p class="lead mb-4">Join dozens of educational institutions using our system today</p>
            <a href="{{route('index')}}" class="btn btn-primary btn-lg px-4 me-2">Get Started</a>
            <a href="{{route('contact')}}" class="btn btn-outline-primary btn-lg px-4">Contact Us</a>
        </div>
    </section>
@endsection
