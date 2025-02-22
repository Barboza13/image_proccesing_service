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
        Schema::table('images', function (Blueprint $table) {
            $table->string('path', 255);
            $table->string('format', 255);
            $table->integer('size');
            $table->string('resolution', 255);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('path');
            $table->dropColumn('format');
            $table->dropColumn('size');
            $table->dropColumn('resolution');
            $table->dropSoftDeletes();
        });
    }
};
