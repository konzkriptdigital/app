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
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('name')->nullable()->change();
            $table->longText('avatar')->nullable();
            $table->tinyInteger('is_finish')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('facebook_id');
            $table->dropColumn('google_id');
            $table->dropColumn('avatar');
            $table->dropColumn('is_finish');
            $table->string('name')->nullable(false)->change();
        });
    }
};
