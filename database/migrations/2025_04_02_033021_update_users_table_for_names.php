<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Add new columns first
            $table->string('first_name')->after('id')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            
            // 2. Create a virtual full_name column
            $table->string('full_name')->virtualAs('CONCAT(first_name, " ", last_name)');
        });

        // 3. Update existing data (if any)
        if (Schema::hasColumn('users', 'name')) {
            \DB::statement("
                UPDATE users 
                SET 
                    first_name = SUBSTRING_INDEX(name, ' ', 1),
                    last_name = IF(
                        LOCATE(' ', name) > 0, 
                        SUBSTRING(name, LOCATE(' ', name) + 1), 
                        ''
                    )
            ");
        }

        // 4. Drop old columns (if they exist)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            
            // 5. Make new columns non-nullable after migration
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Recreate old columns
            $table->string('name')->after('id');
            $table->string('phone')->nullable()->after('email');
            
            // Combine names back
            \DB::statement("UPDATE users SET name = CONCAT(first_name, ' ', last_name)");
            
            // Drop new columns
            $table->dropColumn(['first_name', 'last_name', 'full_name']);
        });
    }
};