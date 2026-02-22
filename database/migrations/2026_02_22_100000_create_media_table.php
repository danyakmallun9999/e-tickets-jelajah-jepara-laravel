<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->string('url');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->string('source')->default('gallery'); // gallery, places, events, posts, etc.
            $table->timestamps();

            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
            $table->index('source');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
