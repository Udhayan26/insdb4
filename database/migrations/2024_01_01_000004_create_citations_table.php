<?php
// database/migrations/2024_01_01_000004_create_citations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_inscription_id')->constrained('inscriptions')->onDelete('cascade');
            $table->foreignId('target_inscription_id')->constrained('inscriptions')->onDelete('cascade');
            $table->string('citation_type', 50)->default('reference');
            $table->text('citation_text')->nullable();
            $table->string('page_number')->nullable();
            $table->timestamps();
            
            $table->unique(['source_inscription_id', 'target_inscription_id'], 'unique_citation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citations');
    }
};
