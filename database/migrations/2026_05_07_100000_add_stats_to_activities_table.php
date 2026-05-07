<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->integer('avg_hr')->nullable()->after('avg_speed');
            $table->integer('max_hr')->nullable()->after('avg_hr');
            $table->float('max_speed')->nullable()->after('max_hr');
            $table->integer('calories')->nullable()->after('max_speed');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['avg_hr', 'max_hr', 'max_speed', 'calories']);
        });
    }
};
