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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64)->unique();
            $table->string('path')->comment('存储路径');
            $table->string('url')->comment('公开访问地址');
            $table->unsignedBigInteger('size')->comment('文件大小');
            $table->string('mime', 64)->comment('MIME 类型');
            $table->unsignedInteger('width')->nullable()->comment('宽度');
            $table->unsignedInteger('height')->nullable()->comment('高度');
            $table->string('ext', 10)->nullable()->comment('扩展名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
