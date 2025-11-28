<!-- resources/views/admin/students/new-student.blade.php -->
@extends('admin.layouts.master')

@section('title', 'New Student Registration')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">New Student Registration</h4>
                    <p class="card-description">Fill in the form below to register as a new student</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="forms-sample" action="{{ route('student.register.process') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="{{ old('phone') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="program">Program/Course *</label>
                                    <select class="form-control" id="program" name="program" required>
                                        <option value="">Select Program</option>
                                        <option value="Computer Science" {{ old('program') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                        <option value="Business Administration" {{ old('program') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                                        <option value="Engineering" {{ old('program') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                        <option value="Medicine" {{ old('program') == 'Medicine' ? 'selected' : '' }}>Medicine</option>
                                        <option value="Law" {{ old('program') == 'Law' ? 'selected' : '' }}>Law</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Full Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter complete address" required>{{ old('address') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Submit Registration</button>
                        <button type="reset" class="btn btn-light">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection