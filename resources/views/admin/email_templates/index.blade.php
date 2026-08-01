@extends('layouts.admin')

@section('title', 'Email Templates')
@section('subtitle', 'Manage reusable email templates for bulk sending')

@section('actions')
    <a href="{{ route('admin.email-templates.create') }}" class="px-5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
        <i class="fas fa-plus"></i> Create Template
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @if(session('success'))
        <div class="bg-green-50 text-green-600 p-4 text-sm font-medium border-b border-green-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4 font-medium">Template Name</th>
                    <th class="px-6 py-4 font-medium">Email Subject</th>
                    <th class="px-6 py-4 font-medium">Last Updated</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($templates as $template)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $template->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $template->subject }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">
                            {{ $template->updated_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.email-templates.edit', $template->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit Template">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.email-templates.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Delete Template">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                <i class="fas fa-envelope-open-text text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No Templates Found</h3>
                            <p class="text-sm">You haven't created any email templates yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($templates->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $templates->links() }}
        </div>
    @endif
</div>
@endsection
