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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // there will be only two member in the conversation
            $table->integer('member_1')->unsigned();
            $table->integer('member_2')->unsigned();

            // relate it to user
            $table->foreign('member_1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('member_2')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
