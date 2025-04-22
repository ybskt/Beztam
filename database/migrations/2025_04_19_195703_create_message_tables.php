<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // User Messages (user to admin)
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('message');
            $table->tinyInteger('is_read')->default(0); // 0 = unread, 1 = read
            $table->timestamps();
        });

        // Admin Messages (admin to user)
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('message');
            $table->tinyInteger('is_read')->default(0); // 0 = unread, 1 = read
            $table->timestamps();
        });

        // Guest Messages (contact form)
        Schema::create('guest_messages', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->tinyInteger('is_read')->default(0); // 0 = unread, 1 = read
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_messages');
        Schema::dropIfExists('user_messages');
        Schema::dropIfExists('guest_messages');
    }
};