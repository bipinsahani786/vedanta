<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$keep = ['English', 'Hindi', 'Mathematics', 'EVS', 'Science', 'Social Studies', 'Computer', 'General Teacher', 'Art & Craft'];
App\Models\Subject::whereNotIn('name', $keep)->delete();

$allSubjectIds = App\Models\Subject::pluck('id');
$allCategoryIds = App\Models\Category::pluck('id');

foreach ($allCategoryIds as $catId) {
    $cat = App\Models\Category::find($catId);
    $cat->subjects()->sync($allSubjectIds);
}

echo "Extra subjects deleted and synced.\n";
