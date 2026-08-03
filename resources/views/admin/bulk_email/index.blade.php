@extends('layouts.admin')

@section('title', 'Bulk Emailer')
@section('subtitle', 'Send customized emails to multiple users using saved templates')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-8">
        @if(session('success'))
            <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-medium border border-green-100 mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.bulk-email.send') }}" method="POST" id="bulk-email-form" class="space-y-8">
            @csrf

            <!-- Section 1: Choose Template -->
            <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span> 
                    Choose Template
                </h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <select id="template_id" name="template_id" required class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-3 px-4 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select a Saved Template --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="template-preview" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Subject</label>
                            <input type="text" id="preview_subject" name="subject" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Body (You can modify this for this specific send)</label>
                            <textarea id="preview_body" name="body" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white min-h-[200px]"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Select Recipients -->
            <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span> 
                    Select Recipients
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Role</label>
                        <select id="role_filter" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Users</option>
                            <option value="candidate">Candidates Only</option>
                            <option value="employer">Employers Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search User (Name/Email)</label>
                        <div class="flex gap-2">
                            <input type="text" id="user_search" placeholder="Search..." class="flex-1 rounded-lg border-gray-300 shadow-sm text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" id="btn_search" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300 transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl bg-white overflow-hidden">
                    <div class="bg-gray-100 px-4 py-2 text-xs font-bold text-gray-500 uppercase flex justify-between items-center border-b border-gray-200">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="select_all_search" class="rounded border-gray-300 text-blue-600">
                            Select All Shown
                        </label>
                        <span id="search_results_count">0 users</span>
                    </div>
                    <div id="search_results" class="max-h-80 overflow-y-auto p-2 space-y-1">
                        <div class="text-center text-gray-500 py-4 text-sm">Use the search above to find users.</div>
                    </div>
                    <div id="pagination_controls" class="bg-gray-50 px-4 py-2 border-t border-gray-200 flex justify-between items-center hidden">
                        <button type="button" id="btn_prev_page" class="px-3 py-1 bg-white border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50">Previous</button>
                        <span id="page_info" class="text-xs text-gray-500 font-medium">Page 1 of 1</span>
                        <button type="button" id="btn_next_page" class="px-3 py-1 bg-white border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50">Next</button>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-4">
                    <h4 class="font-bold text-gray-900 mb-2">Selected Recipients (<span id="selected_count">0</span>)</h4>
                    <div id="selected_users_container" class="flex flex-wrap gap-2 max-h-48 overflow-y-auto p-2 bg-white rounded-lg border border-gray-200">
                        <span class="text-sm text-gray-400">No users selected.</span>
                    </div>
                    <div id="hidden_inputs_container"></div>
                </div>
            </div>

            <!-- Section 3: Send -->
            <div class="pt-4 flex justify-end">
                <button type="submit" id="btn_send" disabled class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane"></i> Send Bulk Email
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
        border-radius: 0 0 0.75rem 0.75rem !important;
    }
    .ck-toolbar {
        border-radius: 0.75rem 0.75rem 0 0 !important;
        background: #f8fafc !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const templateSelect = document.getElementById('template_id');
        const previewContainer = document.getElementById('template-preview');
        const previewSubject = document.getElementById('preview_subject');
        
        let editorInstance;

        ClassicEditor
            .create(document.querySelector('#preview_body'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
            })
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error('CKEditor init error:', error);
            });
        
        // Template Selection
        templateSelect.addEventListener('change', function() {
            const templateId = this.value;
            if (!templateId) {
                previewContainer.classList.add('hidden');
                return;
            }

            fetch(`/admin/email-templates/api/${templateId}`)
                .then(res => res.json())
                .then(data => {
                    previewSubject.value = data.subject || '';
                    if (editorInstance) {
                        editorInstance.setData(data.body || '');
                    } else {
                        document.getElementById('preview_body').value = data.body || '';
                    }
                    previewContainer.classList.remove('hidden');
                });
        });

        // Form Submit Handler & Validation
        const bulkEmailForm = document.getElementById('bulk-email-form');
        if (bulkEmailForm) {
            bulkEmailForm.addEventListener('submit', function(e) {
                if (editorInstance) {
                    document.getElementById('preview_body').value = editorInstance.getData();
                }

                const templateId = templateSelect.value;
                const subject = previewSubject.value.trim();
                const body = document.getElementById('preview_body').value.trim();

                if (!templateId) {
                    e.preventDefault();
                    alert('Please select an email template.');
                    templateSelect.focus();
                    return false;
                }

                if (!subject) {
                    e.preventDefault();
                    alert('Please enter a subject for the email.');
                    previewSubject.focus();
                    return false;
                }

                if (!body || body === '<p></p>') {
                    e.preventDefault();
                    alert('Please enter body text for the email.');
                    if (editorInstance) {
                        editorInstance.editing.view.focus();
                    }
                    return false;
                }
            });
        }

        // User Search and Selection
        const btnSearch = document.getElementById('btn_search');
        const roleFilter = document.getElementById('role_filter');
        const userSearch = document.getElementById('user_search');
        const searchResults = document.getElementById('search_results');
        const selectAllSearch = document.getElementById('select_all_search');
        const searchResultsCount = document.getElementById('search_results_count');
        const selectedUsersContainer = document.getElementById('selected_users_container');
        const hiddenInputsContainer = document.getElementById('hidden_inputs_container');
        const selectedCount = document.getElementById('selected_count');
        const btnSend = document.getElementById('btn_send');
        const paginationControls = document.getElementById('pagination_controls');
        const btnPrevPage = document.getElementById('btn_prev_page');
        const btnNextPage = document.getElementById('btn_next_page');
        const pageInfo = document.getElementById('page_info');

        const selectedUsers = new Map(); // id => {name, email, role}
        let currentPage = 1;

        function performSearch(page = 1) {
            btnSearch.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            currentPage = page;
            const params = new URLSearchParams({
                role: roleFilter.value,
                search: userSearch.value,
                page: currentPage
            });

            fetch(`/admin/bulk-email/search-users?${params.toString()}`)
                .then(res => res.json())
                .then(data => {
                    const users = data.data;
                    searchResults.innerHTML = '';
                    searchResultsCount.textContent = `${data.total} users found`;
                    selectAllSearch.checked = false;
                    
                    if (data.total === 0) {
                        searchResults.innerHTML = '<div class="text-center text-gray-500 py-4 text-sm">No users found.</div>';
                        paginationControls.classList.add('hidden');
                        return;
                    }

                    // Pagination
                    paginationControls.classList.remove('hidden');
                    pageInfo.textContent = `Page ${data.current_page} of ${data.last_page}`;
                    btnPrevPage.disabled = data.current_page === 1;
                    btnNextPage.disabled = data.current_page === data.last_page;

                    users.forEach(user => {
                        const isChecked = selectedUsers.has(user.id.toString()) ? 'checked' : '';
                        const roleBadge = user.role === 'candidate' 
                            ? '<span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full ml-2 uppercase font-bold">Candidate</span>'
                            : '<span class="bg-purple-100 text-purple-700 text-[10px] px-2 py-0.5 rounded-full ml-2 uppercase font-bold">Employer</span>';

                        const div = document.createElement('div');
                        div.className = 'flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg border border-transparent hover:border-gray-100 transition-colors cursor-pointer';
                        div.innerHTML = `
                            <input type="checkbox" class="search-user-cb rounded border-gray-300 text-blue-600" value="${user.id}" data-name="${user.name}" data-email="${user.email}" data-role="${user.role}" ${isChecked}>
                            <div class="flex-1">
                                <div class="font-medium text-sm text-gray-900">${user.name} ${roleBadge}</div>
                                <div class="text-xs text-gray-500">${user.email}</div>
                            </div>
                        `;
                        
                        // Make entire row clickable
                        div.addEventListener('click', (e) => {
                            if(e.target.tagName !== 'INPUT') {
                                const cb = div.querySelector('input');
                                cb.checked = !cb.checked;
                                cb.dispatchEvent(new Event('change'));
                            }
                        });

                        const cb = div.querySelector('input');
                        cb.addEventListener('change', function() {
                            if (this.checked) {
                                selectedUsers.set(this.value, { name: this.dataset.name, email: this.dataset.email, role: this.dataset.role });
                            } else {
                                selectedUsers.delete(this.value);
                            }
                            updateSelectedUI();
                        });

                        searchResults.appendChild(div);
                    });
                })
                .finally(() => {
                    btnSearch.innerHTML = '<i class="fas fa-search"></i>';
                });
        }

        btnSearch.addEventListener('click', () => performSearch(1));
        userSearch.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(1);
            }
        });

        btnPrevPage.addEventListener('click', () => performSearch(currentPage - 1));
        btnNextPage.addEventListener('click', () => performSearch(currentPage + 1));

        selectAllSearch.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.search-user-cb');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                if (this.checked) {
                    selectedUsers.set(cb.value, { name: cb.dataset.name, email: cb.dataset.email, role: cb.dataset.role });
                } else {
                    selectedUsers.delete(cb.value);
                }
            });
            updateSelectedUI();
        });

        function updateSelectedUI() {
            selectedUsersContainer.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';
            
            selectedCount.textContent = selectedUsers.size;
            
            if (selectedUsers.size === 0) {
                selectedUsersContainer.innerHTML = '<span class="text-sm text-gray-400 p-2">No users selected.</span>';
                btnSend.disabled = true;
                return;
            }

            btnSend.disabled = false;

            selectedUsers.forEach((data, id) => {
                // UI badge
                const badge = document.createElement('div');
                badge.className = 'inline-flex items-center gap-1 bg-gray-100 border border-gray-200 px-3 py-1 rounded-full text-xs font-medium text-gray-700';
                badge.innerHTML = `
                    ${data.name} 
                    <button type="button" class="text-gray-400 hover:text-red-500 ml-1 remove-user" data-id="${id}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                badge.querySelector('.remove-user').addEventListener('click', function() {
                    const removeId = this.dataset.id;
                    selectedUsers.delete(removeId);
                    
                    // Uncheck in search list if visible
                    const cb = document.querySelector(`.search-user-cb[value="${removeId}"]`);
                    if (cb) cb.checked = false;
                    
                    updateSelectedUI();
                });

                selectedUsersContainer.appendChild(badge);

                // Hidden input for form submission
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'user_ids[]';
                hiddenInput.value = id;
                hiddenInputsContainer.appendChild(hiddenInput);
            });
        }
    });
</script>
@endpush
@endsection
