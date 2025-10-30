@extends('frontend.layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-6">Register</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Full Name" class="w-full border p-2 rounded" required>
        <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded" required>
        <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full border p-2 rounded" required>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Register</button>
    </form>
</div>
@endsection
