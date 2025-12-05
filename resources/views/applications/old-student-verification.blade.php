<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Verification - WYTU</title>
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
                        <h3><i class="fas fa-user-graduate me-2"></i>Student Verification</h3>
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
                        
                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form id="verifyForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Student ID</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" name="student_id" class="form-control" 
                                           placeholder="Enter student ID" required
                                           value="WYTU202400001">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" 
                                           placeholder="Enter password" required
                                           value="password123">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" name="date_of_birth" class="form-control" 
                                           required value="2000-01-01">
                                </div>
                                <small class="text-muted">Format: YYYY-MM-DD</small>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-verify text-white btn-lg w-100 py-3" id="verifyBtn">
                                    <i class="fas fa-check-circle me-2"></i>Verify Student
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <p class="text-muted">
                                Need test credentials? 
                                <a href="/test/create-student" class="text-decoration-none">Create test student</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('verifyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            const btn = document.getElementById('verifyBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
            
            try {
                const response = await fetch('/student/verify', {
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
                    alert('✅ Verification successful! Redirecting...');
                    window.location.href = '/old-student-apply';
                } else {
                    alert('❌ Verification failed: ' + result.message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Verify Student';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('⚠️ Network error. Please try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Verify Student';
            }
        });
    </script>
</body>
</html>