<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // 菜单名称
            $table->string('slug')->unique();         // 唯一标识
            $table->string('url')->nullable();        // URL地址
            $table->string('route_name')->nullable(); // 路由名称
            $table->string('icon')->nullable();       // 图标
            $table->unsignedBigInteger('parent_id')->nullable(); // 父菜单ID
            $table->string('permission')->nullable(); // 关联权限
            $table->integer('order')->default(0);     // 排序
            $table->boolean('active')->default(true); // 是否激活
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
