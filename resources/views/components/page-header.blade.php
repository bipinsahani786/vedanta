@props(['title', 'breadcrumbs' => [], 'image' => null])

<div class="relative pt-6 pb-6 lg:pt-8 lg:pb-8 px-6 lg:px-[5%] bg-gradient-to-r from-secondary-bg to-primary-bg border-t border-white/5 overflow-hidden flex flex-col md:flex-row items-center justify-between">
    <!-- Decorative Elements -->
    <div class="absolute top-1/4 left-1/3 w-16 h-16 rounded-full border-4 border-white/20 z-0"></div>
    <div class="absolute top-1/3 right-1/2 w-24 h-24 bg-dots-pattern opacity-20 z-0" style="background-image: radial-gradient(white 2px, transparent 2px); background-size: 15px 15px;"></div>

    <!-- Left Content -->
    <div class="relative z-10 w-full md:w-1/2 mb-6 md:mb-0 text-left">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-blue-900 mb-4">{{ $title }}</h1>
        
        <div class="flex items-center gap-2 text-sm font-semibold tracking-wider uppercase">
            @foreach($breadcrumbs as $label => $url)
                @if(!$loop->last || $url)
                    <a href="{{ $url }}" class="text-accent-blue hover:text-blue-900 transition-colors">{{ $label }}</a>
                    <span class="text-white/50">/</span>
                @else
                    <span class="text-white/80">{{ $label }}</span>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Right Content (Image) -->
    @if($image)
    <div class="relative z-10 w-full md:w-1/2 flex justify-end">
        <img src="{{ $image }}" alt="{{ $title }}" class="max-w-full h-auto max-h-[300px] object-contain drop-shadow-2xl">
    </div>
    @else
    <div class="relative z-10 w-full md:w-1/2 flex justify-end">
        <img src="images/pic2.png" alt="Placeholder" class="max-w-full h-auto max-h-[300px] object-cover rounded-2xl drop-shadow-2xl mix-blend-luminosity opacity-80">
    </div>
    @endif
</div>
