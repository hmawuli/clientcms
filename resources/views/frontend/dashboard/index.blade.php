@extends('frontend.layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2 class="text-3xl font-bold mb-4">Dashboard</h2>
<p class="text-gray-700">Welcome, {{ auth()->user()->name }}!</p>
@endsection
