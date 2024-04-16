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
        Schema::create('messages', function (Blueprint $table) {
            //
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text('text')->nullable();
            $table->timestamps();
            $table->integer('group_id')->nullable(true);
            $table->integer('conversation_id')->nullable(true);

            // create relation for group
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            // create relation for conversation
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            //
            Schema::dropIfExists('messages');
        });
    }
};
