<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workouts_exercises', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable(false);
            $table->integer('type')->nullable()->default(null);
            $table->integer('series')->nullable()->default(0);
            $table->integer('repetitions')->nullable()->default(0);
            $table->dateTime('done_at')->nullable()->default(null);
            $table->unsignedBigInteger('workout_id')->nullable(false);
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('workout_id')->references('id')->on('workouts');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts_exercises');
    }
};
