@extends('layouts.admin')

@section('title', 'Job Query Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.jobs.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Jobs
        </a>
        <h2 class="text-xl font-bold text-gray-800">Review Job Post</h2>
    </div>
    
    <div>
        @if($job->status === 'pending')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                <i class="fas fa-clock mr-1.5 text-xs"></i> Pending Review
            </span>
        @elseif($job->status === 'approved')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                <i class="fas fa-check-circle mr-1.5 text-xs"></i> Approved
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                <i class="fas fa-times-circle mr-1.5 text-xs"></i> Rejected
            </span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Job Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Job Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Job Title</div>
                    <div class="text-gray-800 font-medium">{{ $job->title ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Salary Range</div>
                    <div class="text-gray-800 font-medium">{{ $job->salary_range ?? 'Not specified' }}</div>
                </div>
                
                <div class="col-span-2 mt-2">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-2">Required Criteria</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-blue-50 text-blue-700 text-sm border border-blue-100">
                            <i class="fas fa-folder-open mr-1.5 text-blue-400"></i> {{ $job->category->name }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-purple-50 text-purple-700 text-sm border border-purple-100">
                            <i class="fas fa-book mr-1.5 text-purple-400"></i> {{ $job->subject->name }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-orange-50 text-orange-700 text-sm border border-orange-100">
                            <i class="fas fa-graduation-cap mr-1.5 text-orange-400"></i> {{ $job->qualification->name }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-green-50 text-green-700 text-sm border border-green-100">
                            <i class="fas fa-map-marker-alt mr-1.5 text-green-400"></i> {{ $job->location->city }}, {{ $job->location->state }}
                        </span>
                    </div>
                </div>

                <div class="col-span-2 mt-4">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-2">Description</div>
                    <div class="text-gray-700 bg-gray-50 p-4 rounded-lg text-sm border border-gray-100">
                        {!! nl2br(e($job->description ?? 'No description provided.')) !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: Employer & Actions -->
    <div class="space-y-6">
        
        <!-- Employer Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Employer Information</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Institution Name</div>
                    <div class="text-gray-800 font-bold flex items-center">
                        <i class="fas fa-building text-gray-400 mr-2"></i> {{ $job->school_name }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Contact Person</div>
                    <div class="text-gray-800 flex items-center">
                        <i class="fas fa-user text-gray-400 mr-2"></i> {{ $job->contact_person }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Email</div>
                    <div class="text-gray-800 flex items-center">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i> 
                        <a href="mailto:{{ $job->email }}" class="text-blue-600 hover:underline">{{ $job->email }}</a>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Phone</div>
                    <div class="text-gray-800 flex items-center">
                        <i class="fas fa-phone-alt text-gray-400 mr-2"></i> 
                        <a href="tel:{{ $job->phone }}" class="text-blue-600 hover:underline">{{ $job->phone }}</a>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-gray-100 mt-2">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Account Status</div>
                    @if($job->user_id)
                        <div class="flex items-center text-green-600 font-medium text-sm">
                            <i class="fas fa-check-circle mr-1.5"></i> Registered Employer
                        </div>
                    @else
                        <div class="flex items-center text-orange-500 font-medium text-sm">
                            <i class="fas fa-exclamation-triangle mr-1.5"></i> Guest Submission
                        </div>
                        <div class="text-xs text-gray-500 mt-1">No employer account linked to this query.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if($job->status === 'pending')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Action Panel</h3>
            
            <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="mb-3">
                @csrf
                @if(!$job->user_id)
                    <div class="mb-4 bg-blue-50 border border-blue-100 p-3 rounded-lg flex items-start">
                        <div class="flex items-center h-5">
                            <input id="create_account" name="create_account" type="checkbox" value="1" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        </div>
                        <label for="create_account" class="ml-2 text-sm font-medium text-blue-800">
                            Create an Employer Account for this user and send login details via email.
                        </label>
                    </div>
                @endif
                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="fas fa-check mr-2"></i> Approve Job Post
                </button>
            </form>

            <form action="{{ route('admin.jobs.reject', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this query?');">
                @csrf
                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-times mr-2"></i> Reject
                </button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
