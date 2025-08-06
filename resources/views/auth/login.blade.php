<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/tailwind.min.css') }}">
    <style>
        body {
            background-color: #e0e5ec;
        }
        .neumorphism-form {
            background: #e0e5ec;
            border-radius: 20px;
            box-shadow: 9px 9px 16px #a3b1c6, -9px -9px 16px #ffffff;
        }
        .neumorphism-input {
            background: #e0e5ec;
            border-radius: 10px;
            box-shadow: inset 5px 5px 10px #a3b1c6, inset -5px -5px 10px #ffffff;
            border: none;
        }
        .neumorphism-button {
            background: #e0e5ec;
            border-radius: 10px;
            box-shadow: 5px 5px 10px #a3b1c6, -5px -5px 10px #ffffff;
            border: none;
            transition: all 0.3s ease-in-out;
        }
        .neumorphism-button:hover {
            box-shadow: 3px 3px 6px #a3b1c6, -3px -3px 6px #ffffff;
        }
        .neumorphism-button:active {
            box-shadow: inset 5px 5px 10px #a3b1c6, inset -5px -5px 10px #ffffff;
        }
        #cooldown-timer {
            font-weight: bold;
            font-family: monospace;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-sm p-8 space-y-6 neumorphism-form sm:w-11/12">
        <h1 class="text-3xl font-bold text-center text-gray-700">LOGIN</h1>

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

        <p class="text-center"><a href="{{ route('login', ['ui' => 'admin']) }}" class="text-gray-600 hover:text-gray-800">Switch to Admin Login</a></p>

        @if ($errors->any())
            <div class="text-red-500 text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div class="relative">
                <i class="fas fa-user absolute top-3 left-4 text-gray-400"></i>
                <input type="text" name="username" id="username" class="w-full py-3 pl-12 pr-4 neumorphism-input text-gray-700 focus:outline-none" placeholder="Username" value="{{ old('username') }}">
            </div>
            <div class="relative">
                <i class="fas fa-lock absolute top-3 left-4 text-gray-400"></i>
                <input type="password" name="password" id="password" class="w-full py-3 pl-12 pr-12 neumorphism-input text-gray-700 focus:outline-none" placeholder="Password">
                <i class="fas fa-eye absolute top-3 right-4 text-gray-400 cursor-pointer" id="togglePassword"></i>
            </div>
            <button type="submit" class="w-full py-3 font-bold text-gray-700 neumorphism-button">LOGIN</button>
        </form>
    </div>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>