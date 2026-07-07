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
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="jobs-container">
                    @foreach($jobs as $job)
                    <div class="job-card bg-white border border-blue-100 rounded-2xl shadow-md p-6 hover:shadow-xl transition-shadow duration-300 text-left" data-subject-id="{{ $job->subject_id }}">
                        <h3 class="text-xl font-bold mb-4 text-blue-700 border-b border-blue-50 pb-3">
                            {{ $job->title }}
                        </h3>
                        <p class="mb-2 text-slate-800 text-sm">
                            <b class="text-slate-900">Subject:</b>
                            {{ $job->subject->name ?? '-' }}
                        </p>
                        <p class="mb-2 text-slate-800 text-sm">
                            <b class="text-slate-900">Location:</b>
                            {{ $job->location->name ?? '-' }}
                        </p>
                        <p class="mb-2 text-slate-800 text-sm">
                            <b class="text-slate-900">Qualification:</b>
                            {{ $job->qualification->name ?? '-' }}
                        </p>
                        <p class="mb-5 text-slate-800 text-sm">
                            <b class="text-slate-900">Salary:</b>
                            <span class="font-semibold text-blue-600">{{ $job->salary }}</span>
                        </p>
                        <a href="{{ route('jobs.show', $job->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg block text-center transition-colors">
                            View Details
                        </a>
                    </div>
                    @endforeach
                </div>
                
                <div id="no-filtered-jobs-msg" class="text-center py-10" style="display: none;">
                    <h3 class="text-xl font-semibold text-gray-600">
                        No jobs currently available for the selected role.
                    </h3>
                </div>
            @else
            <div class="text-center py-20">
                <h2 class="text-2xl font-semibold">
                    No Active Jobs Found
                </h2>
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
                            card.style.display = 'block';
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

       