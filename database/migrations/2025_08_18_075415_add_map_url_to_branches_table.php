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
        Schema::table('branches', function (Blueprint $table) {
            $table->string('map_url')->nullable();
            $table->string('latitude')->nullable()->change();
            $table->string('longitude')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('map_url');
            $table->string('latitude')->nullable(false)->change();
            $table->string('longitude')->nullable(false)->change();
        });
    }
};
