@extends('layouts.admin')

@section('header')
<div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
        CRM: {{ $candidate->name }}
        @if($candidate->profile && $candidate->profile->is_verified)
            <i class="fas fa-check-circle text-blue-500" title="Verified Candidate"></i>
        @endif
    </h2>
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.crm.candidate.magic-login', $candidate->id) }}" target="_blank" class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded hover:bg-indigo-200 transition-colors">
            <i class="fas fa-sign-in-alt mr-1"></i> Login as Candidate
        </a>
        <a href="{{ route('admin.crm.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Back to List</a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Profile & Applications -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Candidate Profile -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Candidate Profile</h3>
                <div class="space-y-3 text-sm">
                    <p><strong>Name:</strong> {{ $candidate->name }}</p>
                    <p><strong>Email:</strong> {{ $candidate->email }}</p>
                    <p><strong>Phone:</strong> {{ $candidate->phone }}</p>
                    
                    @if($candidate->profile)
                        <hr class="my-3 border-gray-100">
                        <p><strong>Address:</strong> {{ $candidate->profile->address }}</p>
                        <p><strong>Experience:</strong> {{ $candidate->profile->years_of_experience }} years</p>
                        <p>
                            <strong>Status:</strong>
                            @if($candidate->profile->is_fee_paid)
                                <span class="text-green-600 font-bold">Active</span>
                            @else
                                <span class="text-yellow-600 font-bold">Pending Payment</span>
                            @endif
                        </p>
                        @if($candidate->profile->resume_path)
                            <div class="mt-3">
                                <a href="{{ Storage::url($candidate->profile->resume_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold"><i class="fas fa-file-pdf mr-1"></i> View Resume</a>
                            </div>
                        @endif
                        
                        @if($candidate->profile->salary_slip_path)
                            <div class="mt-2">
                                <a href="{{ Storage::url($candidate->profile->salary_slip_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold"><i class="fas fa-file-invoice-dollar mr-1"></i> View Salary Slip</a>
                            </div>
                        @endif

                        @if($candidate->profile->offer_letter_path)
                            <div class="mt-2">
                                <a href="{{ Storage::url($candidate->profile->offer_letter_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold"><i class="fas fa-file-contract mr-1"></i> View Offer Letter</a>
                            </div>
                        @endif

                        @if($candidate->profile->passport_photo_path)
                            <div class="mt-2">
                                <a href="{{ Storage::url($candidate->profile->passport_photo_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold"><i class="fas fa-id-badge mr-1"></i> View Passport Photo</a>
                            </div>
                        @endif

                        @if($candidate->profile->live_photo_path)
                            <div class="mt-2">
                                <a href="{{ Storage::url($candidate->profile->live_photo_path) }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-bold"><i class="fas fa-camera mr-1"></i> View Live Photo</a>
                            </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <form action="{{ route('admin.crm.candidate.verify', $candidate->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 {{ $candidate->profile->is_verified ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100' : 'bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100' }} rounded text-sm font-bold transition-colors">
                                    {{ $candidate->profile->is_verified ? 'Revoke Verification Badge' : 'Verify & Award Badge' }}
                                </button>
                            </form>
                        </div>

                        <!-- Manual Agreement Upload -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Agreement Details</h4>
                            @if($candidate->profile->is_agreement_signed && $candidate->profile->agreement_pdf_path)
                                <div class="mb-3">
                                    <a href="{{ Storage::url($candidate->profile->agreement_pdf_path) }}" target="_blank" class="text-green-600 hover:underline text-xs font-bold"><i class="fas fa-file-contract mr-1"></i> View Signed Agreement</a>
                                </div>
                            @else
                                <p class="text-xs text-yellow-600 mb-3"><i class="fas fa-exclamation-triangle"></i> Not signed digitally yet.</p>
                            @endif

                            <form action="{{ route('admin.crm.candidate.upload-agreement', $candidate->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-3 rounded border border-gray-200">
                                @csrf
                                <label class="block text-xs font-medium text-gray-700 mb-1">Manually Upload Agreement (PDF)</label>
                                <input type="file" name="agreement_pdf" accept="application/pdf" required class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-2">
                                <button type="submit" class="w-full px-2 py-1.5 bg-indigo-600 text-white rounded text-xs font-bold hover:bg-indigo-700 transition-colors">Upload & Send to Candidate</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Job Applications -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Job Applications</h3>
                @forelse($candidate->applications as $app)
                    <div class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                        <div class="font-semibold text-gray-800">{{ $app->jobPost->title }}</div>
                        <div class="text-xs text-gray-500 mb-1">{{ $app->jobPost->school_name }}</div>
                            <div class="flex justify-between items-center mt-2">
                                <form action="{{ route('admin.applications.status.update', $app->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold px-2 py-1 rounded bg-gray-100 text-gray-700 border-none focus:ring-0 cursor-pointer">
                                        <option value="applied" {{ $app->status === 'applied' ? 'selected' : '' }}>Applied</option>
                                        <option value="shortlisted" {{ $app->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                        <option value="hired" {{ $app->status === 'hired' ? 'selected' : '' }}>Hired</option>
                                        <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            @if($app->status === 'hired')
                                @if(!$app->invoice)
                                    <button type="button" onclick="prepareInvoice({{ $app->id }})" class="text-xs text-indigo-600 hover:underline font-bold">Generate Invoice</button>
                                @else
                                    <span class="text-xs text-green-600 font-bold"><i class="fas fa-check"></i> Invoiced</span>
                                @endif
                            @endif
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
    <div class="lg:col-span-2 space-y-6">
        
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
                                <td class="py-2 px-4">
                                    @if($invoice->status !== 'paid')
                                    <form action="{{ route('admin.crm.invoice.update', $invoice->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="text-xs text-green-600 hover:text-green-900 font-bold" onclick="return confirm('Mark this invoice as Paid?')">Mark Paid</button>
                                    </form>
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
                            <select name="job_application_id" id="job_application_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                                <option value="">-- Select Application --</option>
                                @foreach($candidate->applications->where('status', 'hired') as $app)
                                    @if(!$app->invoice)
                                        <option value="{{ $app->id }}">{{ $app->jobPost->title }} ({{ $app->jobPost->school_name }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Amount (₹)</label>
                            <input type="number" name="amount" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required min="0">
                        </div>
                        <div class="w-36">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
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
                        <textarea name="notes" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required placeholder="What was discussed?"></textarea>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Next Follow-up Date</label>
                            <input type="date" name="follow_up_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="open">Open (Needs Action)</option>
                                <option value="closed">Closed (Resolved)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
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
