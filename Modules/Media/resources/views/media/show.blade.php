@extends('media::components.layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $media->title }}</h1>
        <a href="{{ route('media.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Library
        </a>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6><i class="bi bi-exclamation-triangle"></i> Errors:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    @if($media->media_type === 'image' && $media->telegram_file_path)
                        <img 
                            src="https://api.telegram.org/file/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/{{ $media->telegram_file_path }}?t={{ time() }}" 
                            class="img-fluid w-100 rounded" 
                            alt="{{ $media->title }}"
                            style="max-height: 600px; object-fit: contain; background-color: #f8f9fa;"
                            loading="lazy"
                            decoding="async"
                            fetchpriority="high"
                            onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                        >
                    @elseif($media->media_type === 'video' && $media->telegram_file_path)
                        <video controls class="w-100 rounded" preload="auto" style="max-height: 600px;" poster="{{ asset('images/video-placeholder.jpg') }}">
                            <source src="https://api.telegram.org/file/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/{{ $media->telegram_file_path }}?t={{ time() }}" type="video/mp4">
                            <div class="p-4 text-center">
                                <i class="bi bi-exclamation-triangle"></i>
                                Your browser does not support the video format.
                            </div>
                        </video>
                    @else
                        <div class="bg-light text-center py-5 rounded">
                            <i class="bi bi-file-earmark display-4 text-muted"></i>
                            <h4 class="mt-3">Document File</h4>
                            <p class="text-muted">Click the download button below to view this document.</p>
                            <a href="https://api.telegram.org/file/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/{{ $media->telegram_file_path }}" 
                               target="_blank" 
                               class="btn btn-primary">
                                <i class="bi bi-download"></i> Download Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> File Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @if($media->media_type === 'image')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-image"></i> Image File
                            </span>
                        @elseif($media->media_type === 'video')
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-play-circle"></i> Video File
                            </span>
                        @else
                            <span class="badge bg-info fs-6">
                                <i class="bi bi-file-text"></i> Document File
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6>Description:</h6>
                        <p class="text-muted">
                            {{ $media->description ?: 'No description provided.' }}
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Uploaded:</h6>
                        <p class="text-muted">
                            <i class="bi bi-calendar"></i> {{ $media->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('media.edit', $media->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Information
                        </a>
                        
                        <form action="{{ route('media.destroy', $media->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($media->title) }}\'? This action cannot be undone.')">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> Delete File
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection