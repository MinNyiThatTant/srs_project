@extends('layouts.master')

@section('title', 'West Yangon Technological University - Home')

@section('content')

<!-- content -->
<!-- <div class="container"> -->
<section class="main custom-padding" style="background-image: url(images/hero-bg.png);">
    <div class="container mt-4 py-5">
        <h1 class="text-center"></h1>
        <div class="row g-4 mt-3">
            <!-- Contact Form -->
            <div class="col-md-6">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Your full name" required />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" required />
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" placeholder="Subject" />
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="5" placeholder="Write your message here..."
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-info"><i class="bi bi-envelope-fill"></i> Send
                        Message</button>
                </form>


            </div>
            <!-- Google Map -->
            <div class="col-md-6">
                <div class="map-responsive">

                    <iframe style="border-radius: 10px;"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3231.49693099441!2d96.00584617434232!3d16.86907098393232!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c1bf1c49ac518d%3A0xf20029d58b338b3a!2sWest%20Yangon%20Technological%20University%20(WYTU)%20We%20are%20CDM!5e1!3m2!1sen!2smm!4v1749147997524!5m2!1sen!2smm"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        
    </div>
</section>