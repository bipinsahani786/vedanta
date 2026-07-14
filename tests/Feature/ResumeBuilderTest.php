<?php

test('resume builder page loads successfully', function () {
    $response = $this->get(route('resume.builder'));
    $response->assertStatus(200);
});

test('resume builder download downloads pdf successfully', function () {
    $resumeData = [
        'personal' => [
            'name' => 'John Doe',
            'title' => 'Math Teacher',
            'email' => 'john@example.com',
            'phone' => '+91 9876543210',
            'location' => 'Patna, Bihar',
            'photo' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ],
        'summary' => 'Dedicated educator.',
        'experience' => [
            [
                'title' => 'Teacher',
                'company' => 'School',
                'startDate' => '2020',
                'endDate' => 'Present',
                'description' => 'Taught math.'
            ]
        ],
        'education' => [
            [
                'degree' => 'B.Sc.',
                'institution' => 'University',
                'year' => '2019',
                'grade' => 'A'
            ]
        ],
        'skills' => ['Math', 'Algebra']
    ];

    $response = $this->post(route('resume.builder.download'), [
        'resume_data' => json_encode($resumeData)
    ]);

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
    $response->assertHeader('content-disposition', 'attachment; filename="Resume_John Doe.pdf"');
});
