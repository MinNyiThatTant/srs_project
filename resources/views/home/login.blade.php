@extends('layouts.master')

@section('title', 'West Yangon Technological University - Home')

@section('body-class', 'light-blue-bg')

@section('content')
    <section class="main pt-5"
        style="background-image: url({{ asset('images/hero-bg.png') }}); height: 100vh; display: flex; justify-content: center; align-items: center;">
        <div class="container my-4 d-flex justify-content-center align-items-center" style="height: 100%;">
            <div class="card mx-auto" style="width: 20rem;">
                <br>
                <img class="card-img-top mx-auto" src="{{ asset('images/login.png') }}" style="width: 60%;"
                    alt="Card image cap">

                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                <div class="card-body">
                    <form action="{{ route('authenticate') }}" method="post">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="name@email.com"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-lock"></i> Login
                            </button>
                            
                            <a href="{{ route('register') }}" class="btn btn-info">
                                <i class="fa fa-user-plus"></i> Register
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
