<!-- Quick Add School / Institution Modal (Rendered outside job form to prevent nested forms) -->
<div id="quickAddSchoolModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="absolute inset-0" onclick="closeQuickAddSchoolModal()"></div>
    <div class="bg-card-bg border border-card-border rounded-2xl max-w-xl w-full p-6 shadow-2xl relative z-10 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-card-border">
            <div>
                <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                    <i class="fas fa-school text-accent-blue"></i>
                    <span>Add New School / Institution</span>
                </h3>
                <p class="text-xs text-text-dark/60 mt-0.5">Saves straight to Schools CRM and auto-fills this job post immediately.</p>
            </div>
            <button type="button" onclick="closeQuickAddSchoolModal()" class="text-text-dark/50 hover:text-text-main text-xl font-bold">&times;</button>
        </div>

        <div id="quickAddSchoolErrors" class="hidden mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/30 text-xs text-red-400"></div>

        <form id="quickAddSchoolForm" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1">School / Institution Name *</label>
                    <input type="text" name="school_name" id="qa_school_name" required placeholder="E.g., St. Xavier's High School"
                           class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accent-blue">
                </div>

                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1">Contact Person *</label>
                    <input type="text" name="contact_person" id="qa_contact_person" required placeholder="Principal / HR Name"
                           class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accent-blue">
                </div>

                <div>
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1">Phone Number *</label>
                    <input type="text" name="phone" id="qa_phone" required placeholder="98XXXXXXXX"
                           class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accent-blue">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1">Email Address *</label>
                    <input type="email" name="email" id="qa_email" required placeholder="school@example.com"
                           class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accent-blue">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1">School Address *</label>
                    <input type="text" name="address" id="qa_address" required placeholder="Full campus address"
                           class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-accent-blue">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1">School Photo / Logo (Optional)</label>
                    <input type="file" name="logo" id="qa_logo" accept="image/png,image/jpeg,image/jpg,image/webp"
                           class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-3 py-2 text-xs">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-text-dark/70 uppercase tracking-wider mb-1">About / Notes (Optional)</label>
                    <textarea name="about" id="qa_about" rows="2" placeholder="Optional institution details..."
                              class="w-full bg-secondary-bg border border-card-border text-text-main rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-accent-blue"></textarea>
                </div>
            </div>

            <div class="pt-3 border-t border-card-border flex justify-end gap-2">
                <button type="button" onclick="closeQuickAddSchoolModal()" class="px-4 py-2 rounded-xl bg-secondary-bg text-text-dark/70 text-xs font-bold hover:bg-card-border transition-colors">
                    Cancel
                </button>
                <button type="submit" id="quickAddSchoolSubmitBtn" class="px-5 py-2 rounded-xl bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-bold shadow-sm transition-all cursor-pointer inline-flex items-center gap-1.5">
                    <i class="fas fa-save"></i>
                    <span>Save &amp; Select School</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const schoolsList = @json($schools);
        const schoolsById = {};
        schoolsList.forEach(s => { schoolsById[String(s.id)] = s; });

        const schoolSelect = document.getElementById('school_user_id');
        const quickSearch = document.getElementById('school_quick_search');
        const prefillBanner = document.getElementById('school_prefill_banner');
        const prefillText = document.getElementById('school_prefill_text');
        const saveAsNewWrap = document.getElementById('save_as_new_school_wrap');

        function applySchoolToJobForm(school) {
            if (!school) return;

            const nameEl = document.getElementById('school_name');
            const contactEl = document.getElementById('contact_person');
            const emailEl = document.getElementById('email');
            const phoneEl = document.getElementById('phone');
            const retainedEl = document.getElementById('retained_image');
            const removeImgEl = document.getElementById('remove_school_image');
            const fileInputEl = document.getElementById('school_image');
            const previewImg = document.getElementById('image-preview-img');
            const previewState = document.getElementById('image-preview-state');
            const emptyState = document.getElementById('image-empty-state');
            const previewName = document.getElementById('image-preview-name');

            if (nameEl) nameEl.value = school.school_name || '';
            if (contactEl) contactEl.value = school.contact_person || '';
            if (emailEl) emailEl.value = school.email || '';
            if (phoneEl) phoneEl.value = school.phone || '';

            if (school.logo_path && school.logo_url && (!fileInputEl || !fileInputEl.files || fileInputEl.files.length === 0)) {
                if (retainedEl) retainedEl.value = school.logo_path;
                if (removeImgEl) removeImgEl.value = '0';
                if (previewImg) previewImg.src = school.logo_url;
                if (previewName) previewName.textContent = 'Saved School Photo';
                if (emptyState) emptyState.classList.add('hidden');
                if (previewState) previewState.classList.remove('hidden');
            }

            if (prefillBanner && prefillText) {
                prefillText.innerHTML = `Linked to <strong>${escapeHtml(school.school_name)}</strong>` + (school.address ? ` · ${escapeHtml(school.address)}` : '');
                prefillBanner.classList.remove('hidden');
            }
            if (saveAsNewWrap) {
                saveAsNewWrap.classList.add('hidden');
                saveAsNewWrap.classList.remove('inline-flex');
            }
        }

        function escapeHtml(str) {
            return String(str || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        window.clearSelectedSchool = function () {
            if (schoolSelect) schoolSelect.value = '';
            if (prefillBanner) prefillBanner.classList.add('hidden');
            if (saveAsNewWrap) {
                saveAsNewWrap.classList.remove('hidden');
                saveAsNewWrap.classList.add('inline-flex');
            }
        };

        if (schoolSelect) {
            schoolSelect.addEventListener('change', function () {
                const selected = schoolsById[String(this.value)];
                if (selected) {
                    applySchoolToJobForm(selected);
                } else {
                    window.clearSelectedSchool();
                }
            });
        }

        if (quickSearch && schoolSelect) {
            quickSearch.addEventListener('input', function () {
                const q = this.value.trim().toLowerCase();
                Array.from(schoolSelect.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    const match = opt.text.toLowerCase().includes(q);
                    opt.hidden = !match;
                });
            });
        }

        window.openQuickAddSchoolModal = function () {
            const modal = document.getElementById('quickAddSchoolModal');
            const errBox = document.getElementById('quickAddSchoolErrors');
            if (errBox) {
                errBox.classList.add('hidden');
                errBox.innerHTML = '';
            }
            // Pre-copy manually typed values into quick add modal if admin started typing before clicking Add
            const sName = document.getElementById('school_name')?.value?.trim();
            const cPerson = document.getElementById('contact_person')?.value?.trim();
            const sEmail = document.getElementById('email')?.value?.trim();
            const sPhone = document.getElementById('phone')?.value?.trim();
            if (sName && !document.getElementById('qa_school_name').value) document.getElementById('qa_school_name').value = sName;
            if (cPerson && !document.getElementById('qa_contact_person').value) document.getElementById('qa_contact_person').value = cPerson;
            if (sEmail && !document.getElementById('qa_email').value) document.getElementById('qa_email').value = sEmail;
            if (sPhone && !document.getElementById('qa_phone').value) document.getElementById('qa_phone').value = sPhone;

            modal.classList.remove('hidden');
        };

        window.closeQuickAddSchoolModal = function () {
            document.getElementById('quickAddSchoolModal').classList.add('hidden');
        };

        const quickForm = document.getElementById('quickAddSchoolForm');
        if (quickForm) {
            quickForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const errBox = document.getElementById('quickAddSchoolErrors');
                const submitBtn = document.getElementById('quickAddSchoolSubmitBtn');
                const originalHtml = submitBtn.innerHTML;

                errBox.classList.add('hidden');
                errBox.innerHTML = '';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                try {
                    const formData = new FormData(quickForm);
                    const res = await fetch(@json(route('admin.schools.quick-store')), {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: formData,
                    });
                    const payload = await res.json();

                    if (!res.ok || !payload.success) {
                        const msgs = [];
                        if (payload.errors) {
                            Object.values(payload.errors).forEach(arr => msgs.push(...arr));
                        } else if (payload.message) {
                            msgs.push(payload.message);
                        } else {
                            msgs.push('Could not save school. Please check the fields.');
                        }
                        errBox.innerHTML = msgs.map(m => `<div>&bull; ${escapeHtml(m)}</div>`).join('');
                        errBox.classList.remove('hidden');
                        return;
                    }

                    const newSchool = payload.school;
                    schoolsById[String(newSchool.id)] = newSchool;

                    if (schoolSelect) {
                        const opt = document.createElement('option');
                        opt.value = String(newSchool.id);
                        opt.text = `${newSchool.school_name}${newSchool.contact_person ? ' — ' + newSchool.contact_person : ''}${newSchool.email ? ' (' + newSchool.email + ')' : ''}`;
                        opt.selected = true;
                        schoolSelect.appendChild(opt);
                    }

                    applySchoolToJobForm(newSchool);
                    quickForm.reset();
                    window.closeQuickAddSchoolModal();
                } catch (err) {
                    errBox.textContent = 'Network error while saving school. Please try again.';
                    errBox.classList.remove('hidden');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                }
            });
        }
    })();
</script>
@endpush
