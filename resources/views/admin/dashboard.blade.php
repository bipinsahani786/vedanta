@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('subtitle', 'Welcome back! Here\'s what\'s happening today.')

@section('actions')
    <a href="{{ route('admin.jobs.index', ['status' => 'pending']) }}" class="px-4 py-2 bg-accent-blue/10 text-accent-blue hover:bg-accent-blue hover:text-white rounded-xl text-sm font-semibold transition-all flex items-center gap-2">
        <i class="fas fa-clipboard-check text-xs"></i>
        <span>Review Pending Jobs</span>
    </a>
@endsection

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Candidates Stat -->
    <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-accent-blue/30 transition-all">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent-blue/5 rounded-full blur-xl group-hover:bg-accent-blue/10 transition-all"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-text-dark/50 uppercase tracking-widest mb-1">Candidates</p>
                <h3 class="text-3xl font-extrabold text-text-main">{{ number_format(\App\Models\CandidateProfile::count() ?? 1245) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs">
            <span class="text-green-400 font-bold flex items-center gap-1 bg-green-400/10 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-up text-[10px]"></i> 12%</span>
            <span class="text-text-dark/40 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Active Jobs Stat -->
    <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-green-500/30 transition-all">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-500/5 rounded-full blur-xl group-hover:bg-green-500/10 transition-all"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-text-dark/50 uppercase tracking-widest mb-1">Active Jobs</p>
                <h3 class="text-3xl font-extrabold text-text-main">{{ number_format(\App\Models\JobPost::where('status', 'approved')->count() ?? 84) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs">
            <span class="text-green-400 font-bold flex items-center gap-1 bg-green-400/10 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-up text-[10px]"></i> 5%</span>
            <span class="text-text-dark/40 ml-2">vs last month</span>
        </div>
    </div>

    <!-- Pending Queries Stat -->
    <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-accent-yellow/30 transition-all">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-accent-yellow/5 rounded-full blur-xl group-hover:bg-accent-yellow/10 transition-all"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-text-dark/50 uppercase tracking-widest mb-1">Pending Jobs</p>
                <h3 class="text-3xl font-extrabold text-text-main">{{ number_format(\App\Models\JobPost::where('status', 'pending')->count() ?? 12) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent-yellow/10 text-accent-yellow flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs">
            <span class="text-red-400 font-bold flex items-center gap-1 bg-red-400/10 px-2 py-0.5 rounded-md">Requires Action</span>
        </div>
    </div>

    <!-- Revenue Stat -->
    <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl relative overflow-hidden group hover:border-purple-500/30 transition-all">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/5 rounded-full blur-xl group-hover:bg-purple-500/10 transition-all"></div>
        <div class="flex items-start justify-between relative z-10">
            <div>
                <p class="text-xs font-bold text-text-dark/50 uppercase tracking-widest mb-1">Paid Registrations</p>
                <h3 class="text-3xl font-extrabold text-text-main">{{ number_format(\App\Models\CandidateProfile::where('is_fee_paid', true)->count() ?? 342) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-text-dark/40">
            Total active premium users
        </div>
    </div>
</div>

{{-- Main Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Recent Applications Table --}}
    <div class="lg:col-span-2 bg-card-bg rounded-2xl border border-card-border shadow-xl overflow-hidden flex flex-col">
        <div class="p-6 border-b border-card-border flex justify-between items-center bg-secondary-bg/30">
            <h3 class="font-bold text-text-main text-lg">Recent Applications</h3>
            <a href="{{ route('admin.crm.index') }}" class="text-sm font-semibold text-accent-blue hover:underline flex items-center gap-1">
                View All <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto flex-1">
            @php
                $recentApps = \App\Models\JobApplication::with(['candidate', 'jobPost'])->orderBy('created_at', 'desc')->take(5)->get();
            @endphp
            <table class="w-full text-left border-collapse admin-table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job Applied For</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-card-border">
                    @forelse($recentApps as $app)
                    <tr>
                        <td>
                            <div class="font-semibold text-text-main">{{ $app->candidate->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-text-dark/40">{{ $app->candidate->email ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="font-medium text-text-main">{{ $app->jobPost->title ?? 'Teacher' }}</div>
                            <div class="text-xs text-text-dark/40">{{ $app->jobPost->school_name ?? 'School' }}</div>
                        </td>
                        <td class="text-text-dark/60 text-sm">{{ $app->created_at->diffForHumans() }}</td>
                        <td>
                            @if($app->status === 'applied')
                                <span class="bg-accent-blue/10 text-accent-blue px-2.5 py-1 rounded-lg text-xs font-bold border border-accent-blue/20">Applied</span>
                            @elseif($app->status === 'hired')
                                <span class="bg-green-500/10 text-green-400 px-2.5 py-1 rounded-lg text-xs font-bold border border-green-500/20">Hired</span>
                            @else
                                <span class="bg-card-border/50 text-text-dark/60 px-2.5 py-1 rounded-lg text-xs font-bold border border-card-border">Waitlisted</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center">
                            <div class="w-12 h-12 bg-secondary-bg rounded-xl flex items-center justify-center text-text-dark/20 text-xl mx-auto mb-3">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <p class="text-text-dark/40 text-sm">No recent applications found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions & System Info --}}
    <div class="flex flex-col gap-6">
        
        {{-- System Status --}}
        <div class="bg-gradient-to-br from-[#031b4e] to-[#0a286c] rounded-2xl p-6 shadow-xl relative overflow-hidden border border-white/10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-accent-blue/20 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-3 mb-6 relative z-10">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-[0_0_8px_#4ade80]"></div>
                <h3 class="font-bold text-white text-lg tracking-tight">System Status</h3>
            </div>
            
            <div class="space-y-4 relative z-10">
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm">App Version</span>
                    <span class="text-white font-semibold text-sm">2.0.1</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm">Laravel</span>
                    <span class="text-white font-semibold text-sm">v{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm">PHP Version</span>
                    <span class="text-white font-semibold text-sm">{{ phpversion() }}</span>
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-white/10 relative z-10">
                <p class="text-xs text-white/40 italic">"The Gold Standard in Education Recruitment"</p>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-xl">
            <h3 class="font-bold text-text-main text-lg mb-5">Quick Links</h3>
            
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.categories.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-secondary-bg hover:bg-accent-blue/5 border border-transparent hover:border-accent-blue/20 transition-all text-center group">
                    <i class="fas fa-layer-group text-text-dark/40 group-hover:text-accent-blue mb-2 text-xl transition-colors"></i>
                    <span class="text-xs font-semibold text-text-main">Categories</span>
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-secondary-bg hover:bg-accent-yellow/5 border border-transparent hover:border-accent-yellow/20 transition-all text-center group">
                    <i class="fas fa-book text-text-dark/40 group-hover:text-accent-yellow mb-2 text-xl transition-colors"></i>
                    <span class="text-xs font-semibold text-text-main">Subjects</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-secondary-bg hover:bg-green-500/5 border border-transparent hover:border-green-500/20 transition-all text-center group">
                    <i class="fas fa-concierge-bell text-text-dark/40 group-hover:text-green-500 mb-2 text-xl transition-colors"></i>
                    <span class="text-xs font-semibold text-text-main">Services</span>
                </a>
                <a href="{{ route('home') }}" target="_blank" class="flex flex-col items-center justify-center p-4 rounded-xl bg-secondary-bg hover:bg-purple-500/5 border border-transparent hover:border-purple-500/20 transition-all text-center group">
                    <i class="fas fa-external-link-alt text-text-dark/40 group-hover:text-purple-400 mb-2 text-xl transition-colors"></i>
                    <span class="text-xs font-semibold text-text-main">View Site</span>
                </a>
            </div>
        </div>
        
    </div>
</div>
@endsection
