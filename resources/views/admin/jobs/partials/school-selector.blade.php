@php
    $selectedIdStr = (string) ($selectedSchoolId ?? '');
    $preselectedSchool = collect($schools)->firstWhere('id', (int) $selectedIdStr);
@endphp

<div class="mb-8 p-5 rounded-2xl bg-secondary-bg/60 border border-accent-blue/30 space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h4 class="text-sm font-bold text-text-main flex items-center gap-2">
                <i class="fas fa-school text-accent-blue"></i>
                <span>Select Saved School / Institution</span>
            </h4>
            <p class="text-xs text-text-dark/65 mt-0.5">
                Pick an existing school to auto-fill contact info &amp; campus photo, or add a new institution for reuse on future job posts.
            </p>
        </div>

        <button type="button" onclick="openQuickAddSchoolModal()"
                class="px-4 py-2 rounded-xl bg-accent-blue hover:bg-accent-blue-hover text-white text-xs font-bold shadow-sm transition-all inline-flex items-center gap-1.5 cursor-pointer shrink-0">
            <i class="fas fa-plus-circle"></i>
            <span>Add School / Institution</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-4 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-text-dark/40 text-xs"></i>
            <input type="text" id="school_quick_search"
                   placeholder="Filter school list..."
                   autocomplete="off"
                   class="w-full pl-8 pr-3 py-2.5 bg-card-bg border border-card-border text-text-main rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-accent-blue/40">
        </div>

        <div class="md:col-span-8">
            <select name="school_user_id" id="school_user_id"
                    class="w-full bg-card-bg border border-card-border text-text-main rounded-xl px-3.5 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-accent-blue/40">
                <option value="">-- Manual School Entry / Select Saved School ({{ count($schools) }}) --</option>
                @foreach($schools as $sch)
                    <option value="{{ $sch['id'] }}" {{ $selectedIdStr === (string) $sch['id'] ? 'selected' : '' }}>
                        {{ $sch['school_name'] }}{{ $sch['contact_person'] ? ' — ' . $sch['contact_person'] : '' }}{{ $sch['email'] ? ' (' . $sch['email'] . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="school_prefill_banner" class="{{ $preselectedSchool ? '' : 'hidden' }} p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs text-emerald-300 flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2 min-w-0">
            <i class="fas fa-check-circle text-emerald-400 shrink-0"></i>
            <span id="school_prefill_text" class="truncate">
                @if($preselectedSchool)
                    Linked to <strong>{{ $preselectedSchool['school_name'] }}</strong>{{ !empty($preselectedSchool['address']) ? ' · ' . $preselectedSchool['address'] : '' }}
                @endif
            </span>
        </div>
        <button type="button" onclick="clearSelectedSchool()" class="text-[11px] font-bold text-slate-300 hover:text-white underline cursor-pointer shrink-0">
            Unlink School
        </button>
    </div>

    @if(empty($isEdit))
        <label id="save_as_new_school_wrap" class="{{ $selectedIdStr !== '' ? 'hidden' : 'inline-flex' }} items-center gap-2 text-xs text-text-dark/80 cursor-pointer pt-1">
            <input type="checkbox" name="save_as_new_school" value="1" {{ old('save_as_new_school') ? 'checked' : '' }} class="rounded border-card-border text-accent-blue focus:ring-accent-blue">
            <span>Save manually entered school details to <strong>Schools CRM</strong> for reuse in future job posts (when Name &amp; Email are filled)</span>
        </label>
    @endif
</div>
