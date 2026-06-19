@extends('layouts.app')
@section('content')
<div class="pt-32 pb-12 px-6 lg:px-[5%] text-center border-b border-card-border bg-card-bg/30">
    <h4 class="text-accent-blue text-sm font-bold mb-3 uppercase tracking-wider">Legal</h4>
    <h1 class="text-4xl md:text-5xl font-extrabold text-text-main mb-6">Privacy Policy</h1>
    <p class="text-text-main opacity-70 max-w-2xl mx-auto">Last updated: October 2023</p>
</div>

<div class="py-16 px-6 lg:px-[5%] max-w-4xl mx-auto reveal">
    <div class="prose prose-sm md:prose-base prose-headings:text-text-main prose-p:text-text-main prose-p:opacity-80 prose-a:text-accent-blue max-w-none">
        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">1. Information We Collect</h2>
        <p class="mb-6 leading-relaxed">We collect information to provide better services to all our users. The types of personal information we collect include:</p>
        <ul class="list-disc pl-6 mb-6 text-text-main opacity-80 space-y-2">
            <li><strong>Contact Information:</strong> Name, email address, phone number, and physical address.</li>
            <li><strong>Professional Information:</strong> Resumes, educational qualifications, employment history, and certifications.</li>
            <li><strong>Technical Data:</strong> IP address, browser type, and usage data collected via cookies.</li>
        </ul>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">2. How We Use Your Information</h2>
        <p class="mb-6 leading-relaxed">Your data is strictly used for the purpose of facilitating employment opportunities. We use your information to:</p>
        <ul class="list-disc pl-6 mb-6 text-text-main opacity-80 space-y-2">
            <li>Match candidate profiles with suitable job openings.</li>
            <li>Communicate with you regarding interviews and job offers.</li>
            <li>Improve our website and services based on user feedback and analytics.</li>
        </ul>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">3. Data Sharing and Disclosure</h2>
        <p class="mb-6 leading-relaxed">We do not sell your personal data. We only share candidate profiles with verified partner institutions for recruitment purposes. We may also disclose information if required by law.</p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">4. Data Security</h2>
        <p class="mb-6 leading-relaxed">We implement robust security measures to protect your personal information from unauthorized access, alteration, or disclosure. However, no internet transmission is 100% secure.</p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">5. Contact Us</h2>
        <p class="mb-6 leading-relaxed">If you have any questions about this Privacy Policy, please contact us at <a href="mailto:info@vedantaplacementagency.in">info@vedantaplacementagency.in</a>.</p>
    </div>
</div>
@endsection