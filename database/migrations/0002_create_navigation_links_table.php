<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('label', 255);
            $table->string('url', 255)->nullable();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_links')->cascadeOnDelete();
            $table->string('section', 20)->default('header');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_links');
    }
};
