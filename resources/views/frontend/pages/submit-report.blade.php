<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Report System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('reports.create') }}">Submit Report</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Submit a Report</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Please fill out the form below to submit a report. All fields are optional, but providing more information helps us better understand the situation.
                        </p>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="individual_name" class="form-label">Name of Individual</label>
                                <input type="text" 
                                       class="form-control @error('individual_name') is-invalid @enderror" 
                                       id="individual_name" 
                                       name="individual_name" 
                                       value="{{ old('individual_name') }}"
                                       placeholder="Enter the name of the individual"
                                       pattern="[A-Za-z\s]+"
                                       title="Name must contain only letters and spaces">
                                @error('individual_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback" id="name-error" style="display: none;">
                                    Name must contain only letters and spaces
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" 
                                       class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" 
                                       name="photo"
                                       accept="image/*">
                                <div class="form-text">Maximum file size: 5MB. Accepted formats: JPG, PNG, GIF</div>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="incident_date" class="form-label">Date of Incident</label>
                                <input type="date" 
                                       class="form-control @error('incident_date') is-invalid @enderror" 
                                       id="incident_date" 
                                       name="incident_date" 
                                       value="{{ old('incident_date') }}">
                                @error('incident_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}"
                                       placeholder="Enter the location">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="narrative" class="form-label">Narrative of the Issue</label>
                                <textarea class="form-control @error('narrative') is-invalid @enderror" 
                                          id="narrative" 
                                          name="narrative" 
                                          rows="6"
                                          placeholder="Describe what happened...">{{ old('narrative') }}</textarea>
                                @error('narrative')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <strong>Note:</strong> Your report will be reviewed by our admin team before being published to the public library.
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Submit Report</button>
                                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} Report System. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Name field validation - Accept only letters and spaces (max 10 spaces)
        const nameInput = document.getElementById('individual_name');
        const nameError = document.getElementById('name-error');
        
        nameInput.addEventListener('input', function(e) {
            const value = e.target.value;
            const regex = /^[A-Za-z\s]*$/;
            
            // Count spaces
            const spaceCount = (value.match(/ /g) || []).length;
            
            if (!regex.test(value)) {
                // Remove invalid characters
                e.target.value = value.replace(/[^A-Za-z\s]/g, '');
                nameInput.classList.add('is-invalid');
                nameError.textContent = 'Name must contain only letters and spaces';
                nameError.style.display = 'block';
            } else if (spaceCount > 10) {
                // Remove extra spaces
                let cleanValue = value;
                let spaces = 0;
                let result = '';
                for (let i = 0; i < cleanValue.length; i++) {
                    if (cleanValue[i] === ' ') {
                        spaces++;
                        if (spaces <= 10) {
                            result += cleanValue[i];
                        }
                    } else {
                        result += cleanValue[i];
                    }
                }
                e.target.value = result;
                nameInput.classList.add('is-invalid');
                nameError.textContent = 'Name can contain maximum 10 spaces';
                nameError.style.display = 'block';
            } else {
                nameInput.classList.remove('is-invalid');
                nameError.style.display = 'none';
            }
        });
        
        // Validate on blur
        nameInput.addEventListener('blur', function(e) {
            const value = e.target.value.trim();
            const regex = /^[A-Za-z\s]+$/;
            const spaceCount = (value.match(/ /g) || []).length;
            
            if (value && !regex.test(value)) {
                nameInput.classList.add('is-invalid');
                nameError.textContent = 'Name must contain only letters and spaces';
                nameError.style.display = 'block';
            } else if (spaceCount > 10) {
                nameInput.classList.add('is-invalid');
                nameError.textContent = 'Name can contain maximum 10 spaces';
                nameError.style.display = 'block';
            }
        });
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const nameValue = nameInput.value.trim();
            const regex = /^[A-Za-z\s]+$/;
            const spaceCount = (nameValue.match(/ /g) || []).length;
            
            if (nameValue && (!regex.test(nameValue) || spaceCount > 10)) {
                e.preventDefault();
                nameInput.classList.add('is-invalid');
                if (!regex.test(nameValue)) {
                    nameError.textContent = 'Name must contain only letters and spaces';
                } else {
                    nameError.textContent = 'Name can contain maximum 10 spaces';
                }
                nameError.style.display = 'block';
                nameInput.focus();
                return false;
            }
        });
        
        // Preview image before upload
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add image preview here if needed
                    console.log('Image selected:', file.name);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
