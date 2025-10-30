@extends('frontend.layouts.app')

@section('title', 'Register')

@section('content')
<h2 class="text-3xl font-bold mb-4">Register</h2>
<form method="POST" action="{{ route('register') }}" class="max-w-md">
    @csrf
    <input type="text" name="name" placeholder="Name" class="block w-full p-2 mb-2 border rounded">
    <input type="email" name="email" placeholder="Email" class="block w-full p-2 mb-2 border rounded">
    <input type="password" name="password" placeholder="Password" class="block w-full p-2 mb-2 border rounded">
    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="block w-full p-2 mb-2 border rounded">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Register</button>
</form>
@endsection
