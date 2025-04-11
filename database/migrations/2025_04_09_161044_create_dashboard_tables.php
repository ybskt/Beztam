<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users table (already exists, but we'll reference it)
        
        // Budgets table (one-time budgets)
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->decimal('free_amount', 10, 2)->default(0);
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });

        // Monthly budgets table
        Schema::create('monthly_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->unsignedTinyInteger('day_of_month')->comment('Day of month when budget is applied');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });

        // Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('limit_amount', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });

        // Expenses table (one-time expenses)
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'category_id']);
        });

        // Monthly expenses table
        Schema::create('monthly_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->unsignedTinyInteger('day_of_month')->comment('Day of month when expense occurs');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'category_id']);
        });

        // Savings table
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('savings');
        Schema::dropIfExists('monthly_expenses');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('monthly_budgets');
        Schema::dropIfExists('budgets');
    }
};