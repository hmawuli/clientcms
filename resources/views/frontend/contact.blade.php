@extends('frontend.layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4">Contact Us</h2>

    <form action="#" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Your Name" class="w-full border p-2 rounded">
        <input type="email" name="email" placeholder="Your Email" class="w-full border p-2 rounded">
        <textarea name="message" rows="4" placeholder="Your Message" class="w-full border p-2 rounded"></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send</button>
    </form>
</div>
@endsection
