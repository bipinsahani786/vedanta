<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('employer_profiles', 'agreement_path')) {
                $table->string('agreement_path', 500)->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('employer_profiles', 'agreement_status')) {
                $table->string('agreement_status', 30)->default('not_uploaded')->index()->after('agreement_path');
            }
            if (!Schema::hasColumn('employer_profiles', 'agreement_uploaded_at')) {
                $table->timestamp('agreement_uploaded_at')->nullable()->after('agreement_status');
            }
            if (!Schema::hasColumn('employer_profiles', 'agreement_reviewed_at')) {
                $table->timestamp('agreement_reviewed_at')->nullable()->after('agreement_uploaded_at');
            }
            if (!Schema::hasColumn('employer_profiles', 'agreement_reviewed_by')) {
                $table->foreignId('agreement_reviewed_by')->nullable()->after('agreement_reviewed_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('employer_profiles', 'agreement_rejection_reason')) {
                $table->text('agreement_rejection_reason')->nullable()->after('agreement_reviewed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('employer_profiles', 'agreement_reviewed_by')) {
                $table->dropConstrainedForeignId('agreement_reviewed_by');
            }

            $dropCols = [];
            foreach ([
                'agreement_path',
                'agreement_status',
                'agreement_uploaded_at',
                'agreement_reviewed_at',
                'agreement_rejection_reason',
            ] as $col) {
                if (Schema::hasColumn('employer_profiles', $col)) {
                    $dropCols[] = $col;
                }
            }
            if (!empty($dropCols)) {
                $table->dropColumn($dropCols);
            }
        });
    }
};
