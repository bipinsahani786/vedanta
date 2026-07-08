@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('subtitle', 'Welcome back! Here\'s what\'s happening today.')

@section('actions')
    <a href="{{ route('admin.jobs.index', ['status' => 'pending']) }}" class="px-5 py-2.5 bg-[#00a8e8] text-white hover:bg-[#008ecc] rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
        <i class="fas fa-clipboard-check text-xs"></i>
        <span>Review Pending Jobs</span>
    </a>
@endsection

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Candidates Stat -->
    <a href="{{ route('admin.crm.index') }}" class="block bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md hover:border-blue-200 hover:-translate-y-1 transition-all group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Candidates</p>
                <h3 class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ number_format($totalCandidates) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-[#00a8e8] flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs">
            <span class="text-emerald-600 font-medium flex items-center gap-1 bg-emerald-50 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-up text-[10px]"></i> 12%</span>
            <span class="text-gray-400 ml-2">vs last month</span>
        </div>
    </a>

    <!-- Active Jobs Stat -->
    <a href="{{ route('admin.jobs.index', ['status' => 'active']) }}" class="block bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md hover:border-emerald-200 hover:-translate-y-1 transition-all group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Active Jobs</p>
                <h3 class="text-3xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ number_format($activeJobs) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs">
            <span class="text-emerald-600 font-medium flex items-center gap-1 bg-emerald-50 px-2 py-0.5 rounded-md"><i class="fas fa-arrow-up text-[10px]"></i> 5%</span>
            <span class="text-gray-400 ml-2">vs last month</span>
        </div>
    </a>

    <!-- Pending Queries Stat -->
    <a href="{{ route('admin.jobs.index', ['status' => 'pending']) }}" class="block bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md hover:border-amber-200 hover:-translate-y-1 transition-all group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pending Jobs</p>
                <h3 class="text-3xl font-bold text-gray-900 group-hover:text-amber-600 transition-colors">{{ number_format($pendingJobs->count()) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs">
            <span class="text-rose-600 font-medium flex items-center gap-1 bg-rose-50 px-2 py-0.5 rounded-md">Requires Action</span>
        </div>
    </a>

    <!-- Revenue Stat -->
    <a href="{{ route('admin.transactions.index') }}" class="block bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md hover:border-purple-200 hover:-translate-y-1 transition-all group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Revenue</p>
                <h3 class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">₹{{ number_format($totalCollections) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-gray-500 justify-between w-full">
            <span>Reg: ₹{{ number_format($registrationRevenue) }}</span>
            <span>Services: ₹{{ number_format($serviceChargeRevenue) }}</span>
        </div>
    </a>
</div>

{{-- Main Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 space-y-8">
        {{-- Recent Applications Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-lg">Recent Applications</h3>
                <a href="{{ route('admin.crm.index') }}" class="text-sm font-semibold text-[#00a8e8] hover:text-[#008ecc] flex items-center gap-1 transition-colors">
                    View All <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto flex-1">
                @php
                    $recentApps = \App\Models\JobApplication::with(['candidate', 'jobPost'])->orderBy('created_at', 'desc')->take(5)->get();
                @endphp
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">Candidate</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">Job Applied For</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">Date</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentApps as $app)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $app->candidate->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $app->candidate->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $app->jobPost->title ?? 'Teacher' }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $app->jobPost->school_name ?? 'School' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">{{ $app->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                @if($app->status === 'applied')
                                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold border border-blue-100">Applied</span>
                                @elseif($app->status === 'hired')
                                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-100">Hired</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold border border-gray-200">Waitlisted</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 text-2xl mx-auto mb-4">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <p class="text-gray-500 text-sm">No recent applications found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Revenue Details --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h3 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
                <i class="fas fa-chart-pie text-[#00a8e8]"></i> Revenue Analytics
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="p-5 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition-colors">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Collected</p>
                    <h4 class="text-2xl font-bold text-gray-900 mb-3">₹{{ number_format($totalCollections) }}</h4>
                    <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full w-[80%] rounded-full"></div>
                    </div>
                </div>
                
                <div class="p-5 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition-colors">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pending Collections</p>
                    <h4 class="text-2xl font-bold text-rose-500 mb-3">₹{{ number_format($pendingCollections) }}</h4>
                    <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-rose-500 h-full w-[30%] rounded-full"></div>
                    </div>
                </div>

                <div class="p-5 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition-colors">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Placements</p>
                    <h4 class="text-2xl font-bold text-[#00a8e8] mb-3">{{ number_format($placements) }}</h4>
                    <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-[#00a8e8] h-full w-[60%] rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="flex flex-col gap-8">
        
        {{-- System Status --}}
        <div class="bg-gray-900 rounded-2xl p-6 shadow-md relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-3 mb-6 relative z-10">
                <div class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </div>
                <h3 class="font-bold text-white text-lg tracking-tight">System Status</h3>
            </div>
            
            <div class="space-y-4 relative z-10">
                <div class="flex justify-between items-center py-2 border-b border-gray-800">
                    <span class="text-gray-400 text-sm">App Version</span>
                    <span class="text-gray-100 font-semibold text-sm">2.0.1</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-800">
                    <span class="text-gray-400 text-sm">Laravel</span>
                    <span class="text-gray-100 font-semibold text-sm">v{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400 text-sm">PHP Version</span>
                    <span class="text-gray-100 font-semibold text-sm">{{ phpversion() }}</span>
                </div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-gray-800 relative z-10">
                <p class="text-xs text-gray-500 italic text-center">"The Gold Standard in Education Recruitment"</p>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h3 class="font-bold text-gray-800 text-lg mb-5">Quick Links</h3>
            
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.categories.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 hover:bg-[#00a8e8]/5 border border-transparent hover:border-[#00a8e8]/20 transition-all text-center group">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-layer-group text-gray-400 group-hover:text-[#00a8e8] transition-colors"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Categories</span>
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 hover:bg-amber-500/5 border border-transparent hover:border-amber-500/20 transition-all text-center group">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-book text-gray-400 group-hover:text-amber-500 transition-colors"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Subjects</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 hover:bg-emerald-500/5 border border-transparent hover:border-emerald-500/20 transition-all text-center group">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-concierge-bell text-gray-400 group-hover:text-emerald-500 transition-colors"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Services</span>
                </a>
                <a href="{{ route('home') }}" target="_blank" class="flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 hover:bg-purple-500/5 border border-transparent hover:border-purple-500/20 transition-all text-center group">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-external-link-alt text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">View Site</span>
                </a>
            </div>
        </div>
        
    </div>
</div>
@endsection
