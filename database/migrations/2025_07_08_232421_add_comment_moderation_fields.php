<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->enum('moderation_type', ['blur', 'comment', 'edit', 'remove', 'replace', 'wrap'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['moderation_type']);
        });
    }
};
