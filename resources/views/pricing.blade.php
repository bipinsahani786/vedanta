@extends('layouts.app')
@section('content')

    <div class="pt-32 pb-12 px-6 lg:px-[5%] text-center border-b border-card-border bg-card-bg/30">
        <h4 class="text-accent-blue text-sm font-bold mb-3 uppercase tracking-wider">Legal</h4>
        <h1 class="text-4xl md:text-5xl font-extrabold text-text-main mb-6">Pricing & Service Charges Policy</h1>
        <p class="text-text-main opacity-70 max-w-2xl mx-auto">Last updated: October 2023</p>
    </div>

    <div class="py-16 px-6 lg:px-[5%] max-w-4xl mx-auto reveal">
        <div
            class="prose prose-sm md:prose-base prose-headings:text-text-main prose-p:text-text-main prose-p:opacity-80 prose-a:text-accent-blue max-w-none">
            <h2 class="text-2xl font-bold text-text-main mt-8 mb-4"> Disclaimer</h2>
            <p class="mb-6 leading-relaxed">At Vedanta Placement Agency, we believe in maintaining complete transparency
                regarding
                our pricing, registration plans, and placement service charges. This Pricing & Service
                Charges Policy explains the fees applicable for our recruitment services.
            </p>
            <p>By registering with Vedanta Placement Agency or using our services, you acknowledge that
                you have read and agreed to this Pricing Policy. </p>
        </div>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">1. Registration Plans</h2>

        <p class="text-text-secondary leading-7 mb-4">
            Vedanta Placement Agency offers two registration options for candidates.
        </p>

        <p class="text-text-secondary font-semibold mb-2">
            Standard Registration
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Registration Fee: ₹500
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Includes:
        </p>

        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li class="text-text-secondary leading-7">Candidate profile creation</li>
            <li class="text-text-secondary leading-7">Resume screening</li>
            <li class="text-text-secondary leading-7">Database listing</li>
            <li class="text-text-secondary leading-7">Basic recruitment assistance</li>
            <li class="text-text-secondary leading-7">Interview coordination</li>
            <li class="text-text-secondary leading-7">Standard application processing</li>
        </ul>

        <p class="text-text-secondary font-semibold mb-2">
            Premium Registration
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Registration Fee: ₹1,000
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Includes:
        </p>

        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li class="text-text-secondary leading-7">Everything included in Standard Registration</li>
            <li class="text-text-secondary leading-7">Priority profile processing</li>
            <li class="text-text-secondary leading-7">Priority interview coordination</li>
            <li class="text-text-secondary leading-7">Enhanced profile visibility (subject to employer requirements)</li>
            <li class="text-text-secondary leading-7">Faster recruitment assistance</li>
            <li class="text-text-secondary leading-7">Dedicated recruitment support</li>
        </ul>

        <p class="text-text-secondary leading-7 mb-4">
            Important: Premium Registration improves the priority of recruitment assistance but does not guarantee job
            placement, interview selection, or employment.
        </p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">2. What the Registration Fee Covers</h2>

        <p class="text-text-secondary leading-7 mb-4">
            The registration fee is charged to cover administrative and recruitment-related services, including:
        </p>

        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li class="text-text-secondary leading-7">Candidate registration</li>
            <li class="text-text-secondary leading-7">Profile verification</li>
            <li class="text-text-secondary leading-7">Document management</li>
            <li class="text-text-secondary leading-7">Resume review</li>
            <li class="text-text-secondary leading-7">Recruitment database maintenance</li>
            <li class="text-text-secondary leading-7">Employer profile sharing</li>
            <li class="text-text-secondary leading-7">Interview scheduling</li>
            <li class="text-text-secondary leading-7">Candidate support</li>
            <li class="text-text-secondary leading-7">Administrative processing</li>
        </ul>

        <p class="text-text-secondary leading-7 mb-4">
            The registration fee is not a placement fee and is separate from any placement service charges.
        </p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">3. Placement Service Charges</h2>

        <p class="text-text-secondary leading-7 mb-4">
            Vedanta Placement Agency follows a "Pay After Successful Joining" model.
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            No placement service charges are collected before a candidate successfully joins an employer through our
            recruitment services.
        </p>

        <p class="text-text-secondary font-semibold mb-2">
            Teaching Staff
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            For candidates placed in teaching positions (including PRT, TGT, PGT, Principal, Vice Principal, Academic
            Coordinator, Head of Boarding, and other teaching-related roles):
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Placement Service Charge: 50% of the candidate's first month's Gross Salary.
        </p>

        <p class="text-text-secondary font-semibold mb-2">
            Example:
        </p>

        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-blue-900 ">First Month Gross Salary</th>
                        <th class="border px-4 py-2 text-blue-900">Service Charge</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">₹30,000</td>
                        <td class="border px-4 py-2">₹15,000</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">₹40,000</td>
                        <td class="border px-4 py-2">₹20,000</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">₹50,000</td>
                        <td class="border px-4 py-2">₹25,000</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">₹60,000</td>
                        <td class="border px-4 py-2">₹30,000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="text-text-secondary font-semibold mb-2">
            Non-Teaching Staff
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            For candidates placed in non-teaching positions (including Receptionist, Accountant, Admission Counsellor,
            Office Staff, Hostel Staff, Administrative Staff, HR, Librarian, IT Support, and similar roles):
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Placement Service Charge: 66.67% of the candidate's first month's Gross Salary (equivalent to 20 days' salary).
        </p>

        <p class="text-text-secondary font-semibold mb-2">
            Example:
        </p>

        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-blue-900">First Month Gross Salary</th>
                        <th class="border px-4 py-2 text-blue-900">Service Charge</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">₹30,000</td>
                        <td class="border px-4 py-2">₹20,000</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">₹45,000</td>
                        <td class="border px-4 py-2">₹30,000</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">₹60,000</td>
                        <td class="border px-4 py-2">₹40,000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">4. When Service Charges Become Payable</h2>

        <p class="text-text-secondary leading-7 mb-4">
            Placement service charges become payable only after:
        </p>

        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li class="text-text-secondary leading-7">The candidate has accepted the offer issued by the employer.</li>
            <li class="text-text-secondary leading-7">The candidate has officially joined the institution through Vedanta
                Placement Agency.</li>
            <li class="text-text-secondary leading-7">The joining has been confirmed.</li>
        </ul>

        <p class="text-text-secondary leading-7 mb-4">
            No placement service charge is collected before successful joining.
        </p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">5. Payment Methods</h2>

        <p class="text-text-secondary leading-7 mb-4">
            Registration fees may be paid using the payment options available on our website, including:
        </p>

        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li class="text-text-secondary leading-7">UPI</li>
            <li class="text-text-secondary leading-7">Credit Cards</li>
            <li class="text-text-secondary leading-7">Debit Cards</li>
            <li class="text-text-secondary leading-7">Net Banking</li>
            <li class="text-text-secondary leading-7">Digital Wallets</li>
            <li class="text-text-secondary leading-7">Other approved online payment methods</li>
        </ul>

        <p class="text-text-secondary leading-7 mb-4">
            All online payments are processed through secure and authorized payment gateway partners.
        </p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">6. Taxes</h2>

        <p class="text-text-secondary leading-7 mb-4">
            All fees displayed on the website are exclusive of applicable taxes unless expressly stated otherwise. Any
            statutory taxes, duties, or levies required under applicable law will be charged separately where applicable.
        </p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">7. Changes to Pricing</h2>

        <p class="text-text-secondary leading-7 mb-4">
            Vedanta Placement Agency reserves the right to revise or update its pricing structure, registration plans, or
            service charges at any time.
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Any changes will apply prospectively and will not affect payments already successfully completed unless required
            by applicable law.
        </p>

        <h2 class="text-2xl font-bold text-text-main mt-8 mb-4">8. Contact Us</h2>

        <p class="text-text-secondary leading-7 mb-4">
            If you have any questions regarding our pricing or service charges, please contact us:
        </p>

        <p class="text-text-secondary leading-7 mb-2">
            Vedanta Placement Agency
        </p>

        <p class="text-text-secondary leading-7 mb-2">
            Email:
            <a href="mailto:vedantaplacementagency@gmail.com" class="text-primary hover:underline break-all">
                vedantaplacementagency@gmail.com
            </a>
        </p>

        <p class="text-text-secondary leading-7 mb-2">
            Phone:
            <a href="tel:+917070938975" class="text-primary hover:underline">
                +91 7070938975
            </a>
        </p>

        <p class="text-text-secondary leading-7 mb-4">
            Website:
            <a href="https://www.vedantaplacementagency.in" target="_blank" rel="noopener noreferrer"
                class="text-primary hover:underline break-all">
                www.vedantaplacementagency.in
            </a>
        </p>



    </div>


@endsection