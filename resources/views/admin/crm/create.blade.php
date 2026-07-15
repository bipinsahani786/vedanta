@extends('layouts.admin')

@section('title', 'Add Candidate')
@section('subtitle', 'Register a new candidate offline.')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-card-bg rounded-2xl border border-card-border overflow-hidden shadow-xl reveal">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6 border-b border-card-border pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-accent-blue/10 text-accent-blue flex items-center justify-center text-lg">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h2 class="text-xl font-bold text-text-main">New Candidate Details</h2>
                </div>
                <a href="{{ route('admin.crm.index') }}" class="text-text-dark hover:text-text-main text-sm font-semibold transition-colors flex items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Back to CRM
                </a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 p-4 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        <div>
                            <ul class="text-sm text-red-300/80 list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.crm.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Full Name *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-user text-sm"></i></span>
                        <input id="name" name="name" type="text" required
                            class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                            placeholder="Candidate's full name" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Email Address *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-envelope text-sm"></i></span>
                            <input id="email" name="email" type="email" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="name@example.com" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Phone Number *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-phone-alt text-sm"></i></span>
                            <input id="phone" name="phone" type="text" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="+91-XXXXXXXXXX" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Password *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-lock text-sm"></i></span>
                            <input id="password" name="password" type="password" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-text-main/70 mb-2 uppercase tracking-wider">Confirm Password *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-dark/40"><i class="fas fa-shield-alt text-sm"></i></span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="w-full bg-secondary-bg border border-card-border rounded-xl pl-11 pr-4 py-3 text-sm text-text-main placeholder-text-dark/40 focus:outline-none focus:ring-2 focus:ring-accent-blue/50 focus:border-accent-blue transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-card-border text-right">
                    <a href="{{ route('admin.crm.index') }}" class="inline-block px-6 py-3.5 bg-secondary-bg hover:bg-white/5 text-text-main rounded-xl font-bold transition-colors mr-2">Cancel</a>
                    <button type="submit" class="px-8 py-3.5 bg-accent-blue text-white font-bold rounded-xl shadow-lg hover:bg-accent-blue-hover hover:-translate-y-0.5 transition-all">
                        Create Candidate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
