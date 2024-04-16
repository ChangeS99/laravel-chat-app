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
        Schema::create('conversations_messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('message_id')->unsigned();
            $table->integer('conversation_id')->unsigned();


            // create relation with conversations
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            // create relation with messages
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations_messages');
    }
};
