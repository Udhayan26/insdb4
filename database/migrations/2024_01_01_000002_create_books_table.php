<?php
// database/migrations/2024_01_01_000002_create_books_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->string('volume_number', 20);
            $table->string('title', 255);
            $table->string('subtitle')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('editor')->nullable();
            $table->string('language')->default('Sanskrit');
            $table->text('description')->nullable();
            $table->string('pdf_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('total_pages')->nullable();
            $table->string('isbn')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->unique(['series_id', 'volume_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
