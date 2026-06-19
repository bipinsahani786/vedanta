@extends('layouts.app')
@section('content')
<div class="pt-32 pb-12 px-6 lg:px-[5%] text-center border-b border-card-border bg-card-bg/30">
    <h4 class="text-accent-blue text-sm font-bold mb-3 uppercase tracking-wider">News & Insights</h4>
    <h1 class="text-4xl md:text-5xl font-extrabold text-text-main mb-6">Media Center</h1>
    <p class="text-text-main opacity-70 max-w-2xl mx-auto">Stay updated with the latest trends in education recruitment and news from Vedanta Placement Agency.</p>
</div>

<div class="py-16 px-6 lg:px-[5%]">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Article 1 -->
        <article class="bg-card-bg border border-card-border rounded-2xl overflow-hidden shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group reveal">
            <div class="h-48 overflow-hidden relative">
                <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Education" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-accent-blue text-white text-xs font-bold px-3 py-1 rounded-full">Industry Trends</div>
            </div>
            <div class="p-6">
                <p class="text-xs text-text-main opacity-50 mb-2"><i class="far fa-calendar-alt mr-1"></i> Oct 15, 2023</p>
                <h3 class="text-xl font-bold text-text-main mb-3 leading-tight group-hover:text-accent-blue transition-colors">The Future of Digital Classrooms in India</h3>
                <p class="text-sm text-text-main opacity-70 mb-4 line-clamp-3">Explore how technology is reshaping the educational landscape and what teachers need to know to stay ahead.</p>
                <a href="#" class="text-accent-blue font-bold text-sm flex items-center gap-2">Read Article <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i></a>
            </div>
        </article>

        <!-- Article 2 -->
        <article class="bg-card-bg border border-card-border rounded-2xl overflow-hidden shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group reveal reveal-delay-1">
            <div class="h-48 overflow-hidden relative">
                <img src="https://images.unsplash.com/photo-1573164574572-cb89e39749b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Meeting" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-accent-yellow text-[#031b4e] text-xs font-bold px-3 py-1 rounded-full">Company News</div>
            </div>
            <div class="p-6">
                <p class="text-xs text-text-main opacity-50 mb-2"><i class="far fa-calendar-alt mr-1"></i> Sep 28, 2023</p>
                <h3 class="text-xl font-bold text-text-main mb-3 leading-tight group-hover:text-accent-blue transition-colors">Vedanta Agency Partners with 50+ New Schools</h3>
                <p class="text-sm text-text-main opacity-70 mb-4 line-clamp-3">We are thrilled to announce our recent partnerships that will open hundreds of new opportunities for educators.</p>
                <a href="#" class="text-accent-blue font-bold text-sm flex items-center gap-2">Read Article <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i></a>
            </div>
        </article>

        <!-- Article 3 -->
        <article class="bg-card-bg border border-card-border rounded-2xl overflow-hidden shadow-lg hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group reveal reveal-delay-2">
            <div class="h-48 overflow-hidden relative">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Resume" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 left-4 bg-accent-blue text-white text-xs font-bold px-3 py-1 rounded-full">Career Advice</div>
            </div>
            <div class="p-6">
                <p class="text-xs text-text-main opacity-50 mb-2"><i class="far fa-calendar-alt mr-1"></i> Sep 10, 2023</p>
                <h3 class="text-xl font-bold text-text-main mb-3 leading-tight group-hover:text-accent-blue transition-colors">5 Resume Tips for Educators</h3>
                <p class="text-sm text-text-main opacity-70 mb-4 line-clamp-3">Make your application stand out to top institutions with these simple yet highly effective resume tweaks.</p>
                <a href="#" class="text-accent-blue font-bold text-sm flex items-center gap-2">Read Article <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i></a>
            </div>
        </article>
    </div>
</div>
@endsection