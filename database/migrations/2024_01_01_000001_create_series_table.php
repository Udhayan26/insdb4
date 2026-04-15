<?php
// database/migrations/2024_01_01_000001_create_series_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // ARIE, SII, TNARCH
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('publisher')->nullable();
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            $table->integer('total_volumes')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
