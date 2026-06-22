@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-secondary-bg relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-accent-blue/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-md w-full text-center relative z-10">
        <div class="w-24 h-24 rounded-3xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-5xl mx-auto mb-8 shadow-lg">
            <i class="fas fa-server"></i>
        </div>
        
        <h1 class="text-7xl font-black text-text-main mb-2 tracking-tighter">500</h1>
        <h2 class="text-2xl font-bold text-text-main mb-4">Server Error</h2>
        
        <p class="text-text-dark/70 mb-8 max-w-sm mx-auto">
            Oops, something went wrong on our servers. We are looking into it and will have it fixed as soon as possible.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.history.back()" class="inline-flex items-center justify-center gap-2 bg-card-bg border border-card-border text-text-main px-6 py-3.5 rounded-xl font-semibold hover:border-text-dark/30 transition-all">
                <i class="fas fa-arrow-left"></i> Go Back
            </button>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 bg-accent-blue text-white px-6 py-3.5 rounded-xl font-semibold shadow-glow-blue hover:bg-accent-blue-hover transition-all hover:-translate-y-0.5">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
