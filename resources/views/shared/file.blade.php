<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sharingFile->title }} - Vedanta Placement Agency</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            #readOnlyCvModal { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <header class="bg-slate-900/90 border-b border-slate-800 sticky top-0 z-30 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Vedanta" class="h-9 bg-white/95 rounded-lg p-1 shadow">
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-white">{{ $sharingFile->title }}</h1>
                    <p class="text-xs text-slate-400">Prepared for <strong class="text-amber-300">{{ $schoolName }}</strong></p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 text-xs">
                <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 font-bold">
                    <i class="fas fa-shield-alt mr-1"></i> View-Only CV Protection Active
                </span>
                @if($sharingFile->hasPassword())
                    <form action="{{ route('shared-file.lock', $sharingFile->share_token) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold cursor-pointer">
                            <i class="fas fa-lock mr-1"></i> Lock File
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        @if(!empty($sharingFile->description))
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 text-xs text-slate-300">
                {{ $sharingFile->description }}
            </div>
        @endif

        <!-- Category Folders -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    <i class="fas fa-folder-open text-amber-400 mr-1.5"></i> Categories
                </span>
                <span class="text-[11px] text-slate-400">
                    Approved Fields: <strong class="text-slate-200">{{ implode(', ', $sharingFile->approvedFieldLabels()) ?: 'Summary' }}</strong>
                </span>
            </div>
            <div class="flex flex-wrap gap-2">
                @forelse($sharingFile->categories as $cat)
                    @php $active = $selectedCategory && (int) $selectedCategory->id === (int) $cat->id; @endphp
                    <a href="{{ route('shared-file.show', ['token' => $sharingFile->share_token, 'category_id' => $cat->id]) }}"
                       class="px-3.5 py-2 rounded-xl text-xs font-bold border transition-all inline-flex items-center gap-2 {{ $active ? 'bg-amber-400 text-slate-950 border-amber-400 shadow-sm' : 'bg-slate-800/70 text-slate-200 border-slate-700 hover:border-amber-400/50' }}">
                        <i class="fas fa-folder"></i>
                        <span>{{ $cat->name }}</span>
                        <span class="px-1.5 py-0.2 rounded text-[10px] {{ $active ? 'bg-slate-950/15 text-slate-950' : 'bg-slate-950/60 text-slate-300' }}">{{ $cat->entries_count }}</span>
                    </a>
                @empty
                    <span class="text-xs text-slate-500">No categories configured yet.</span>
                @endforelse
            </div>
        </div>

        <!-- Candidate Cards -->
        @if($cards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($cards as $card)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between gap-4 shadow-xl">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if(!empty($card['photo_url']))
                                        <img src="{{ $card['photo_url'] }}" alt="{{ $card['name'] }}" class="w-14 h-14 rounded-xl object-cover border border-slate-700 shrink-0" draggable="false" oncontextmenu="return false;">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-amber-400/15 text-amber-300 border border-amber-400/30 flex items-center justify-center font-extrabold text-lg shrink-0">
                                            {{ strtoupper(substr($card['name'], 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h3 class="font-bold text-white text-sm truncate">{{ $card['name'] }}</h3>
                                            @if($card['is_verified'])
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30">Verified</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] font-mono text-slate-400">{{ $card['vpa_id'] }}</div>
                                        <div class="text-xs text-amber-300 font-semibold mt-0.5">{{ $card['category_label'] }} · {{ $card['subject'] }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5 text-xs">
                                @if($card['qualifications'] !== null)
                                    <div class="col-span-2 bg-slate-950/70 p-2.5 rounded-xl border border-slate-800">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Qualifications</span>
                                        <span class="font-semibold text-white">{{ $card['qualifications'] }}</span>
                                    </div>
                                @endif
                                @if($card['date_of_birth'] !== null)
                                    <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Date of Birth</span>
                                        <span class="font-semibold text-white">{{ $card['date_of_birth'] }}</span>
                                    </div>
                                @endif
                                @if($card['experience_years'] !== null)
                                    <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Experience</span>
                                        <span class="font-semibold text-white">{{ $card['experience_years'] }} Year(s)</span>
                                    </div>
                                @endif
                                @if($card['current_salary'] !== null)
                                    <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Current Salary</span>
                                        <span class="font-semibold text-white">{{ $card['current_salary'] }}</span>
                                    </div>
                                @endif
                                @if($card['expected_salary'] !== null)
                                    <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Expected Salary</span>
                                        <span class="font-semibold text-emerald-300">{{ $card['expected_salary'] }}</span>
                                    </div>
                                @endif
                                @if($card['availability'] !== null)
                                    <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Joining Availability</span>
                                        <span class="font-semibold text-amber-300">{{ $card['availability'] }}</span>
                                    </div>
                                @endif
                                <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Preferred Location</span>
                                    <span class="font-semibold text-white">{{ $card['location'] }}</span>
                                </div>
                            </div>

                            @if($card['email'] !== null || $card['phone'] !== null)
                                <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs text-emerald-200 space-y-0.5">
                                    <div><i class="fas fa-envelope w-4 text-emerald-400"></i> {{ $card['email'] ?: 'N/A' }}</div>
                                    <div><i class="fas fa-phone-alt w-4 text-emerald-400"></i> {{ $card['phone'] ?: 'N/A' }}</div>
                                </div>
                            @endif

                            @if(!empty($card['admin_note']))
                                <p class="text-[11px] text-slate-400 italic border-l-2 border-amber-400/60 pl-2.5">{{ $card['admin_note'] }}</p>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-slate-800 flex items-center justify-between gap-2">
                            @if($card['can_view_cv'] && !empty($card['cv_url']))
                                <button type="button"
                                        onclick="openReadOnlyCvModal(@json($card['cv_url']), @json($card['name']), @json($card['cv_ext']))"
                                        class="w-full py-2.5 px-4 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-extrabold cursor-pointer inline-flex items-center justify-center gap-2 transition-colors">
                                    <i class="fas fa-eye"></i>
                                    <span>View CV (Read-Only)</span>
                                </button>
                            @else
                                <span class="text-[11px] text-slate-500">
                                    {{ $sharingFile->allow_cv_view ? 'No CV uploaded by candidate' : 'CV viewing not enabled' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center text-slate-400 text-sm">
                No candidates assigned to {{ $selectedCategory ? "category \"{$selectedCategory->name}\"" : 'this sharing file' }} yet.
            </div>
        @endif
    </main>

    <!-- Read-Only Protected CV Viewer Modal (Zero Download Controls) -->
    <div id="readOnlyCvModal" class="fixed inset-0 z-50 hidden bg-black/85 backdrop-blur-sm flex items-center justify-center p-4 select-none" oncontextmenu="return false;">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-4xl w-full h-[88vh] flex flex-col shadow-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-800 flex items-center justify-between gap-3 bg-slate-950/60">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fas fa-file-alt text-amber-400"></i>
                        <span id="roCvCandidateName">Candidate CV</span>
                    </h3>
                    <span class="text-[11px] text-emerald-400 font-semibold">Protected View-Only Mode — Downloads Disabled</span>
                </div>
                <button type="button" onclick="closeReadOnlyCvModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-200 flex items-center justify-center cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="roCvBody" class="flex-1 overflow-y-auto p-4 sm:p-6 bg-slate-950 flex flex-col items-center gap-4 select-none" oncontextmenu="return false;"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function openReadOnlyCvModal(cvUrl, candidateName, ext) {
            const modal = document.getElementById('readOnlyCvModal');
            const body = document.getElementById('roCvBody');
            document.getElementById('roCvCandidateName').textContent = `${candidateName} — Resume / CV (View-Only)`;
            body.innerHTML = '<div class="py-12 text-slate-400 text-sm"><i class="fas fa-spinner fa-spin mr-2"></i>Rendering secure preview...</div>';
            modal.classList.remove('hidden');

            const lowerExt = (ext || 'pdf').toLowerCase();
            if (['png', 'jpg', 'jpeg', 'webp'].includes(lowerExt)) {
                body.innerHTML = '';
                const img = document.createElement('img');
                img.src = cvUrl;
                img.alt = 'Candidate CV';
                img.draggable = false;
                img.className = 'max-w-full rounded-xl shadow-lg pointer-events-none select-none';
                body.appendChild(img);
                return;
            }

            if (lowerExt === 'pdf' && window.pdfjsLib) {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                pdfjsLib.getDocument({ url: cvUrl, withCredentials: true }).promise.then(async (pdf) => {
                    body.innerHTML = '';
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        const page = await pdf.getPage(pageNum);
                        const viewport = page.getViewport({ scale: 1.4 });
                        const canvas = document.createElement('canvas');
                        canvas.className = 'max-w-full rounded-lg shadow-xl bg-white pointer-events-none';
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;
                        canvas.oncontextmenu = () => false;
                        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
                        body.appendChild(canvas);
                    }
                }).catch(() => renderIframeToolbarHidden(body, cvUrl));
                return;
            }

            renderIframeToolbarHidden(body, cvUrl);
        }

        function renderIframeToolbarHidden(container, cvUrl) {
            container.innerHTML = '';
            const frame = document.createElement('iframe');
            frame.src = `${cvUrl}#toolbar=0&navpanes=0&scrollbar=1`;
            frame.className = 'w-full h-full min-h-[70vh] rounded-xl border border-slate-800 bg-white';
            container.appendChild(frame);
        }

        function closeReadOnlyCvModal() {
            document.getElementById('readOnlyCvModal').classList.add('hidden');
            document.getElementById('roCvBody').innerHTML = '';
        }
    </script>
</body>
</html>
