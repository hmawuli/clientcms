<nav class="bg-white shadow">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <a href="{{ url('/') }}" class="text-xl font-semibold text-blue-600">MyApp</a>

        <ul class="flex space-x-4">
            <li><a href="{{ url('/') }}" class="hover:text-blue-600">Home</a></li>
            <li><a href="{{ url('/about') }}" class="hover:text-blue-600">About</a></li>
            <li><a href="{{ url('/services') }}" class="hover:text-blue-600">Services</a></li>
            <li><a href="{{ url('/contact') }}" class="hover:text-blue-600">Contact</a></li>

            @auth
                <li><a href="{{ route('client.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-red-500">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="hover:text-blue-600">Login</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-blue-600">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>
