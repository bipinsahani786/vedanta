{{-- Shared Admin Candidate Photo Lightbox & Update Modal --}}
<div id="adminPhotoModal"
     class="fixed inset-0 z-[120] hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-sm"
     role="dialog"
     aria-modal="true"
     aria-labelledby="admin-photo-modal-title">
    <div class="absolute inset-0" onclick="closeAdminPhotoModal()"></div>

    <div class="relative z-10 w-full max-w-lg rounded-3xl bg-white border border-gray-200 p-6 shadow-2xl">
        <div class="flex items-center justify-between gap-3 pb-4 border-b border-gray-100">
            <div class="min-w-0">
                <span id="adminPhotoModalBadge" class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md mb-1">
                    Profile Photo
                </span>
                <h3 id="admin-photo-modal-title" class="text-base sm:text-lg font-extrabold text-gray-900 truncate">
                    Candidate Photo
                </h3>
                <p id="adminPhotoModalSub" class="text-xs text-gray-500 font-mono truncate"></p>
            </div>
            <button type="button"
                    onclick="closeAdminPhotoModal()"
                    class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                    aria-label="Close photo dialog">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="my-5 flex flex-col items-center justify-center bg-gray-50 rounded-2xl border border-gray-100 p-4 min-h-[240px]">
            <img id="adminPhotoPreviewImg"
                 src=""
                 alt="Candidate Photo"
                 class="hidden max-h-[55vh] w-auto max-w-full rounded-2xl object-contain shadow-md border border-gray-200">
            <div id="adminPhotoEmptyState" class="hidden text-center py-8">
                <div id="adminPhotoInitials" class="w-24 h-24 rounded-full mx-auto mb-3 bg-blue-100 text-blue-600 flex items-center justify-center text-3xl font-black border-4 border-white shadow">
                    C
                </div>
                <p class="text-xs font-medium text-gray-500">No photo uploaded yet.</p>
            </div>
        </div>

        <form id="adminPhotoUploadForm" action="" method="POST" enctype="multipart/form-data" class="space-y-3 pt-2 border-t border-gray-100">
            @csrf
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <label class="flex-1 flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-gray-50 hover:bg-blue-50/50 border border-dashed border-gray-300 hover:border-blue-400 text-xs font-semibold text-gray-700 cursor-pointer transition-colors">
                    <i class="fas fa-cloud-upload-alt text-blue-600"></i>
                    <span id="adminPhotoFileLabel" class="truncate">Choose new JPG / PNG / WEBP (max 5MB)</span>
                    <input type="file"
                           id="adminPhotoFileInput"
                           name="profile_photo"
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           required
                           onchange="previewAdminSelectedPhoto(this)"
                           class="hidden">
                </label>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-colors inline-flex items-center justify-center gap-1.5 cursor-pointer shrink-0">
                    <i class="fas fa-upload"></i>
                    <span>Update Photo</span>
                </button>
            </div>
            <div class="flex items-center justify-between text-[11px] text-gray-400">
                <span>Admins can update the candidate's main profile photo here.</span>
                <a id="adminPhotoOpenRawLink" href="#" target="_blank" class="hidden text-blue-600 hover:underline font-semibold inline-flex items-center gap-1">
                    <span>Open Original</span>
                    <i class="fas fa-external-link-alt text-[9px]"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function openAdminPhotoModal(candidateName, vpaId, photoUrl, uploadUrl, label = 'Profile Photo') {
        const modal = document.getElementById('adminPhotoModal');
        const img = document.getElementById('adminPhotoPreviewImg');
        const empty = document.getElementById('adminPhotoEmptyState');
        const initials = document.getElementById('adminPhotoInitials');
        const form = document.getElementById('adminPhotoUploadForm');
        const rawLink = document.getElementById('adminPhotoOpenRawLink');
        const fileInput = document.getElementById('adminPhotoFileInput');
        const fileLabel = document.getElementById('adminPhotoFileLabel');

        document.getElementById('admin-photo-modal-title').textContent = candidateName || 'Candidate Photo';
        document.getElementById('adminPhotoModalSub').textContent = vpaId ? ('ID: ' + vpaId) : '';
        document.getElementById('adminPhotoModalBadge').textContent = label || 'Profile Photo';
        form.action = uploadUrl;

        if (fileInput) fileInput.value = '';
        if (fileLabel) fileLabel.textContent = 'Choose new JPG / PNG / WEBP (max 5MB)';

        if (photoUrl) {
            img.src = photoUrl;
            img.classList.remove('hidden');
            empty.classList.add('hidden');
            rawLink.href = photoUrl;
            rawLink.classList.remove('hidden');
        } else {
            img.src = '';
            img.classList.add('hidden');
            empty.classList.remove('hidden');
            initials.textContent = (candidateName || 'C').trim().charAt(0).toUpperCase();
            rawLink.href = '#';
            rawLink.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAdminPhotoModal() {
        const modal = document.getElementById('adminPhotoModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function previewAdminSelectedPhoto(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const fileLabel = document.getElementById('adminPhotoFileLabel');
        if (fileLabel) fileLabel.textContent = file.name;

        const reader = new FileReader();
        reader.onload = function (ev) {
            const img = document.getElementById('adminPhotoPreviewImg');
            const empty = document.getElementById('adminPhotoEmptyState');
            if (img) {
                img.src = ev.target.result;
                img.classList.remove('hidden');
            }
            if (empty) empty.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAdminPhotoModal();
        }
    });
</script>
