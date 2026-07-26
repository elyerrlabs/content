<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('disk');
            $table->string('path');
            $table->string('name')->index();
            $table->string('original_name')->nullable();
            $table->string('mime_type', 100)->index();
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_files');
    }
};
