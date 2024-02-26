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
        Schema::create('falls', function (Blueprint $table) {
            $table->id();

            // The user that fell
            $table->foreignId('user_id')->constrained()->references('id')->on('users')->onDelete('cascade');

            $table->string('location');

            // Cordinates to use later in Google maps
            $table->double("latitude");
            $table->double("longitude");

            // Severity of the fall
            $table->enum("severity", ["danger", "info", "ok"])->nullable();

            // The time the fall happened
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('falls');
    }
};
