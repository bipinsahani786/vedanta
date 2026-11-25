@extends('layouts.app')

@section('title', 'Job Alert Preferences | Vedanta Placement Agency®')

@section('content')
<section class="py-20 px-4 sm:px-6 lg:px-[5%] bg-slate-50 min-h-[65vh] flex items-center justify-center">
    <div class="max-w-lg w-full bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-lg text-center">
        @if(! $found)
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl mx-auto mb-5">
                <i class="fas fa-link-slash"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-[#040e2d] mb-3">Subscription Link Not Found</h1>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                This unsubscribe link is invalid or has already been replaced. If you need help managing your job alerts, please contact
                <a href="mailto:info@vedantaplacementagency.in" class="text-[#129aef] font-bold hover:underline">info@vedantaplacementagency.in</a>.
            </p>
            <a href="{{ route('jobs') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#031b4e] hover:bg-[#129aef] text-white font-bold text-sm transition-colors">
                Browse Latest Jobs <i class="fas fa-arrow-right text-xs"></i>
            </a>
        @elseif($resubscribed)
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-5">
                <i class="fas fa-bell"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-[#040e2d] mb-3">Subscription Reactivated</h1>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                <strong>{{ $subscriber->email }}</strong> will continue receiving free daily job updates when new teaching and school vacancies are published.
            </p>
            <a href="{{ route('jobs') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#129aef] hover:bg-[#031b4e] text-white font-bold text-sm transition-colors">
                Explore Current Vacancies <i class="fas fa-arrow-right text-xs"></i>
            </a>
        @else
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-2xl mx-auto mb-5">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-[#040e2d] mb-3">You Have Been Unsubscribed</h1>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                <strong>{{ $subscriber->email }}</strong> has been removed from our Daily Job Updates email alerts. You will not receive further job alert emails unless you subscribe again.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <form action="{{ route('job-alerts.resubscribe', $subscriber->unsubscribe_token) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors cursor-pointer">
                        Undo / Re-subscribe Free
                    </button>
                </form>
                <a href="{{ route('jobs') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-colors">
                    Browse Jobs
                </a>
            </div>
            <p class="text-xs text-slate-400 mt-6 mb-0">
                Questions? Contact <a href="mailto:info@vedantaplacementagency.in" class="text-[#129aef] hover:underline">info@vedantaplacementagency.in</a>
            </p>
        @endif
    </div>
</section>
@endsection
