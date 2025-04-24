<?php

// database/migrations/[timestamp]_create_notifications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('notification');
            $table->string('type'); // 'message', 'limit', 'monthlyexpense'
            $table->timestamp('created_at')->useCurrent(); // Only created_at
            
            // Indexes
            $table->index('user_id');
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};