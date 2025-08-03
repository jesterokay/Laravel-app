<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
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
                <svg class="mx-auto h-32 w-32 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-indigo-600">404</h1>
            <h2 class="text-3xl md:text-4xl font-bold mt-4">Page Not Found</h2>
            <p class="mt-4 text-gray-600">The page you are looking for does not exist. It might have been moved or deleted.</p>
            
            <div class="mt-8">
                <form action="{{ url('/search') }}" method="GET" class="flex items-center justify-center">
                    <input type="text" name="q" placeholder="Search the site..." class="w-full max-w-xs px-4 py-3 text-gray-700 bg-gray-100 border-2 border-gray-200 rounded-l-lg focus:outline-none focus:border-indigo-500 focus:bg-white transition-all duration-300">
                    <button type="submit" class="px-6 py-3 font-semibold text-white bg-indigo-600 rounded-r-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-300">
                        Search
                    </button>
                </form>
            </div>

            <a href="{{ url('/') }}" class="inline-block mt-8 px-8 py-4 font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transform transition-transform duration-300 hover:scale-110">
                Go to Homepage
            </a>
        </div>
    </div>
</body>
</html>