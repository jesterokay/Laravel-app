@extends('media::components.layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Edit Media Information</h1>
                <a href="{{ route('media.show', $media->id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to View
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('media.update', $media->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT')

                        <div class="mb-4 text-center">
                            <p class="text-muted">Current File Preview:</p>
                            @if($media->media_type === 'image' && $media->telegram_file_path)
                                <img 
                                    src="https://api.telegram.org/file/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/{{ $media->telegram_file_path }}?t={{ time() }}" 
                                    alt="{{ $media->title }}"
                                    class="img-thumbnail"
                                    style="max-height: 150px;"
                                    loading="lazy"
                                    decoding="async"
                                    fetchpriority="high"
                                    onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                >
                            @elseif($media->media_type === 'video' && $media->telegram_file_path)
                                <video 
                                    controls 
                                    class="d-inline-block rounded" 
                                    style="width: 150px; max-height: 100px; object-fit: cover;"
                                    preload="auto"
                                    poster="{{ asset('images/video-placeholder.jpg') }}"
                                >
                                    <source src="https://api.telegram.org/file/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/{{ $media->telegram_file_path }}?t={{ time() }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <div class="bg-light d-inline-flex align-items-center justify-content-center rounded" 
                                     style="width: 150px; height: 100px;">
                                    <i class="bi bi-file-earmark fs-3 text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <strong>Title</strong> <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="title"
                                name="title" 
                                class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title', $media->title) }}" 
                                placeholder="Enter a descriptive title"
                                maxlength="255"
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <strong>Description</strong> <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea 
                                id="description"
                                name="description" 
                                class="form-control @error('description') is-invalid @enderror" 
                                rows="4"
                                placeholder="Add any additional details..."
                            >{{ old('description', $media->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="media_type" class="form-label">
                                <strong>File Type</strong> <span class="text-danger">*</span>
                            </label>
                            <select 
                                id="media_type"
                                name="media_type" 
                                class="form-select @error('media_type') is-invalid @enderror" 
                                required
                            >
                                <option value="image" {{ (old('media_type', $media->media_type) == 'image') ? 'selected' : '' }}>
                                    📸 Image (JPG, PNG)
                                </option>
                                <option value="video" {{ (old('media_type', $media->media_type) == 'video') ? 'selected' : '' }}>
                                    🎥 Video (MP4, max 50MB)
                                </option>
                                <option value="document" {{ (old('media_type', $media->media_type) == 'document') ? 'selected' : '' }}>
                                    📄 Document (PDF, DOC, DOCX)
                                </option>
                            </select>
                            @error('media_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">
                                <strong>Replace File</strong> <span class="text-muted">(Optional)</span>
                            </label>
                            <input 
                                type="file" 
                                id="file"
                                name="file" 
                                class="form-control @error('file') is-invalid @enderror" 
                                accept="image/jpeg,image/png,video/mp4,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                            >
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Uploading a new file will replace the current one. Maximum file size: 50MB | Supported formats: JPG, PNG, MP4, PDF, DOC, DOCX
                            </small>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('media.show', $media->id) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection