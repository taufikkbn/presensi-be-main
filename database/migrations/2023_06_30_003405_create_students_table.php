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
        Schema::create('students', function (Blueprint $table) {
            $table->bigInteger('nipd')->primary();
            $table->string('user_id')->nullable()->default(null);
            $table->string('class_id')->nullable()->default(null);
            $table->string('name');
            $table->char('gender', 2);
            $table->string('pob');
            $table->date('dob');
            $table->string('religion');
            $table->text('address');
            $table->string('residence_type');
            $table->string('photo');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
