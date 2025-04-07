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
        Schema::create('dept', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // 父菜单ID
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0); // 排序
            // remark
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('dept')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dept');
    }
};
