<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->bigInteger('nipd');
            $table->string('absence_type');
            $table->string('absence_note')->nullable();
            $table->string('attachment');
            $table->date('absence_date');
            $table->timestamps();

            $table->foreign('nipd')->references('nipd')->on('students')->onDelete('set default')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
