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
        Schema::create('goods_category_goods', function (Blueprint $table) {
            $table->id();

            $table->integer('goods_id')->comment('商品ID');
            $table->integer('goods_category_id')->comment('商品分类ID');

            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('cascade');
            $table->foreign('goods_category_id')->references('id')->on('goods_category')->onDelete('cascade');

            $table->unique(['goods_id', 'goods_category_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_category_goods');
    }
};
