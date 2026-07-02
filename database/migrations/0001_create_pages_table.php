<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->longText('content')->nullable();
            $table->string('status', 20)->default('draft');
            $table->boolean('show_in_navigation')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('seo_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'slug']);
            $table->index(['project_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
