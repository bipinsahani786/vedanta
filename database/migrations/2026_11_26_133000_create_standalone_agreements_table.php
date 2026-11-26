<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standalone_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_code', 32)->unique();
            $table->string('token', 80)->unique();
            $table->string('recipient_email')->index();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone', 30)->nullable();
            $table->string('subject')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->unsignedInteger('resent_count')->default(0);
            $table->timestamp('resent_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('signed_at')->nullable();
            $table->string('signer_name')->nullable();
            $table->string('signature_type', 20)->nullable();
            $table->longText('signature_data')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->string('signer_ip', 45)->nullable();
            $table->text('signer_user_agent')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standalone_agreements');
    }
};
