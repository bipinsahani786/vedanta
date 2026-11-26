<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_follow_ups', function (Blueprint $table) {
            if (! Schema::hasColumn('crm_follow_ups', 'next_action')) {
                $table->string('next_action', 255)->nullable()->after('notes');
            }
            if (! Schema::hasColumn('crm_follow_ups', 'follow_up_at')) {
                $table->dateTime('follow_up_at')->nullable()->after('follow_up_date');
            }
            if (! Schema::hasColumn('crm_follow_ups', 'reminder_sent_at')) {
                $table->timestamp('reminder_sent_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('crm_follow_ups', function (Blueprint $table) {
            $cols = array_values(array_filter([
                Schema::hasColumn('crm_follow_ups', 'next_action') ? 'next_action' : null,
                Schema::hasColumn('crm_follow_ups', 'follow_up_at') ? 'follow_up_at' : null,
                Schema::hasColumn('crm_follow_ups', 'reminder_sent_at') ? 'reminder_sent_at' : null,
            ]));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
