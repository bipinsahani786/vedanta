<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_alert_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->string('unsubscribe_token', 64)->unique();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_alert_dispatches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_post_id')->unique();
            $table->unsignedInteger('sent_count')->default(0);
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_alert_dispatches');
        Schema::dropIfExists('job_alert_subscribers');
    }
};
