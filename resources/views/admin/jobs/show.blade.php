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
    
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-semibold transition-colors border border-blue-200">
            <i class="fas fa-edit mr-1"></i> Edit Job
        </a>
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
                            <i class="fas fa-folder-open mr-1.5 text-blue-400"></i> {{ $job->category?->name ?? 'N/A' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-purple-50 text-purple-700 text-sm border border-purple-100">
                            <i class="fas fa-book mr-1.5 text-purple-400"></i> {{ $job->subject?->name ?? 'N/A' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-orange-50 text-orange-700 text-sm border border-orange-100">
                            <i class="fas fa-graduation-cap mr-1.5 text-orange-400"></i> {{ $job->qualification?->name ?? 'N/A' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-green-50 text-green-700 text-sm border border-green-100">
                            <i class="fas fa-map-marker-alt mr-1.5 text-green-400"></i> {{ $job->city?->name ?? 'N/A' }}, {{ $job->state?->name ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <div class="col-span-2 mt-4">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-2">Description & Requirements</div>
                    <div class="text-gray-700 bg-gray-50/80 p-5 rounded-xl text-sm border border-gray-200/80 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-2 [&_ul]:my-3 [&_li]:text-gray-700 [&_li]:marker:text-blue-600 [&_p]:mb-3 [&_h4]:font-bold [&_h4]:text-gray-900 [&_h4]:text-xs [&_h4]:mt-4 [&_h4]:mb-2 [&_h4]:border-b [&_h4]:border-gray-200 [&_h4]:pb-1 [&_h4]:uppercase [&_h4]:tracking-wider">
                        @php
                            $rawDesc = $job->description ?? '';

                            if (empty(trim($rawDesc))) {
                                $formattedDescription = '<p class="text-gray-400 italic text-sm">No description provided.</p>';
                            } else {
                                $hasHtml = preg_match('/<[a-z][\s\S]*>/i', $rawDesc);

                                if ($hasHtml) {
                                    $formattedDescription = $rawDesc;
                                } else {
                                    $normalized = str_replace(['•', '·', '►', '▪', '⁃', '●'], '•', $rawDesc);

                                    $sectionKeywords = [
                                        'Key Responsibilities:', 'Responsibilities:', 'Job Responsibilities:',
                                        'Requirements:', 'Key Requirements:', 'Eligibility:', 'Qualifications:',
                                        'Job Details:', 'Job Overview:', 'Key Details:', 'About the Role:',
                                        'Job Type:', 'Location:', 'Salary:', 'Perks & Benefits:', 'Perks:'
                                    ];

                                    foreach ($sectionKeywords as $keyword) {
                                        $normalized = preg_replace('/(?i)' . preg_quote($keyword, '/') . '/', "\n\n<h4>" . trim($keyword, ':') . "</h4>\n", $normalized);
                                    }

                                    $normalized = preg_replace('/(?<!^|\n)•/', "\n•", $normalized);
                                    $rawLines = explode("\n", $normalized);
                                    $outputHtml = '';
                                    $inList = false;

                                    foreach ($rawLines as $line) {
                                        $trimmed = trim($line);
                                        if (empty($trimmed)) continue;

                                        if (strpos($trimmed, '<h4>') === 0) {
                                            if ($inList) {
                                                $outputHtml .= '</ul>';
                                                $inList = false;
                                            }
                                            $outputHtml .= $trimmed;
                                        }
                                        elseif (strpos($trimmed, '•') === 0 || strpos($trimmed, '-') === 0 || strpos($trimmed, '*') === 0) {
                                            if (!$inList) {
                                                $outputHtml .= '<ul>';
                                                $inList = true;
                                            }
                                            $cleanItem = e(trim(ltrim($trimmed, '•-* ')));
                                            $outputHtml .= '<li>' . $cleanItem . '</li>';
                                        }
                                        else {
                                            if ($inList) {
                                                $outputHtml .= '</ul>';
                                                $inList = false;
                                            }
                                            $outputHtml .= '<p>' . e($trimmed) . '</p>';
                                        }
                                    }

                                    if ($inList) {
                                        $outputHtml .= '</ul>';
                                    }

                                    $formattedDescription = $outputHtml;
                                }
                            }
                        @endphp
                        {!! $formattedDescription !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Suggested Candidates (AI Match) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-magic text-blue-500 mr-2"></i> Suggested Candidates</h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">AI Match Engine</span>
            </div>
            
            @if(isset($suggestedCandidates) && $suggestedCandidates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($suggestedCandidates as $candidate)
                        <div class="flex flex-col p-4 border border-gray-100 rounded-xl hover:border-blue-200 hover:shadow-md transition-all bg-gray-50/30">
                            <div class="flex justify-between items-start mb-3">
                                <a href="{{ route('admin.crm.show', $candidate->id) }}" class="font-bold text-gray-800 hover:text-blue-600 transition-colors text-base">{{ $candidate->name }}</a>
                                <span class="bg-green-100 border border-green-200 text-green-800 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">{{ $candidate->match_score }}% Match</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 text-xs text-gray-600 mb-4 bg-white p-3 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-1.5" title="Category">
                                    <i class="fas fa-folder w-3.5 text-purple-400"></i> 
                                    <span class="truncate font-medium">{{ $candidate->profile?->category?->name ?? 'N/A' }}</span>
                                    @if(in_array('category', $candidate->matched_criteria ?? []))
                                        <i class="fas fa-check-circle text-green-500 text-[10px]" title="Matched Category"></i>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5" title="Subject">
                                    <i class="fas fa-book w-3.5 text-blue-400"></i> 
                                    <span class="truncate font-medium">{{ $candidate->profile?->subject?->name ?? 'N/A' }}</span>
                                    @if(in_array('subject', $candidate->matched_criteria ?? []))
                                        <i class="fas fa-check-circle text-green-500 text-[10px]" title="Matched Subject"></i>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5" title="Qualification">
                                    <i class="fas fa-graduation-cap w-3.5 text-orange-400"></i> 
                                    <span class="truncate">{{ $candidate->profile?->highestQualification?->name ?? 'N/A' }}</span>
                                    @if(in_array('qualification', $candidate->matched_criteria ?? []))
                                        <i class="fas fa-check-circle text-green-500 text-[10px]" title="Matched Qualification"></i>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5" title="Location">
                                    <i class="fas fa-map-marker-alt w-3.5 text-red-400"></i> 
                                    <span class="truncate">{{ $candidate->profile?->preferredCity?->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-auto pt-2">
                                <a href="{{ route('admin.crm.show', $candidate->id) }}" class="block w-full text-center py-2 bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 rounded-lg text-xs font-semibold text-gray-700 transition-colors shadow-sm">
                                    View Full Profile
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-gray-500 flex flex-col items-center justify-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-3 text-xl">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <p class="font-medium">No matches found</p>
                    <p class="text-xs mt-1 text-gray-400">There are no highly matching candidates for this job yet.</p>
                </div>
            @endif
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
        </div>
        @endif

        <!-- Communication Panel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Communications</h3>
            <button type="button" onclick="openMessageModal()" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i> Send Notification
            </button>
        </div>



    </div>
</div>

<!-- Send Message Modal -->
<div id="sendMessageModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true" onclick="closeMessageModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl border border-gray-200 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative z-10">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-paper-plane text-indigo-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Send Job Notification
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Select candidates to notify about this job opening.</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.jobs.send-message', $job->id) }}" method="POST" id="sendMessageForm">
                @csrf
                <div class="px-4 py-5 sm:p-6 bg-gray-50/50">
                    <!-- Target Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Audience</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="audience-label relative flex cursor-pointer rounded-lg border border-gray-200 bg-white p-4 shadow-sm focus:outline-none transition-colors">
                                <input type="radio" name="audience" value="all" class="sr-only" onchange="toggleManualSelect()">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">All Candidates</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">Send to everyone</span>
                                    </span>
                                </span>
                                <i class="fas fa-check-circle absolute right-4 top-4 text-indigo-600 opacity-0 transition-opacity check-icon"></i>
                            </label>
                            
                            <label class="audience-label relative flex cursor-pointer rounded-lg border border-gray-200 bg-white p-4 shadow-sm focus:outline-none transition-colors">
                                <input type="radio" name="audience" value="matched" class="sr-only" checked onchange="toggleManualSelect()">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Matched Candidates</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">AI Suggested (Top 50)</span>
                                    </span>
                                </span>
                                <i class="fas fa-check-circle absolute right-4 top-4 text-indigo-600 opacity-0 transition-opacity check-icon"></i>
                            </label>
                            
                            <label class="audience-label relative flex cursor-pointer rounded-lg border border-gray-200 bg-white p-4 shadow-sm focus:outline-none transition-colors">
                                <input type="radio" name="audience" value="manual" class="sr-only" onchange="toggleManualSelect()">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Manual Select</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">Filter and choose</span>
                                    </span>
                                </span>
                                <i class="fas fa-check-circle absolute right-4 top-4 text-indigo-600 opacity-0 transition-opacity check-icon"></i>
                            </label>
                        </div>
                    </div>

                    <!-- Manual Selection Section -->
                    <div id="manualSelectSection" class="hidden space-y-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="text-sm font-bold text-gray-700 mb-3">Filters</h4>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <div>
                                    <select id="filterCategory" class="block w-full rounded-md border border-gray-300 py-2 px-3 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700">
                                        <option value="">Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select id="filterSubject" class="block w-full rounded-md border border-gray-300 py-2 px-3 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700">
                                        <option value="">Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select id="filterQualification" class="block w-full rounded-md border border-gray-300 py-2 px-3 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700">
                                        <option value="">Qualification</option>
                                        @foreach($qualifications as $qualification)
                                            <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select id="filterState" class="block w-full rounded-md border border-gray-300 py-2 px-3 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700">
                                        <option value="">State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="button" onclick="searchCandidates()" class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-100 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-h-64 overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAllCandidates" onchange="toggleAllCandidates(this)" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    </tr>
                                </thead>
                                <tbody id="candidatesTableBody" class="bg-white divide-y divide-gray-200 text-sm">
                                    <!-- Populated via AJAX -->
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Search for candidates using filters above.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button" onclick="submitNotificationForm()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Send Notifications
                    </button>
                    <button type="button" onclick="closeMessageModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openMessageModal() {
        document.getElementById('sendMessageModal').classList.remove('hidden');
        toggleManualSelect();
    }

    function closeMessageModal() {
        document.getElementById('sendMessageModal').classList.add('hidden');
    }

    function toggleManualSelect() {
        const manualSelectChecked = document.querySelector('input[name="audience"][value="manual"]').checked;
        const manualSelectSection = document.getElementById('manualSelectSection');
        
        updateRadioStyles();
        
        if (manualSelectChecked) {
            manualSelectSection.classList.remove('hidden');
            searchCandidates();
        } else {
            manualSelectSection.classList.add('hidden');
        }
    }

    function updateRadioStyles() {
        const labels = document.querySelectorAll('.audience-label');
        labels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            const icon = label.querySelector('.check-icon');
            
            if (radio.checked) {
                label.classList.remove('border-gray-200');
                label.classList.add('border-indigo-600', 'bg-indigo-50');
                icon.classList.remove('opacity-0');
                icon.classList.add('opacity-100');
            } else {
                label.classList.remove('border-indigo-600', 'bg-indigo-50');
                label.classList.add('border-gray-200');
                icon.classList.remove('opacity-100');
                icon.classList.add('opacity-0');
            }
        });
    }

    function searchCandidates() {
        const btn = document.querySelector('button[onclick="searchCandidates()"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        const params = new URLSearchParams({
            category_id: document.getElementById('filterCategory').value,
            subject_id: document.getElementById('filterSubject').value,
            qualification_id: document.getElementById('filterQualification').value,
            state_id: document.getElementById('filterState').value,
        });

        fetch(`{{ route('admin.jobs.candidates.search', $job->id) }}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('candidatesTableBody');
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No candidates found.</td></tr>';
                } else {
                    data.forEach(candidate => {
                        tbody.innerHTML += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="candidate_ids[]" value="${candidate.id}" class="candidate-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">${candidate.name}</div>
                                    <div class="text-xs text-gray-500">${candidate.email}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">${candidate.subject}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">${candidate.city}</td>
                            </tr>
                        `;
                    });
                }
            })
            .finally(() => {
                btn.innerHTML = 'Search';
            });
    }

    function toggleAllCandidates(source) {
        const checkboxes = document.querySelectorAll('.candidate-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = source.checked;
        });
    }

    function submitNotificationForm() {
        const form = document.getElementById('sendMessageForm');
        const audience = document.querySelector('input[name="audience"]:checked').value;
        
        if (audience === 'manual') {
            const checked = document.querySelectorAll('.candidate-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one candidate to send notifications.');
                return;
            }
        }
        
        form.submit();
    }
</script>
@endsection
