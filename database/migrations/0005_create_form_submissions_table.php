<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_definition_id')->constrained()->cascadeOnDelete();
            $table->json('data');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_spam')->default(false);
            $table->timestamp('created_at')->nullable();

            $table->index(['form_definition_id', 'is_spam']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
