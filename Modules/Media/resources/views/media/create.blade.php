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
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
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
                                    📸 Image (JPG, PNG)
                                </option>
                                <option value="video" {{ old('media_type') == 'video' ? 'selected' : '' }}>
                                    🎥 Video (MP4)
                                </option>
                                <option value="document" {{ old('media_type') == 'document' ? 'selected' : '' }}>
                                    📄 Document (PDF, DOC, DOCX)
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
                            <input 
                                type="file" 
                                id="file"
                                name="file" 
                                class="form-control @error('file') is-invalid @enderror" 
                                accept="image/jpeg,image/png,video/mp4,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                required
                            >
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Maximum file size: 2GB | Supported formats: JPG, PNG, MP4, PDF, DOC, DOCX
                            </small>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('media.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload"></i> Upload File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
