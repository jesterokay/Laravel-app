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
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            <img 
                                src="https://api.telegram.org/file/bot{{ config('telegram.bot_token', '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE') }}/{{ $mediaItem->telegram_file_path }}?t={{ time() }}" 
                                class="card-img-top w-100 h-100" 
                                alt="{{ $mediaItem->title }}"
                                style="object-fit: cover; background-color: #f8f9fa;"
                                loading="lazy"
                                decoding="async"
                                onerror="this.parentNode.innerHTML='<div class=\'bg-light d-flex align-items-center justify-content-center h-100\'><div class=\'text-center text-muted\'><i class=\'bi bi-image-alt fs-1\'></i><div class=\'mt-2\'>Image not available</div></div></div>'"
                            >
                        </div>
                        <span class="badge bg-success position-absolute top-0 end-0 m-2">
                            <i class="bi bi-image"></i> Image
                        </span>
                    @elseif($mediaItem->media_type === 'video' && $mediaItem->telegram_file_path)
                        <div class="bg-dark d-flex align-items-center justify-content-center position-relative" style="height: 200px;">
                            <video 
                                class="w-100 h-100" 
                                style="object-fit: cover;" 
                                preload="metadata"
                                muted
                                data-video-id="{{ $mediaItem->id }}"
                                poster="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Crect width='100%25' height='100%25' fill='%23000'/%3E%3C/svg%3E"
                                onloadedmetadata="this.currentTime = 1"
                                onerror="showVideoFallback({{ $mediaItem->id }})"
                            >
                                <source src="https://api.telegram.org/file/bot{{ config('telegram.bot_token', '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE') }}/{{ $mediaItem->telegram_file_path }}?t={{ time() }}" type="video/mp4">
                                <div class="text-white text-center">
                                    <i class="bi bi-play-circle fs-1"></i>
                                    <div class="mt-2">Video not supported</div>
                                </div>
                            </video>
                            <!-- Play button overlay -->
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <div class="bg-dark bg-opacity-75 rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-play-fill text-white fs-3"></i>
                                </div>
                            </div>
                            <!-- Fallback for video load errors -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark d-none align-items-center justify-content-center" id="video-fallback-{{ $mediaItem->id }}">
                                <div class="text-center text-white">
                                    <i class="bi bi-play-circle fs-1"></i>
                                    <div class="mt-2">Video preview unavailable</div>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                            <i class="bi bi-play-circle"></i> Video
                        </span>
                    @elseif($mediaItem->media_type === 'document' || $mediaItem->media_type === 'audio')
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <div class="text-center text-muted">
                                @if($mediaItem->media_type === 'document')
                                    @php
                                        $extension = strtolower(pathinfo($mediaItem->original_filename ?? '', PATHINFO_EXTENSION));
                                        $iconClass = match($extension) {
                                            'pdf' => 'bi-file-pdf',
                                            'doc', 'docx' => 'bi-file-word',
                                            'xls', 'xlsx' => 'bi-file-excel',
                                            'ppt', 'pptx' => 'bi-file-ppt',
                                            'txt' => 'bi-file-text',
                                            'zip', 'rar' => 'bi-file-zip',
                                            default => 'bi-file-earmark'
                                        };
                                    @endphp
                                    <i class="bi {{ $iconClass }} fs-1"></i>
                                    <div class="mt-2">{{ strtoupper($extension ?: 'DOC') }}</div>
                                @else
                                    <i class="bi bi-music-note fs-1"></i>
                                    <div class="mt-2">Audio</div>
                                @endif
                                @if($mediaItem->file_size)
                                    <small class="text-muted">{{ number_format($mediaItem->file_size / 1024 / 1024, 2) }} MB</small>
                                @endif
                            </div>
                        </div>
                        <span class="badge bg-info position-absolute top-0 end-0 m-2">
                            <i class="bi {{ $mediaItem->media_type === 'document' ? 'bi-file-text' : 'bi-music-note' }}"></i> 
                            {{ ucfirst($mediaItem->media_type) }}
                        </span>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <div class="text-center text-muted">
                                <i class="bi bi-question-circle fs-1"></i>
                                <div class="mt-2">Unknown Type</div>
                            </div>
                        </div>
                        <span class="badge bg-secondary position-absolute top-0 end-0 m-2">
                            <i class="bi bi-question"></i> Unknown
                        </span>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title" title="{{ $mediaItem->title }}">
                            {{ Str::limit($mediaItem->title, 50) }}
                        </h5>
                        <p class="card-text text-muted flex-grow-1" title="{{ $mediaItem->description }}">
                            {{ Str::limit($mediaItem->description ?: 'No description provided.', 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> {{ $mediaItem->created_at->format('M j, Y') }}
                            </small>
                            @if($mediaItem->file_size)
                                <small class="text-muted">
                                    <i class="bi bi-hdd"></i> {{ number_format($mediaItem->file_size / 1024, 0) }} KB
                                </small>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-1">
                            <a href="{{ route('media.show', $mediaItem->id) }}" 
                               class="btn btn-sm btn-outline-primary flex-fill"
                               title="View {{ $mediaItem->title }}">
                                <i class="bi bi-eye"></i> View
                            </a>
                            @if($mediaItem->telegram_message_id)
                                <a href="https://t.me/c/{{ substr(abs(-1002808159169), 4) }}/{{ $mediaItem->telegram_message_id }}?single" 
                                   class="btn btn-sm btn-outline-info"
                                   title="View in Telegram"
                                   target="_blank">
                                    <i class="bi bi-telegram"></i>
                                </a>
                            @endif
                            <a href="{{ route('media.edit', $mediaItem->id) }}" 
                               class="btn btn-sm btn-outline-warning"
                               title="Edit {{ $mediaItem->title }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('media.destroy', $mediaItem->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($mediaItem->title) }}\'?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete {{ $mediaItem->title }}">
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

<script>
// Handle video loading errors and preview generation
document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('video[data-video-id]');
    
    videos.forEach(video => {
        const videoId = video.getAttribute('data-video-id');
        
        // Handle video loading errors
        video.addEventListener('error', function() {
            showVideoFallback(videoId);
        });
        
        // Handle when video metadata is loaded (this will show first frame)
        video.addEventListener('loadedmetadata', function() {
            // Set to 1 second to get a frame (not 0 to avoid black frame)
            this.currentTime = Math.min(1, this.duration * 0.1);
        });
        
        // Handle seeking to the preview frame
        video.addEventListener('seeked', function() {
            // Video now shows the first frame as preview
            this.style.opacity = '1';
        });
        
        // Click handler to play video
        video.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.paused) {
                this.controls = true;
                this.currentTime = 0;
                this.play();
                // Hide play button overlay
                const overlay = this.nextElementSibling;
                if (overlay && overlay.classList.contains('position-absolute')) {
                    overlay.style.display = 'none';
                }
            }
        });
        
        // Reset when video ends
        video.addEventListener('ended', function() {
            this.controls = false;
            this.currentTime = Math.min(1, this.duration * 0.1);
            // Show play button overlay again
            const overlay = this.nextElementSibling;
            if (overlay && overlay.classList.contains('position-absolute')) {
                overlay.style.display = 'flex';
            }
        });
    });
});

function showVideoFallback(videoId) {
    const video = document.querySelector(`video[data-video-id="${videoId}"]`);
    const fallback = document.getElementById(`video-fallback-${videoId}`);
    
    if (video && fallback) {
        video.style.display = 'none';
        fallback.classList.remove('d-none');
        fallback.classList.add('d-flex');
        
        // Also hide the play button overlay
        const overlay = video.nextElementSibling;
        if (overlay && overlay.classList.contains('position-absolute')) {
            overlay.style.display = 'none';
        }
    }
}
</script>

@endsection