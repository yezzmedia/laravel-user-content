<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('source', 255);
            $table->string('target', 255);
            $table->smallInteger('status_code')->default(301);
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['project_id', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
