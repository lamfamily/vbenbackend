<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'leguo';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imageables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained('images')->onDelete('cascade');
            $table->unsignedBigInteger('imageable_id');
            $table->string('imageable_type', 100);
            $table->string('type', 30)->nullable()->comment('图片类型，如cover/avatar');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['image_id', 'imageable_id', 'imageable_type', 'type'], 'u_imageable');
            $table->index(['imageable_id', 'imageable_type'], 'idx_imageable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imageables');
    }
};
