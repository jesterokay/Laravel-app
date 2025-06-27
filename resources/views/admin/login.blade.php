<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <style>
        .wrapbody {
            border: 1px solid #ccc;
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 20px;
            margin-top: 20px;
        }
        #cooldown-timer {
            font-weight: bold;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <section class="wrapbody">
        <h3 align="center">Member's Login</h3>

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

        <p><a href="{{ route('login', ['ui' => 'auth']) }}" class="btn btn-secondary">Switch to User Login</a></p>
        <p style="color:red">{{ session()->get('status') }}</p>
        <form action="/login" method="POST">
            @csrf
            <label for="user">Username</label>
            <input type="text" name="username" placeholder="please input your username" class="form-control">
            <label for="pass">Password</label>
            <input type="password" name="password" placeholder="************" class="form-control">
            <div align="center" style="margin-top:20px;">
                <button type="submit" class="btn btn-danger">login</button>
            </div>
        </form>
    </section>
</body>
</html>
