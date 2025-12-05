<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Old Student Verification - WYTU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .btn-verify { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
        .btn-verify:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3><i class="fas fa-user-graduate me-2"></i>Old Student Verification</h3>
                        <p class="mb-0">West Yangon Technological University</p>
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="verificationForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Student ID</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" name="student_id" class="form-control" 
                                           placeholder="Enter your student ID" required
                                           value="WYTU202400001">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" 
                                           placeholder="Enter your password" required
                                           value="password123">
                                </div>
                                <small class="text-muted">
                                    <a href="#" class="text-decoration-none">Forgot password?</a>
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" name="date_of_birth" class="form-control" 
                                           required value="2000-01-01">
                                </div>
                                <small class="text-muted">Format: YYYY-MM-DD (e.g., 2000-01-01)</small>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-verify text-white btn-lg w-100 py-3" id="verifyBtn">
                                    <i class="fas fa-check-circle me-2"></i>Verify Student
                                </button>
                            </div>
                        </form>

                        <div class="mt-4 text-center">
                            <p class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Don't have test credentials? 
                                <a href="#" id="createTestStudent" class="text-decoration-none">Create test student</a>
                            </p>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('old.student.application.form') }}" class="text-decoration-none">
                                Already verified? Go to application form
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: transparent; border: none;">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-white mt-3">Verifying credentials...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verificationForm');
            const verifyBtn = document.getElementById('verifyBtn');
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            const createTestBtn = document.getElementById('createTestStudent');

            // Create test student
            if (createTestBtn) {
                createTestBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    verifyBtn.disabled = true;
                    verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
                    
                    try {
                        const response = await fetch('/old-student/create-test', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            alert('Test student created successfully!\n\nStudent ID: WYTU202400001\nPassword: password123\nDOB: 2000-01-01');
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Network error. Please try again.');
                    } finally {
                        verifyBtn.disabled = false;
                        verifyBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Verify Student';
                    }
                });
            }

            // Form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                // Show loading
                loadingModal.show();
                verifyBtn.disabled = true;
                
                try {
                    const response = await fetch('{{ route('old.student.verify') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Show success message
                        alert('✅ Verification successful! Redirecting...');
                        
                        // Redirect to application form
                        if (result.redirect_url) {
                            window.location.href = result.redirect_url;
                        } else {
                            window.location.href = '{{ route('old.student.application.form') }}';
                        }
                    } else {
                        alert('❌ Verification failed: ' + result.message);
                        loadingModal.hide();
                        verifyBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('⚠️ Network error. Please check your connection.');
                    loadingModal.hide();
                    verifyBtn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>