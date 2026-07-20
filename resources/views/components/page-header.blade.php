@props(['title', 'breadcrumbs' => [], 'image' => null])

<div class="relative pt-12 pb-12 lg:pt-16 lg:pb-16 px-6 lg:px-[5%] bg-gradient-to-r from-[#040e2d] via-[#129aef] to-[#040e2d] border-t border-white/10 overflow-hidden flex flex-col items-center justify-center text-center">
    <!-- Decorative Elements (Animated) -->
    <div class="absolute top-1/4 left-1/4 w-20 h-20 rounded-full border-[6px] border-white/10 z-0 animate-float-slow"></div>
    <div class="absolute top-1/3 right-1/4 w-32 h-32 opacity-10 z-0 animate-float" style="background-image: radial-gradient(#ffffff 2px, transparent 2px); background-size: 16px 16px;"></div>
    <div class="absolute bottom-1/4 right-1/3 w-40 h-40 bg-blue-400/20 rounded-full blur-3xl z-0 animate-pulse-soft"></div>

    <!-- Content -->
    <div class="relative z-10 w-full mb-2">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 drop-shadow-md">{{ $title }}</h1>
        
        <div class="flex items-center justify-center gap-2 text-sm font-semibold tracking-wider uppercase">
            @foreach($breadcrumbs as $label => $url)
                @if(!$loop->last || $url)
                    <a href="{{ $url }}" class="text-blue-200 hover:text-white transition-colors">{{ $label }}</a>
                    <span class="text-white/50">/</span>
                @else
                    <span class="text-white/90">{{ $label }}</span>
                @endif
            @endforeach
        </div>
    </div>
</div>
