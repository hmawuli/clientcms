@extends('frontend.layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Our Services</h1>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ([
        ['Web Development', 'Custom Laravel, PHP, and React apps.'],
        ['Mobile Apps', 'Cross-platform Flutter and native solutions.'],
        ['UI/UX Design', 'Intuitive interfaces that delight users.'],
        ['API Integration', 'Seamless backend-frontend communication.'],
        ['Cloud Hosting', 'Reliable deployment and scaling services.'],
        ['Maintenance', 'Ongoing optimization and security updates.'],
    ] as [$title, $desc])
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h2 class="text-xl font-semibold mb-2 text-blue-600">{{ $title }}</h2>
            <p class="text-gray-700">{{ $desc }}</p>
        </div>
    @endforeach
</div>
@endsection
