<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('monthly_budgets', function (Blueprint $table) {
            $table->unsignedInteger('occurrences')->default(1)->after('day_of_month');
        });

        Schema::table('monthly_expenses', function (Blueprint $table) {
            $table->unsignedInteger('occurrences')->default(1)->after('day_of_month');
        });
    }

    public function down()
    {
        Schema::table('monthly_budgets', function (Blueprint $table) {
            $table->dropColumn('occurrences');
        });

        Schema::table('monthly_expenses', function (Blueprint $table) {
            $table->dropColumn('occurrences');
        });
    }
};