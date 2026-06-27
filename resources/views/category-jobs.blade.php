    @extends('layouts.app')
    @section('content')

    <section class="py-16 px-6 lg:px-[5%] itext-text-man text-center relative overflow-hidden">
        <div class="container mx-auto px-5">
            <h2 class="text-4xl font-bold mb-2 text-center">
                {{ $category->name }}
            </h2>
            <p class="text-gray-600 mb-10 text-center">
                {{ $jobs->count() }} Active Jobs
            </p>
            @if($jobs->count()>0)
                <div class="grid md:grid-cols-2  lg:grid-cols-3 gap-8">
                    @foreach($jobs as $job)
                    <div class="bg-primary-bg rounded-2xl shadow-lg p-6 hover:shadow-2xl transitionr">
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
                        <a href="#" class="bg-blue-600 text-white px-5 py-2 rounded-lg">
                            Now
                        </a>
                    </div>
                @endforeach
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
    @endsection

       