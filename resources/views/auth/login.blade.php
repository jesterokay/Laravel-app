<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #cooldown-timer {
            font-weight: bold;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Login</h1>

        @if(session('too_many_attempts'))
            <div class="alert alert-danger text-center" id="cooldown-message">
                Too many attempts. Please wait <span id="cooldown-timer">{{ session('seconds_remaining') }}</span> seconds.
            </div>
            <script>
                let countdown = {{ session('seconds_remaining') }};
                const timerEl = document.getElementById('cooldown-timer');

                const interval = setInterval(() => {
                    countdown--;
                    let minutes = Math.floor(countdown / 60);
                    let seconds = countdown % 60;
                    timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                    if (countdown <= 0) {
                        clearInterval(interval);
                        document.getElementById('cooldown-message').textContent = "You can try logging in now.";
                    }
                }, 1000);
            </script>
        @endif

        <p><a href="{{ route('login', ['ui' => 'admin']) }}" class="btn btn-secondary">Switch to Admin and Superadmin Login</a></p>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}">
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
