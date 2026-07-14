@extends('layouts.app')

@section('title', 'Bus Partners - ECBUS')

@section('content')
<div class="pt-32 pb-20 max-w-7xl mx-auto px-6 min-h-[60vh] flex flex-col items-center justify-center text-center">
    <div class="bg-white p-12 rounded-3xl shadow-xl border border-gray-100 animate-fade-in-up">
        <i data-lucide="users" class="w-20 h-20 text-primary-gold mx-auto mb-6"></i>
        <h1 class="text-4xl font-extrabold text-dark-text mb-4">Our Partners</h1>
        <p class="text-gray-500 text-lg mb-8 max-w-lg mx-auto">This page is under construction. A detailed directory of our trusted transport partners will be available soon.</p>
        <a href="{{ route('home') }}" class="inline-block bg-primary-maroon text-white px-8 py-3 rounded-xl font-bold hover:bg-dark-maroon transition shadow-md">
            Return to Home
        </a>
    </div>
</div>
@endsection
