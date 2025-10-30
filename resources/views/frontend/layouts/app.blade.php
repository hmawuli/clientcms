<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans text-gray-900">
    <header class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold"><a href="{{ route('home') }}">My App</a></h1>
            <nav>
                <ul class="flex gap-4">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-600">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-blue-600">About</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-blue-600">Services</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-blue-600">Contact</a></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="hover:text-blue-600">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-blue-600">Register</a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="hover:text-blue-600">Logout</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-6">
        @yield('content')
    </main>

    <footer class="bg-white shadow-inner p-4 mt-12 text-center text-gray-600">
        &copy; {{ date('Y') }} My App. All rights reserved.
    </footer>
</body>
</html>
