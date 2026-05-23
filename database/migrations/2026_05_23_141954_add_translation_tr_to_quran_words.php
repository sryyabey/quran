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
        Schema::table('quran_words', function (Blueprint $table) {
            $table->text('translation_tr')->nullable()->after('translation');
        });
    }

    public function down(): void
    {
        Schema::table('quran_words', function (Blueprint $table) {
            $table->dropColumn('translation_tr');
        });
    }
};
