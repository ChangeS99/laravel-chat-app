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
        Schema::create('groups_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('groups_id');
            $table->integer('users_id');

            // create relation with the tables
            $table->foreign('groups_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups_users');
    }
};
