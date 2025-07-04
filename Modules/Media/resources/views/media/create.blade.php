@extends('media::components.layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Upload Media File</h1>
                <a href="{{ route('media.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Library
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6><i class="bi bi-exclamation-triangle"></i> Please fix the following errors:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" id="mediaForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <strong>Title</strong> <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="title"
                                name="title" 
                                class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title') }}" 
                                placeholder="Enter a descriptive title for your file"
                                maxlength="255"
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This will be shown as the caption in Telegram</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <strong>Description</strong> <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea 
                                id="description"
                                name="description" 
                                class="form-control @error('description') is-invalid @enderror" 
                                rows="3"
                                placeholder="Add any additional details about this file..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="media_type" class="form-label">
                                <strong>File Type</strong> <span class="text-danger">*</span>
                            </label>
                            <select 
                                id="media_type"
                                name="media_type" 
                                class="form-select @error('media_type') is-invalid @enderror" 
                                required
                            >
                                <option value="">Choose file type...</option>
                                <option value="image" {{ old('media_type') == 'image' ? 'selected' : '' }}>
                                    📸 Image (JPG, PNG, WEBP)
                                </option>
                                <option value="video" {{ old('media_type') == 'video' ? 'selected' : '' }}>
                                    🎥 Video (MP4, AVI, MOV)
                                </option>
                                <option value="document" {{ old('media_type') == 'document' ? 'selected' : '' }}>
                                    📄 Document (PDF, DOC, DOCX, TXT)
                                </option>
                            </select>
                            @error('media_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="file" class="form-label">
                                <strong>Choose File</strong> <span class="text-danger">*</span>
                            </label>
                            
                            <!-- Upload Area -->
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <h5>Drop your file here</h5>
                                    <p class="text-muted">or click to browse</p>
                                </div>
                                <input 
                                    type="file" 
                                    id="file"
                                    name="file" 
                                    class="form-control d-none @error('file') is-invalid @enderror" 
                                    accept="image/*,video/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.txt"
                                    required
                                >
                            </div>
                            
                            <!-- File Info -->
                            <div id="fileInfo" class="mt-3 d-none">
                                <div class="alert alert-info">
                                    <strong id="fileName"></strong>
                                    <br>
                                    <small class="text-muted">
                                        Size: <span id="fileSize"></span> | 
                                        Type: <span id="fileType"></span>
                                    </small>
                                </div>
                                <video id="videoPreview" class="img-fluid rounded d-none" controls style="max-height: 200px;"></video>
                                <img id="imagePreview" class="img-fluid rounded d-none" style="max-height: 200px;">
                            </div>
                            
                            @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Maximum file size: 50MB | High quality maintained during upload
                            </small>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div id="progressContainer" class="mb-3 d-none">
                            <div class="progress">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">
                                    <span id="progressText">0%</span>
                                </div>
                            </div>
                            <small class="text-muted">
                                <span id="uploadStatus">Uploading to Telegram...</span>
                                <span id="uploadSpeed" class="ms-2"></span>
                            </small>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('media.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-cloud-upload"></i> Upload to Telegram
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #0d6efd;
    background-color: #e7f3ff;
}

.upload-area.dragover {
    border-color: #198754;
    background-color: #d1e7dd;
}

.upload-icon {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.upload-content h5 {
    margin-bottom: 0.5rem;
    color: #495057;
}

.upload-content p {
    margin-bottom: 0;
}

.progress {
    height: 25px;
}

.progress-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

#progressText {
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileType = document.getElementById('fileType');
    const videoPreview = document.getElementById('videoPreview');
    const imagePreview = document.getElementById('imagePreview');
    const mediaTypeSelect = document.getElementById('media_type');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadSpeed = document.getElementById('uploadSpeed');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('mediaForm');

    let uploadStartTime = 0;
    let lastLoaded = 0;
    let lastTime = 0;

    // Click to upload
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileType.textContent = file.type || 'Unknown';
        fileInfo.classList.remove('d-none');
        
        // Reset previews
        videoPreview.classList.add('d-none');
        imagePreview.classList.add('d-none');
        
        // Auto-detect media type and show preview
        if (file.type.startsWith('image/')) {
            if (!mediaTypeSelect.value) mediaTypeSelect.value = 'image';
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            if (!mediaTypeSelect.value) mediaTypeSelect.value = 'video';
            const url = URL.createObjectURL(file);
            videoPreview.src = url;
            videoPreview.classList.remove('d-none');
            videoPreview.onload = () => URL.revokeObjectURL(url);
        } else {
            if (!mediaTypeSelect.value) mediaTypeSelect.value = 'document';
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function formatSpeed(bytesPerSecond) {
        if (bytesPerSecond === 0) return '0 B/s';
        const k = 1024;
        const sizes = ['B/s', 'KB/s', 'MB/s', 'GB/s'];
        const i = Math.floor(Math.log(bytesPerSecond) / Math.log(k));
        return parseFloat((bytesPerSecond / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function calculateETA(loaded, total, speed) {
        if (speed === 0) return 'Calculating...';
        const remaining = total - loaded;
        const seconds = Math.round(remaining / speed);
        
        if (seconds < 60) return `${seconds}s remaining`;
        if (seconds < 3600) return `${Math.round(seconds / 60)}m remaining`;
        return `${Math.round(seconds / 3600)}h remaining`;
    }

    // Form submission with enhanced progress tracking
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        
        // Show progress
        progressContainer.classList.remove('d-none');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Uploading...';
        
        uploadStartTime = Date.now();
        lastLoaded = 0;
        lastTime = uploadStartTime;
        
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const currentTime = Date.now();
                const percentComplete = (e.loaded / e.total) * 100;
                
                // Update progress bar
                progressBar.style.width = percentComplete + '%';
                progressText.textContent = Math.round(percentComplete) + '%';
                
                // Calculate speed
                const timeDiff = currentTime - lastTime;
                const loadedDiff = e.loaded - lastLoaded;
                
                if (timeDiff > 1000) { // Update every second
                    const speed = loadedDiff / (timeDiff / 1000);
                    uploadSpeed.textContent = formatSpeed(speed);
                    
                    // Calculate ETA
                    const eta = calculateETA(e.loaded, e.total, speed);
                    uploadStatus.textContent = `Uploading... ${eta}`;
                    
                    lastLoaded = e.loaded;
                    lastTime = currentTime;
                }
            }
        });
        
        xhr.onloadstart = function() {
            uploadStatus.textContent = 'Starting upload...';
        };
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                uploadStatus.textContent = 'Upload completed!';
                progressText.textContent = '100%';
                setTimeout(() => {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.href = "{{ route('media.index') }}";
                        }
                    } catch (e) {
                        window.location.href = "{{ route('media.index') }}";
                    }
                }, 1000);
            } else {
                handleError('Upload failed. Please try again.');
            }
        };
        
        xhr.onerror = function() {
            handleError('Network error. Please check your connection and try again.');
        };
        
        xhr.ontimeout = function() {
            handleError('Upload timeout. Please try again with a smaller file.');
        };
        
        function handleError(message) {
            progressContainer.classList.add('d-none');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-cloud-upload"></i> Upload to Telegram';
            alert(message);
        }
        
        xhr.open('POST', form.action);
        xhr.timeout = 600000; // 10 minutes timeout
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
        xhr.send(formData);
    });
});
</script>
@endsection