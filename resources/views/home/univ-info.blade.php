@extends('layouts.master')

@section('title', 'West Yangon Technological University - Home')

@section('content')

<style>
    .hero-bg {
        background-image: url('images/hero-bg.png');
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .hero-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.85);
    }

    .stat-card {
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .department-badge {
        font-size: 0.9rem;
        margin-right: 8px;
        margin-bottom: 8px;
    }
</style>

<section class="main custom-padding" style="background-image: url(images/hero-bg.png);">


    <!-- University Hero Section -->
    {{-- <section class="hero-bg py-5 position-relative"> --}}
    <div class="container py-5 mt-4">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4" style="color: aliceblue">{{ $universityInfo['name'] }}</h1>
                <p class="lead mb-4" style="color: aliceblue">{{ $universityInfo['motto'] ?? 'Excellence in Education and Innovation' }}</p>
                <a href="{{ route('contact') }}" class="btn btn-info btn-lg px-4 me-2">
                    <i class="bi bi-envelope"></i> Contact Us
                </a>
                <a href="{{ $universityInfo['website'] ?? '#' }}" class="btn btn-info btn-lg px-4"
                    target="_blank">
                    <i class="bi bi-globe"></i> Visit Website
                </a>
            </div>
            <div class="col-lg-6">
                <img src="{{ $universityInfo['logo'] }}" alt="{{ $universityInfo['name'] }} logo" class="img-fluid"
                    style="max-width: 250px;"> 
                    <div class="mt-4">
                        <h4 style="color: aliceblue">Accreditations</h4>
                        <div class="d-flex flex-wrap">
                            @foreach ($universityInfo['accreditations'] ?? [] as $accreditation)
                                <span class="badge bg-primary bg-opacity-10 text-white">
                                    <i class="bi bi-journal-bookmark-fill me-1"></i>{{ $accreditation }}
                                </span>
                            @endforeach
                        </div>
                    </div>
            </div>
        </div>
    </div>
    {{-- </section> --}}

    <!-- Status Section -->
    {{-- <section class="py-5 bg-light"> --}}
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card stat-card h-100 text-center py-3">
                        <div class="card-body">
                            <h3 class="text-primary">{{ $universityInfo['founded'] }}</h3>
                            <p class="mb-0">Established Year</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card h-100 text-center py-3">
                        <div class="card-body">
                            <h3 class="text-primary">{{ number_format($universityInfo['students']) }}</h3>
                            <p class="mb-0">Current Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card h-100 text-center py-3">
                        <div class="card-body">
                            <h3 class="text-primary">{{ number_format($universityInfo['faculty'] ?? 1200) }}</h3>
                            <p class="mb-0">Faculty Members</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card h-100 text-center py-3">
                        <div class="card-body">
                            <h3 class="text-primary">{{ count($universityInfo['departments']) }}+</h3>
                            <p class="mb-0">Departments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </section> --}}

    <!-- Main University Info -->
    {{-- <section class="py-5"> --}}
        <div class="container">
            <div class="row g-5">
                
            </div>
        </div>
    </section>