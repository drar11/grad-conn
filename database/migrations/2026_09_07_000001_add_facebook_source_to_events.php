<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('source', 30)->default('gradconn')->index();
            $table->string('source_post_id', 191)->nullable()->unique();
            $table->string('source_name')->nullable();
            $table->text('source_url')->nullable();
            $table->text('external_image_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('events', fn (Blueprint $table) => $table->dropColumn(['source', 'source_post_id', 'source_name', 'source_url', 'external_image_url']));
    }
};
