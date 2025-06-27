<!DOCTYPE html>
<html>
<head>
    <title>Media Gallery</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .card-img-top { max-height: 200px; object-fit: cover; }
        .card { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Media Gallery</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="media">Upload Media</label>
                <input type="file" name="media" class="form-control" accept="image/jpeg,image/png,video/mp4" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload to Telegram</button>
        </form>

        <h2>Uploaded Media</h2>
        <div class="row">
            @foreach ($media as $item)
                <div class="col-md-4">
                    <div class="card">
                        @if ($item->media_type === 'image')
                            <img src="{{ $item->media_url }}" class="card-img-top" alt="{{ $item->title }}">
                        @elseif ($item->media_type === 'video')
                            <video controls class="card-img-top">
                                <source src="{{ $item->media_url }}" type="video/mp4">
                            </video>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                            <a href="{{ route('telegram.send', $item->id) }}" class="btn btn-info">Send to Telegram</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>