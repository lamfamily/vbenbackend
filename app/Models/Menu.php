<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'route_name',
        'icon',
        'parent_id',
        'permission',
        'order',
        'type',
        'component',
        'route_name',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];


    /**
     * 获取父菜单
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * 获取子菜单
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('status', true)
            ->orderBy('order');
    }

    /**
     * 递归获取所有子菜单（包括孙子菜单等）
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * 检查当前用户是否有权限访问此菜单项
     */
    public function authorizeFor($user)
    {
        // 如果没有设置权限，则所有人可访问
        if (!$this->permission) {
            return true;
        }

        // 检查权限
        return $user->can($this->permission);
    }
}
