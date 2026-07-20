@extends('layouts.admin')

@section('title', 'Manage Lead: ' . $lead->name)
@section('subtitle', 'View contact details and manage follow-ups')

@section('actions')
    <a href="{{ route('admin.leads.index') }}" class="text-sm text-text-dark/60 hover:text-accent-blue transition-colors flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Back to Leads
    </a>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Lead Details -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Status Card -->
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4">
                @if($lead->status === 'new')
                    <span class="bg-red-500/10 text-red-400 px-3 py-1.5 rounded-lg text-xs font-bold border border-red-500/20 uppercase tracking-wider flex items-center gap-1.5 w-max">
                        <i class="fas fa-star text-[10px]"></i> New
                    </span>
                @elseif($lead->status === 'contacted')
                    <span class="bg-accent-yellow/10 text-accent-yellow px-3 py-1.5 rounded-lg text-xs font-bold border border-accent-yellow/20 uppercase tracking-wider flex items-center gap-1.5 w-max">
                        <i class="fas fa-reply text-[10px]"></i> Contacted
                    </span>
                @else
                    <span class="bg-green-500/10 text-green-400 px-3 py-1.5 rounded-lg text-xs font-bold border border-green-500/20 uppercase tracking-wider flex items-center gap-1.5 w-max">
                        <i class="fas fa-check-double text-[10px]"></i> Closed
                    </span>
                @endif
            </div>

            <div class="w-16 h-16 rounded-full bg-accent-blue/10 flex items-center justify-center text-accent-blue text-2xl font-extrabold mb-4 border-4 border-white shadow-sm">
                {{ strtoupper(substr($lead->name, 0, 1)) }}
            </div>
            
            <h2 class="text-xl font-bold text-text-main">{{ $lead->name }}</h2>
            <div class="text-sm text-text-dark/60 mt-1 mb-4">Received {{ $lead->created_at->format('M d, Y') }}</div>

            <div class="space-y-3 pt-4 border-t border-card-border">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-secondary-bg flex items-center justify-center text-text-dark/40 shrink-0">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-text-dark/40 uppercase tracking-wider mb-0.5">Email</div>
                        <a href="mailto:{{ $lead->email }}" class="text-sm font-semibold text-text-main hover:text-accent-blue transition-colors">{{ $lead->email }}</a>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-secondary-bg flex items-center justify-center text-text-dark/40 shrink-0">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-text-dark/40 uppercase tracking-wider mb-0.5">Phone</div>
                        <a href="tel:{{ $lead->phone }}" class="text-sm font-semibold text-text-main hover:text-accent-blue transition-colors">{{ $lead->phone }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Original Message Card -->
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm">
            <h3 class="text-sm font-bold text-text-dark/60 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fas fa-comment-alt text-accent-blue/50"></i> Original Query
            </h3>
            <div class="mb-3">
                <div class="text-[10px] font-bold text-text-dark/40 uppercase tracking-wider mb-1">Subject</div>
                <div class="text-sm font-bold text-text-main">{{ $lead->subject }}</div>
            </div>
            <div>
                <div class="text-[10px] font-bold text-text-dark/40 uppercase tracking-wider mb-1">Message</div>
                <div class="text-sm text-text-dark/80 bg-secondary-bg/50 p-4 rounded-xl border border-card-border leading-relaxed whitespace-pre-wrap">{{ $lead->message }}</div>
            </div>
        </div>
    </div>

    <!-- Right Column: Follow-ups -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-600 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl text-sm font-semibold">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Log Follow-up Form -->
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm">
            <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                <i class="fas fa-plus-circle text-accent-blue"></i> Log New Follow-up
            </h3>
            
            <form action="{{ route('admin.leads.followup.store', $lead->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-text-dark/60 uppercase tracking-wider mb-2">Remarks / Notes <span class="text-red-500">*</span></label>
                    <textarea name="notes" rows="3" required placeholder="E.g., Called the client, they requested a callback tomorrow..." 
                              class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-3 text-sm text-text-main focus:border-accent-blue focus:outline-none transition-colors"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-text-dark/60 uppercase tracking-wider mb-2">Next Follow-up Date (Optional)</label>
                        <input type="date" name="follow_up_date" min="{{ date('Y-m-d') }}"
                               class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2.5 text-sm text-text-main focus:border-accent-blue focus:outline-none transition-colors">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-text-dark/60 uppercase tracking-wider mb-2">Update Status To</label>
                        <select name="status" class="w-full bg-secondary-bg border border-card-border rounded-xl px-4 py-2.5 text-sm text-text-main focus:border-accent-blue focus:outline-none transition-colors">
                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-accent-blue text-white rounded-xl px-6 py-2.5 text-sm font-bold shadow-lg shadow-accent-blue/30 hover:bg-accent-blue-hover transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Follow-up
                    </button>
                </div>
            </form>
        </div>

        <!-- Follow-up History Timeline -->
        <div class="bg-card-bg rounded-2xl border border-card-border p-6 shadow-sm">
            <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                <i class="fas fa-history text-text-dark/40"></i> Follow-up History
            </h3>
            
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-card-border before:to-transparent">
                @forelse($lead->followUps()->latest()->get() as $followup)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-card-bg bg-accent-blue text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <i class="fas fa-headset text-sm"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border border-card-border bg-secondary-bg/30 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold text-text-main text-sm">{{ $followup->admin->name ?? 'Admin' }}</div>
                                <time class="text-[10px] font-bold text-text-dark/40">{{ $followup->created_at->format('M d, Y h:i A') }}</time>
                            </div>
                            <div class="text-sm text-text-dark/80 whitespace-pre-wrap leading-relaxed">{{ $followup->notes }}</div>
                            
                            @if($followup->follow_up_date)
                                <div class="mt-3 pt-3 border-t border-card-border/50 flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-text-dark/50">Next Contact:</span>
                                    <span class="text-xs font-bold text-accent-blue bg-accent-blue/10 px-2 py-0.5 rounded-md">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($followup->follow_up_date)->format('M d, Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 relative z-10">
                        <div class="w-12 h-12 bg-secondary-bg rounded-full flex items-center justify-center text-text-dark/20 mx-auto mb-3 border border-card-border">
                            <i class="fas fa-comment-slash"></i>
                        </div>
                        <p class="text-text-dark/50 text-sm font-medium">No follow-ups recorded yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection
