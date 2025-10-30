@extends('frontend.layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-6">Login</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded" required>
        <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
    </form>
</div>
@endsection
