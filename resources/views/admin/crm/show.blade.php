@extends('layouts.admin')

@section('title')
    CRM: {{ $candidate->name }}
    @if($candidate->profile && $candidate->profile->is_verified)
        <i class="fas fa-check-circle text-blue-500 text-base" title="Verified Candidate"></i>
    @endif
@endsection

@section('actions')
    <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
        <a href="{{ route('admin.crm.index') }}" class="text-sm text-gray-600 hover:underline shrink-0">&larr; Back to List</a>
        
        <form action="{{ route('admin.crm.candidate.verify', $candidate->id) }}" method="POST" class="inline">
            @csrf
            @if($candidate->profile && $candidate->profile->is_verified)
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-xl hover:bg-red-200 transition-colors flex items-center shadow-sm">
                    <i class="fas fa-times-circle mr-2"></i> Remove Verification
                </button>
            @else
                <button type="submit" class="px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-xl hover:bg-green-200 transition-colors flex items-center shadow-sm">
                    <i class="fas fa-check-circle mr-2"></i> Verify Profile
                </button>
            @endif
        </form>

        <a href="{{ route('admin.crm.edit', $candidate->id) }}" class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-200 transition-colors flex items-center shadow-sm">
            <i class="fas fa-edit mr-2"></i> Edit Profile
        </a>
        
        <a href="{{ route('admin.crm.candidate.magic-login', $candidate->id) }}" target="_blank" class="px-4 py-2 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-200 transition-colors flex items-center shadow-sm">
            <i class="fas fa-sign-in-alt mr-2"></i> Login as Candidate
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Profile & Applications -->
    <div class="lg:col-span-1 space-y-6 min-w-0">
        <!-- Candidate Profile -->
        <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 mb-6 overflow-hidden">
            <!-- Banner / Header -->
            <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50/50 to-white flex flex-col sm:flex-row justify-between items-start relative gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 relative z-10 w-full">
                    <div class="w-16 h-16 shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-extrabold shadow-sm border-4 border-white">
                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">{{ $candidate->name }}</h3>
                        <div class="text-xs text-gray-500 mt-1.5 flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                            <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-gray-400"></i> {{ $candidate->email }}</span>
                            <span class="flex items-center gap-1.5"><i class="fas fa-phone-alt text-gray-400"></i> {{ $candidate->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if($candidate->profile)
                    <!-- Personal Info -->
                    <div class="mb-6">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Personal Details</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Gender</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->gender ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Date of Birth</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->date_of_birth ? $candidate->profile->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 col-span-2">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Address</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->address ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Info -->
                    <div class="mb-6">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Professional Details</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-50">
                                <div class="text-[10px] text-blue-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-folder"></i> Category</div>
                                <div class="text-sm font-bold text-blue-900">{{ $candidate->profile->category?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-50">
                                <div class="text-[10px] text-blue-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-book"></i> Subject</div>
                                <div class="text-sm font-bold text-blue-900">{{ $candidate->profile->subject?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-orange-50/50 p-3 rounded-xl border border-orange-50">
                                <div class="text-[10px] text-orange-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-graduation-cap"></i> Qualification</div>
                                <div class="text-sm font-bold text-orange-900">{{ $candidate->profile->highestQualification?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-50">
                                <div class="text-[10px] text-emerald-500 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-briefcase"></i> Experience</div>
                                <div class="text-sm font-bold text-emerald-900">{{ $candidate->profile->experience_years ?? 0 }} Years</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Current Salary</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->current_salary ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Expected Salary</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->expected_salary ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 col-span-2">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5 flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> Preferred Location</div>
                                <div class="text-sm font-medium text-gray-800">{{ $candidate->profile->preferredCity?->name ?? 'N/A' }}, {{ $candidate->profile->preferredState?->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Plan & Transactions -->
                    <div class="mb-6">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Plan & Transaction</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-1">Status</div>
                                @if($candidate->profile->is_fee_paid)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1 text-[10px]"></i> Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1 text-[10px]"></i> Pending</span>
                                @endif
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                                <div class="text-[10px] text-gray-400 uppercase font-bold mb-1">Plan Details</div>
                                <div class="text-sm font-bold text-gray-800">{{ $candidate->profile->plan_type ?? 'N/A' }}</div>
                            </div>
                            <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 col-span-2 flex justify-between items-center">
                                <div>
                                    <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Transaction ID</div>
                                    <div class="font-mono text-xs font-bold text-gray-600">{{ $candidate->profile->payment_id ?? 'No Transaction' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold mb-0.5">Paid On</div>
                                    <div class="text-xs font-medium text-gray-600">{{ $candidate->profile->registration_completed_at ? $candidate->profile->registration_completed_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="mb-6">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Documents</h4>
                        <div class="flex flex-wrap gap-2">
                            @if($candidate->profile->resume_path)
                                <a href="{{ Storage::url($candidate->profile->resume_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 text-indigo-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-file-pdf"></i> Resume
                                </a>
                            @endif
                            @if($candidate->profile->salary_slip_path)
                                <a href="{{ Storage::url($candidate->profile->salary_slip_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-file-invoice-dollar"></i> Salary Slip
                                </a>
                            @endif
                            @if($candidate->profile->offer_letter_path)
                                <a href="{{ Storage::url($candidate->profile->offer_letter_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-purple-50 hover:bg-purple-100 border border-purple-100 text-purple-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-file-contract"></i> Offer Letter
                                </a>
                            @endif
                            @if($candidate->profile->profile_photo_path)
                                <a href="{{ Storage::url($candidate->profile->profile_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-amber-50 hover:bg-amber-100 border border-amber-100 text-amber-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-image"></i> Profile Photo
                                </a>
                            @endif
                            @if($candidate->profile->live_photo_path)
                                <a href="{{ Storage::url($candidate->profile->live_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-700 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-camera"></i> Live Photo
                                </a>
                            @endif
                             @if($candidate->profile->agreement_pdf_path)
                                 <a href="{{ Storage::url($candidate->profile->agreement_pdf_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-teal-50 hover:bg-teal-100 border border-teal-100 text-teal-700 rounded-lg text-xs font-bold transition-colors">
                                     <i class="fas fa-file-signature"></i> Signed Agreement (PDF)
                                 </a>
                             @elseif($candidate->profile->is_agreement_signed)
                                 <span class="inline-flex items-center gap-2 px-3 py-2 bg-teal-50 border border-teal-100 text-teal-700 rounded-lg text-xs font-bold">
                                     <i class="fas fa-file-signature"></i> Signed Digitally ({{ $candidate->profile->signature_date_time ? $candidate->profile->signature_date_time->format('d M, Y') : 'Active' }})
                                 </span>
                             @endif
                        </div>
                    </div>

                    <!-- Manual Agreement Upload -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agreement Status</h4>
                            @if($candidate->profile && ($candidate->profile->is_agreement_signed || $candidate->profile->agreement_pdf_path || $candidate->profile->signature_date_time))
                                @if($candidate->profile->agreement_pdf_path)
                                    <a href="{{ Storage::url($candidate->profile->agreement_pdf_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold border border-green-200 hover:bg-green-100 transition-colors">
                                        <i class="fas fa-check-circle"></i> Signed & Valid (PDF)
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold border border-green-200">
                                        <i class="fas fa-check-circle"></i> Signed (Digitally)
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">
                                    <i class="fas fa-clock"></i> Not Signed
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('admin.crm.candidate.upload-agreement', $candidate->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm">
                            @csrf
                            <label class="block text-xs font-bold text-gray-700 mb-2">Manually Upload Agreement (PDF)</label>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="file" name="agreement_pdf" accept="application/pdf" required class="flex-1 block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <button type="submit" class="shrink-0 px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm">
                                    Upload & Send
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Verification -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <form action="{{ route('admin.crm.candidate.verify', $candidate->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 {{ $candidate->profile->is_verified ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' : 'bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100' }} rounded-xl text-sm font-bold transition-colors shadow-sm flex items-center justify-center gap-2">
                                @if($candidate->profile->is_verified)
                                    <i class="fas fa-times-circle"></i> Revoke Verification Badge
                                @else
                                    <i class="fas fa-check-circle"></i> Verify & Award Badge
                                @endif
                            </button>
                        </form>
                    </div>
                @else
                    <div class="py-10 flex flex-col items-center justify-center text-gray-500">
                        <i class="fas fa-user-slash text-4xl mb-3 text-gray-300"></i>
                        <p class="font-medium text-sm">No profile data available yet.</p>
                        <p class="text-xs text-gray-400 mt-1">Candidate has not completed their profile setup.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Job Applications -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col gap-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Job Applications</h3>
                    <form action="{{ route('admin.crm.application.assign', $candidate->id) }}" method="POST" class="flex flex-col gap-2 w-full">
                        @csrf
                        <select name="job_post_id" required class="text-sm rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                            <option value="">-- Assign a Job to Candidate --</option>
                            @foreach($availableJobs as $job)
                                <option value="{{ $job->id }}">{{ $job->title }} ({{ $job->school_name }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm w-full text-center">Assign Job</button>
                    </form>
                </div>
                @forelse($candidate->applications as $app)
                    <div class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                        <div class="font-semibold text-gray-800">{{ $app->jobPost->title }}</div>
                        <div class="text-xs text-gray-500 mb-1">{{ $app->jobPost->school_name }}</div>
                            <div class="mt-3">
                                <form action="{{ route('admin.applications.status.update', $app->id) }}" method="POST" class="space-y-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    @csrf
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <label class="text-xs font-bold text-gray-700 shrink-0">Status:</label>
                                        <select name="status" class="text-xs font-bold px-2 py-1.5 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer w-full">
                                            <option value="applied" {{ $app->status === 'applied' ? 'selected' : '' }}>Applied</option>
                                            <option value="shortlisted" {{ $app->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted (Schedule Interview)</option>
                                            <option value="hired" {{ $app->status === 'hired' ? 'selected' : '' }}>Hired</option>
                                            <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Interview Date (If Shortlisted)</label>
                                            <input type="datetime-local" name="interview_date" value="{{ $app->interview_date }}" class="w-full text-xs rounded-lg border-gray-300 shadow-sm px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Interview Link / Location</label>
                                            <input type="text" name="interview_link" value="{{ $app->interview_link }}" placeholder="e.g. Zoom Link or Address" class="w-full text-xs rounded-lg border-gray-300 shadow-sm px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Remarks (Visible to Candidate)</label>
                                        <textarea name="remarks" rows="2" class="w-full text-xs rounded-lg border-gray-300 shadow-sm px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500" placeholder="Add feedback or updates here...">{{ $app->remarks }}</textarea>
                                    </div>

                                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:items-center w-full">
                                        @if($app->status === 'hired')
                                            @if(!$app->invoice)
                                                <button type="button" onclick="prepareInvoice({{ $app->id }})" class="w-full sm:w-auto text-xs px-3 py-2 sm:py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 font-bold transition-colors shadow-sm text-center">
                                                    <i class="fas fa-file-invoice-dollar mr-1"></i> Generate Invoice
                                                </button>
                                            @else
                                                <span class="w-full sm:w-auto text-center text-xs px-3 py-2 sm:py-1.5 bg-gray-100 text-green-700 font-bold rounded-lg border border-green-200">
                                                    <i class="fas fa-check-circle mr-1"></i> Invoiced
                                                </span>
                                            @endif
                                        @endif
                                        
                                        <button type="submit" class="w-full sm:w-auto text-center text-xs px-4 py-2 sm:py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition-colors shadow-sm whitespace-nowrap">
                                            Save Updates
                                        </button>
                                    </div>
                                </form>
                            </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No applications found.</p>
                @endforelse
            </div>
        </div>

        <!-- Candidate Rating System -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Admin Ratings</h3>
                    @if($rating)
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded"><i class="fas fa-star text-yellow-500 mr-1"></i> {{ number_format($rating->overall_rating, 1) }} Overall</span>
                    @endif
                </div>

                <form action="{{ route('admin.crm.candidate.rate', $candidate->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @php
                        $params = [
                            'communication' => 'Communication Skills',
                            'subject_knowledge' => 'Subject Knowledge',
                            'demo_performance' => 'Demo Performance',
                            'english_fluency' => 'English Fluency',
                            'discipline' => 'Professionalism & Discipline'
                        ];
                    @endphp

                    @foreach($params as $key => $label)
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">{{ $label }}</label>
                        <select name="{{ $key }}" class="rounded-md border-gray-300 shadow-sm text-sm p-1 w-24">
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}" {{ ($rating && $rating->$key == $i) ? 'selected' : ($i==3 ? 'selected' : '') }}>{{ $i }} Stars</option>
                            @endfor
                        </select>
                    </div>
                    @endforeach

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" rows="2" class="w-full rounded-md border-gray-300 shadow-sm text-sm">{{ $rating->remarks ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-900 transition-colors">
                        Save Ratings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: CRM Follow-ups & Invoices -->
    <div class="lg:col-span-2 space-y-6 min-w-0">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Service Charge Invoices -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Service Charge Invoices</h3>
                </div>

                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full bg-white border border-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="py-2 px-4 text-left font-medium">Job Role</th>
                                <th class="py-2 px-4 text-left font-medium">Amount</th>
                                <th class="py-2 px-4 text-left font-medium">Late Fee</th>
                                <th class="py-2 px-4 text-left font-medium">Due Date</th>
                                <th class="py-2 px-4 text-left font-medium">Status</th>
                                <th class="py-2 px-4 text-left font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($invoices as $invoice)
                            <tr>
                                <td class="py-2 px-4">{{ $invoice->jobApplication->jobPost->title }}</td>
                                <td class="py-2 px-4">₹{{ number_format($invoice->amount, 2) }}</td>
                                <td class="py-2 px-4 text-red-600">₹{{ number_format($invoice->late_fee, 2) }}</td>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                                <td class="py-2 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold 
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 space-y-2">
                                    @if($invoice->status !== 'paid')
                                    <form action="{{ route('admin.crm.invoice.update', $invoice->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="text-xs text-green-600 hover:text-green-900 font-bold" onclick="return confirm('Mark this invoice as Paid?')">Mark Paid</button>
                                    </form>
                                    
                                    @if($invoice->late_fee > 0)
                                    <div class="mt-2 border-t border-gray-100 pt-2">
                                        <form action="{{ route('admin.crm.invoice.adjust', $invoice->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="number" name="deduction" max="{{ $invoice->late_fee }}" min="1" required placeholder="Amt" class="w-16 rounded-md border-gray-300 shadow-sm text-xs py-1 px-2 focus:ring-blue-500 focus:border-blue-500">
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-900 font-bold bg-blue-50 px-2 py-1 rounded">Waive</button>
                                        </form>
                                    </div>
                                    @endif

                                    @else
                                        <span class="text-xs text-gray-400">Settled</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">No invoices generated yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Generate Invoice Form -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                    <h4 class="font-bold text-sm text-gray-800 mb-3">Generate New Invoice</h4>
                    <form action="{{ route('admin.crm.invoice.store', $candidate->id) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                        @csrf
                        
                        @if($errors->any())
                            <div class="w-full bg-red-100 text-red-700 p-2 rounded text-xs mb-2">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="w-full md:w-auto flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Select Hired Job Application</label>
                            <select name="job_application_id" id="job_application_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">-- Select Application --</option>
                                @foreach($candidate->applications->where('status', 'hired') as $app)
                                    <option value="{{ $app->id }}">{{ $app->jobPost->title }} ({{ $app->jobPost->school_name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Amount (₹)</label>
                            <input type="number" name="amount" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required min="0">
                        </div>
                        <div class="w-36">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Follow-ups -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Follow-ups & Notes</h3>
                
                <!-- Add Follow-up Form -->
                <form action="{{ route('admin.crm.followup.store', $candidate->id) }}" method="POST" class="mb-6 bg-gray-50 p-4 rounded border border-gray-200">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes / Call Summary</label>
                        <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500" required placeholder="What was discussed?"></textarea>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-up Date</label>
                            <input type="date" name="follow_up_date" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                                <option value="open">Open (Needs Action)</option>
                                <option value="closed">Closed (Resolved)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                            Add Follow-up
                        </button>
                    </div>
                </form>

                <!-- Follow-up History -->
                <div class="space-y-4">
                    <h4 class="font-bold text-sm text-gray-600 uppercase tracking-wider mb-2">History</h4>
                    @forelse($followUps as $fu)
                        <div class="border-l-4 {{ $fu->status === 'open' ? 'border-yellow-400' : 'border-green-400' }} pl-4 py-2">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-bold text-gray-500">{{ $fu->admin->name }} &bull; {{ $fu->created_at->format('M d, Y h:i A') }}</span>
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded {{ $fu->status === 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $fu->status }}</span>
                            </div>
                            <p class="text-sm text-gray-800">{{ $fu->notes }}</p>
                            @if($fu->follow_up_date)
                                <div class="mt-2 text-xs text-indigo-600 font-medium">
                                    <i class="fas fa-calendar-alt mr-1"></i> Next Follow-up: {{ \Carbon\Carbon::parse($fu->follow_up_date)->format('M d, Y') }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No follow-ups recorded yet.</p>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- History Timeline -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Audit Trail / History</h3>
                
                <div class="relative border-l-2 border-gray-200 ml-4 space-y-8">
                    @forelse($history as $event)
                        <div class="relative pl-6">
                            <!-- Timeline Dot -->
                            <div class="absolute -left-3.5 top-0 w-7 h-7 rounded-full {{ $event['color'] }} text-white flex items-center justify-center text-xs shadow-md border-2 border-white">
                                <i class="{{ $event['icon'] }}"></i>
                            </div>
                            
                            <!-- Content -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-800 text-sm">{{ $event['title'] }}</h4>
                                    <span class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y h:i A') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $event['description'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="pl-6 text-sm text-gray-500 italic">No history found for this candidate.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function prepareInvoice(appId) {
        const select = document.getElementById('job_application_id');
        if(select) {
            select.value = appId;
            select.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Highlight the form momentarily
            const formContainer = select.closest('.bg-gray-50');
            if(formContainer) {
                formContainer.classList.add('ring-2', 'ring-indigo-500', 'transition-all', 'duration-500');
                setTimeout(() => formContainer.classList.remove('ring-2', 'ring-indigo-500'), 1500);
            }

            // Focus on amount
            const amountInput = document.querySelector('input[name="amount"]');
            if(amountInput) {
                amountInput.focus();
            }
        }
    }
</script>
@endpush
