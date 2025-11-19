@extends('layouts.master')

@section('title', 'West Yangon Technological University - Home')

@section('body-class', 'light-blue-bg')

@section('content')
<section class="main" style="background-image: url({{ asset('images/hero-bg.png') }}); height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div class="container my-4 d-flex justify-content-center align-items-center" style="height: 100%;">
        <div class="card mx-auto" style="width: 25rem;">
            <br>
            <img class="card-img-top mx-auto" src="{{ asset('images/register.jpg') }}" style="width: 60%;" alt="Card image cap">
            <div class="card-body">
                <form action="{{ route('processRegister') }}" method="post" novalidate>
                    @csrf
                    <div class="row gy-3 overflow-hidden">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    placeholder="Your Name" 
                                    value="{{ old('name') }}" 
                                    autocomplete="name"
                                >
                                <label for="name" class="form-label">Name</label>
                                @error('name')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    placeholder="name@example.com" 
                                    value="{{ old('email') }}" 
                                    autocomplete="email"
                                >
                                <label for="email" class="form-label">Email</label>
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    placeholder="Password" 
                                    autocomplete="new-password"
                                >
                                <label for="password" class="form-label">Password</label>
                                @error('password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    placeholder="Confirm Password" 
                                    autocomplete="new-password"
                                >
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                @error('password_confirmation')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <button class="btn bsb-btn-xl btn-primary" type="submit">Register Now</button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <a href="{{ route('login') }}" class="btn bsb-btn btn-info text-center">Login</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection