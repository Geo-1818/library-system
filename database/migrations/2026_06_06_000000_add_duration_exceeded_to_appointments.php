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
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('duration_exceeded')->default(false)->after('status');
            $table->timestamp('exceeded_notified_at')->nullable()->after('duration_exceeded');
            $table->text('exceeded_message')->nullable()->after('exceeded_notified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['duration_exceeded', 'exceeded_notified_at', 'exceeded_message']);
        });
    }
};
