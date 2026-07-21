    @extends('layouts.app')
    @section('content')

    <section class="bg-slate-50 py-16 px-6 lg:px-[5%] itext-text-man text-center relative overflow-hidden">
        <div class="container mx-auto px-5">
            <h2 class="text-4xl font-extrabold mb-2 text-center text-slate-900">
                {{ $category->name }}
            </h2>
            <p class="text-blue-600 font-medium mb-6 text-center">
                {{ $jobs->count() }} Active Jobs
            </p>
            
            @if(isset($subjects) && $subjects->count() > 0)
                <div class="mb-12">
                    <h3 class="text-xl font-bold mb-6 text-center text-slate-800">Available Roles in <span class="text-blue-600">{{ $category->name }}</span></h3>
                    <div class="flex flex-wrap justify-center gap-3">
                        @foreach($subjects as $subject)
                            <button type="button" data-subject-id="{{ $subject->id }}" class="subject-filter-btn bg-white border border-gray-200 shadow-sm rounded-full px-5 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors cursor-pointer">
                                {{ $subject->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($jobs->count()>0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="jobs-container">
                    @foreach($jobs as $job)
                    <div class="job-card bg-white border border-gray-200 hover:border-blue-300 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full relative group overflow-hidden" data-subject-id="{{ $job->subject_id }}">
                        
                        <!-- Top decorative accent -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <div class="p-6 flex-grow flex flex-col">
                            <!-- Job Title & Badges -->
                            <div class="flex justify-between items-start mb-4 gap-2">
                                <h3 class="text-xl font-bold text-gray-900 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ $job->title }}
                                </h3>
                                @if($job->job_type)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 whitespace-nowrap">
                                        {{ ucfirst($job->job_type) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Key details with Icons -->
                            <div class="space-y-3 mb-6 flex-grow text-sm text-gray-600">
                                @if($job->subject)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-blue-500 shrink-0">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                    <span class="truncate" title="{{ $job->subject->name }}">
                                        {{ $job->subject->name }}
                                    </span>
                                </div>
                                @endif

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-red-400 shrink-0">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <span class="truncate">
                                        {{ $job->city?->name ?? 'Anywhere' }}, {{ $job->state?->name ?? 'Any State' }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-amber-500 shrink-0">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <span class="truncate" title="{{ $job->qualification->name ?? 'Not Specified' }}">
                                        {{ $job->qualification->name ?? 'Not Specified' }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-green-500 shrink-0">
                                        <i class="fas fa-rupee-sign"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900 truncate">
                                        {{ $job->salary_range ?? 'Not Disclosed' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Action -->
                        <div class="p-6 pt-0 mt-auto border-t border-gray-100 bg-gray-50/50">
                            <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center justify-center w-full bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold px-5 py-2.5 rounded-xl transition-colors duration-300 gap-2 mt-4">
                                View Details <i class="fas fa-arrow-right text-sm"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div id="no-filtered-jobs-msg" class="text-center py-16" style="display: none;">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Roles Found</h3>
                    <p class="text-gray-500">We couldn't find any active jobs for this specific role right now.</p>
                </div>
            @else
            <div class="text-center py-24 bg-white rounded-3xl shadow-sm border border-gray-100 max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 text-blue-500">
                    <i class="fas fa-briefcase text-4xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-800 mb-3">
                    No Active Jobs Found
                </h2>
                <p class="text-gray-500 max-w-md mx-auto">Check back soon! We regularly update our job listings with new opportunities.</p>
            </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.subject-filter-btn');
            const jobCards = document.querySelectorAll('.job-card');
            const noJobsMessage = document.getElementById('no-filtered-jobs-msg');
            let selectedSubject = null;

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const subjectId = this.getAttribute('data-subject-id');
                    
                    // Toggle selection
                    if (selectedSubject === subjectId) {
                        selectedSubject = null; // deselect
                    } else {
                        selectedSubject = subjectId;
                    }

                    // Update UI for buttons
                    filterBtns.forEach(b => {
                        if (b.getAttribute('data-subject-id') === selectedSubject) {
                            b.classList.remove('bg-white', 'text-gray-700', 'hover:bg-blue-50', 'hover:text-blue-600');
                            b.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md');
                        } else {
                            b.classList.add('bg-white', 'text-gray-700', 'hover:bg-blue-50', 'hover:text-blue-600');
                            b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md');
                        }
                    });

                    // Filter jobs
                    let visibleCount = 0;
                    jobCards.forEach(card => {
                        if (!selectedSubject || card.getAttribute('data-subject-id') === selectedSubject) {
                            card.style.display = 'flex';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Show/hide no jobs message
                    if (visibleCount === 0 && jobCards.length > 0) {
                        noJobsMessage.style.display = 'block';
                    } else if (noJobsMessage) {
                        noJobsMessage.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @endsection

       