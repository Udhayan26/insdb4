<?php
// database/migrations/2024_01_01_000003_create_inscriptions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('inscription_number', 50)->unique();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->text('original_text')->nullable();
            $table->text('translation')->nullable();
            $table->text('description')->nullable();
            $table->string('dynasty')->nullable();
            $table->string('king')->nullable();
            $table->date('date_era')->nullable();
            $table->string('language')->nullable();
            $table->string('script')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
