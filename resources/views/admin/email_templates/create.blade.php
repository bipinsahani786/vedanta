@extends('layouts.admin')

@section('title', 'Create Email Template')
@section('subtitle', 'Create a new reusable email template')

@section('actions')
    <a href="{{ route('admin.email-templates.index') }}" class="px-5 py-2.5 bg-gray-600 text-white hover:bg-gray-700 rounded-xl text-sm font-semibold transition-all shadow-sm">
        &larr; Back to Templates
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex flex-col md:flex-row">
        <!-- Form Section -->
        <div class="w-full md:w-3/4 p-8 border-b md:border-b-0 md:border-r border-gray-100">
            <form action="{{ route('admin.email-templates.store') }}" method="POST" class="space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Candidate Registration Welcome" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">This is for your internal reference only.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="e.g. Welcome to Vedanta, {candidate_name}!" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Body <span class="text-red-500">*</span></label>
                    <textarea name="body" id="body-editor">{{ old('body') ?? '<p>Dear {name},</p><p>We are writing to inform you about an important update regarding your profile with Vedanta Placement Agency.</p><p>Your current category is <strong>{category}</strong> and your subject is <strong>{subject}</strong>.</p><p>If you have any questions, please feel free to reach out to our support team.</p><br><p>Best regards,<br><strong>Vedanta Team</strong></p>' }}</textarea>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Template
                    </button>
                </div>
            </form>
        </div>

        <!-- Reference Section -->
        <div class="w-full md:w-1/4 p-6 bg-gray-50/50">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-tags text-blue-500"></i> Dynamic Tags
            </h3>
            <p class="text-sm text-gray-600 mb-6">You can use these tags in both the Subject and Body. They will be automatically replaced with the recipient's details.</p>
            
            <div class="space-y-4">
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">General</h4>
                    <ul class="space-y-2">
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{name}</code></li>
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{email}</code></li>
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{phone}</code></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Candidates</h4>
                    <ul class="space-y-2">
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{category}</code></li>
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{subject}</code></li>
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{plan_type}</code></li>
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{invoice_number}</code></li>
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{payment_amount}</code></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Employers</h4>
                    <ul class="space-y-2">
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{company_name}</code></li>
                        <li><code class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-blue-600 select-all">{job_title}</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#body-editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
            })
            .catch(error => {
                console.error('CKEditor init error:', error);
            });
    });
</script>
<style>
    .ck-editor__editable_inline {
        min-height: 300px;
        border-radius: 0 0 0.75rem 0.75rem !important;
    }
    .ck-toolbar {
        border-radius: 0.75rem 0.75rem 0 0 !important;
        background: #f8fafc !important;
    }
</style>
@endpush
@endsection
