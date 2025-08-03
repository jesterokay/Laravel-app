<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Server Error</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 flex items-center justify-center min-h-screen text-gray-800 p-4">
    <div class="w-full max-w-lg text-center">
        <div class="bg-white rounded-2xl shadow-2xl p-10 transform transition-all duration-500 hover:scale-105">
            <div class="mb-8">
                <svg class="mx-auto h-32 w-32 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-red-600">500</h1>
            <h2 class="text-3xl md:text-4xl font-bold mt-4">Server Error</h2>
            <p class="mt-4 text-gray-600">We're sorry, something went wrong on our end. Please try again later.</p>
            
            <a href="{{ url('/') }}" class="inline-block mt-8 px-8 py-4 font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transform transition-transform duration-300 hover:scale-110">
                Go to Homepage
            </a>
        </div>
    </div>
</body>
</html>