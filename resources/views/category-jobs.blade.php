    @extends('layouts.app')
    @section('content')

    <section class="py-16 px-6 lg:px-[5%] itext-text-man text-center relative overflow-hidden">
        <div class="container mx-auto px-5">
            <h2 class="text-4xl font-bold mb-2 text-center">
                {{ $category->name }}
            </h2>
            <p class="text-gray-600 mb-6 text-center">
                {{ $jobs->count() }} Active Jobs
            </p>
            
            @if(isset($subjects) && $subjects->count() > 0)
                <div class="mb-12">
                    <h3 class="text-xl font-semibold mb-6 text-center text-gray-700">Available Roles in {{ $category->name }}</h3>
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
                    <div class="job-card bg-primary-bg rounded-2xl shadow-lg p-6 hover:shadow-2xl transitionr" data-subject-id="{{ $job->subject_id }}">
                        <h3 class="text-xl font-bold mb-4">
                            {{ $job->title }}
                        </h3>
                        <p class="mb-2">
                        <b>Subject :</b>
                            {{ $job->subject->name ?? '-' }}
                        </p>
                        <p class="mb-2">
                        <b>Location :</b>
                            {{ $job->location->name ?? '-' }}
                        </p>
                        <p class="mb-2">
                        <b>Qualification :</b>
                            {{ $job->qualification->name ?? '-' }}
                        </p>
                        <p class="mb-4">
                        <b>Salary :</b>
                            {{ $job->salary }}
                        </p>
                        <a href="{{ route('jobs.show', $job->id) }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg inline-block">
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

       