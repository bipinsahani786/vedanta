@extends('layouts.app')

@section('title', $service->title . ' - Vedanta Placement Agency')

@section('content')
<!-- Page Header -->
<div class="relative bg-[#031b4e] py-24 px-6 lg:px-[5%] overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[40%] h-[120%] bg-gradient-to-l from-[#129aef]/20 to-transparent transform -skew-x-12"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[30%] h-[50%] bg-gradient-to-t from-[#ffb800]/10 to-transparent rounded-tr-full"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto text-center text-white reveal">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#129aef]/20 text-[#ffb800] text-4xl mb-6 shadow-[0_0_30px_rgba(18,154,239,0.3)]">
            <i class="{{ $service->icon }}"></i>
        </div>
        <h1 class="text-4xl lg:text-6xl font-extrabold mb-4">{{ $service->title }}</h1>
        <p class="text-lg text-white/80 max-w-2xl mx-auto">{{ $service->description }}</p>
    </div>
</div>

<!-- Service Content Section -->
<div class="py-20 px-6 lg:px-[5%] bg-slate-50 relative">
    <div class="max-w-6xl mx-auto bg-white p-10 lg:p-14 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.05)] border border-slate-100 reveal">
        <div class="text-slate-700">
            {!! $service->content !!}
        </div>
        
        <div class="mt-14 flex justify-center">
            <a href="{{ route('contact') }}" class="bg-[#129aef] hover:bg-[#0f80c6] text-white font-bold py-4 px-10 rounded-full shadow-[0_8px_20px_rgba(18,154,239,0.3)] hover:shadow-[0_12px_25px_rgba(18,154,239,0.4)] transition-all duration-300 transform hover:-translate-y-1">
                Contact Us About This Service
            </a>
        </div>
    </div>
</div>
@endsection
