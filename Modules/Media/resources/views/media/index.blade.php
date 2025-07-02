@extends('media::components.layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-collection"></i> Media Library</h1>
        <a href="{{ route('media.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Upload New File
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
        @forelse($media as $mediaItem)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm position-relative">
                    @if($mediaItem->media_type === 'image' && $mediaItem->telegram_file_path)
                        <img 
                            src="https://api.telegram.org/file/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/{{ $mediaItem->telegram_file_path }}?t={{ time() }}" 
                            class="card-img-top" 
                            alt="{{ $mediaItem->title }}"
                            style="height: 200px; object-fit: cover; background-color: #f8f9fa;"
                            loading="lazy"
                            decoding="async"
                            fetchpriority="high"
                            onerror="this.src='{{ asset('images/placeholder.jpg') }}'; console.log('Image failed to load: {{ $mediaItem->telegram_file_path }}')"
                        >
                        <span class="badge bg-success position-absolute top-0 end-0 m-2">
                            <i class="bi bi-image"></i> Image
                        </span>
                    @elseif($mediaItem->media_type === 'video' && $mediaItem->telegram_file_path)
                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                            <video class="w-100 h-100" style="object-fit: cover;" controls preload="metadata" poster="{{ asset('images/video-placeholder.jpg') }}">
                                <source src="https://api.telegram.org/file/bot7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE/{{ $mediaItem->telegram_file_path }}?t={{ time() }}" type="video/mp4">
                                <div class="text-white text-center">Video not supported</div>
                            </video>
                        </div>
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                            <i class="bi bi-play-circle"></i> Video
                        </span>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <div class="text-center text-muted">
                                <i class="bi bi-file-earmark fs-1"></i>
                                <div class="mt-2">Document</div>
                            </div>
                        </div>
                        <span class="badge bg-info position-absolute top-0 end-0 m-2">
                            <i class="bi bi-file-text"></i> Doc
                        </span>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ Str::limit($mediaItem->title, 50) }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($mediaItem->description ?: 'No description provided.', 80) }}
                        </p>
                        <small class="text-muted mb-3">
                            <i class="bi bi-calendar"></i> {{ $mediaItem->created_at->format('M j, Y') }}
                        </small>
                        
                        <div class="d-flex gap-1">
                            <a href="{{ route('media.show', $mediaItem->id) }}" 
                               class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('media.edit', $mediaItem->id) }}" 
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('media.destroy', $mediaItem->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($mediaItem->title) }}\'?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h3 class="mt-3 text-muted">No media files yet</h3>
                    <p class="text-muted">Start by uploading your first file to the media library.</p>
                    <a href="{{ route('media.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Upload Your First File
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection