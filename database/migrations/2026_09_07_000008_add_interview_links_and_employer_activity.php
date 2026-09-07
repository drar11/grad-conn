<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('interviews') && ! Schema::hasColumn('interviews', 'meeting_link')) {
            Schema::table('interviews', fn (Blueprint $table) => $table->string('meeting_link', 2048)->nullable()->after('location'));
        }

        if (! Schema::hasTable('employer_activity_logs')) {
            Schema::create('employer_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('employer_id');
                $table->unsignedInteger('alumni_id')->nullable();
                $table->unsignedInteger('offer_id')->nullable();
                $table->string('action', 100);
                $table->text('details')->nullable();
                $table->string('course_filter', 100)->nullable();
                $table->string('batch_filter', 100)->nullable();
                $table->string('skill_search', 255)->nullable();
                $table->unsignedInteger('result_count')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->index(['employer_id', 'created_at']);
                $table->index('alumni_id');
                $table->index('offer_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employer_activity_logs')) Schema::drop('employer_activity_logs');
        if (Schema::hasTable('interviews') && Schema::hasColumn('interviews', 'meeting_link')) Schema::table('interviews', fn (Blueprint $table) => $table->dropColumn('meeting_link'));
    }
};
