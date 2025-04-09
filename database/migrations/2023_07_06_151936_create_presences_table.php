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
        Schema::dropIfExists('presences');
        Schema::create('presences', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->bigInteger('nipd');
            $table->string('learning_type');
            $table->timestamp('presence_in');
            $table->timestamp('presence_out')->nullable();
            $table->string('presence_in_note')->nullable();
            $table->string('presence_out_note')->nullable();
            $table->timestamps();

            $table->foreign('nipd')->references('nipd')->on('students')->onDelete('set default')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
