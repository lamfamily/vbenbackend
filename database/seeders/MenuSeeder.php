<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // 清空菜单表
        Menu::truncate();

        // 创建菜单
        $menus = [
            // 仪表盘 - 需要 access dashboard 权限
            [
                'name' => '仪表盘',
                'slug' => 'dashboard',
                'url' => '/dashboard',
                'icon' => 'fa fa-dashboard',
                'permission' => 'access dashboard',
                'order' => 1,
            ],

            // 用户管理 - 需要 manage users 权限
            [
                'name' => '用户管理',
                'slug' => 'users',
                'url' => '/users',
                'icon' => 'fa fa-users',
                'permission' => 'manage users',
                'order' => 2,
            ],

            // 内容管理 - 父菜单
            [
                'name' => '内容管理',
                'slug' => 'content',
                'icon' => 'fa fa-file-text',
                'order' => 3,
            ],

            // 系统管理 - 父菜单
            [
                'name' => '系统管理',
                'slug' => 'system',
                'icon' => 'fa fa-cog',
                'order' => 4,
            ],
        ];

        // 插入顶级菜单
        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        // 内容管理下的子菜单
        $contentId = Menu::where('slug', 'content')->first()->id;

        $contentSubmenus = [
            // 文章列表 - 需要 view articles 权限
            [
                'name' => '文章列表',
                'slug' => 'articles',
                'url' => '/articles',
                'icon' => 'fa fa-list',
                'parent_id' => $contentId,
                'permission' => 'view articles',
                'order' => 1,
            ],

            // 创建文章 - 需要 create articles 权限
            [
                'name' => '创建文章',
                'slug' => 'articles.create',
                'url' => '/articles/create',
                'icon' => 'fa fa-plus',
                'parent_id' => $contentId,
                'permission' => 'create articles',
                'order' => 2,
            ],

            // 分类管理 - 需要 manage categories 权限
            [
                'name' => '分类管理',
                'slug' => 'categories',
                'url' => '/categories',
                'icon' => 'fa fa-tag',
                'parent_id' => $contentId,
                'permission' => 'manage categories',
                'order' => 3,
            ],
        ];

        foreach ($contentSubmenus as $submenu) {
            Menu::create($submenu);
        }

        // 系统管理下的子菜单
        $systemId = Menu::where('slug', 'system')->first()->id;

        $systemSubmenus = [
            // 角色管理 - 需要 manage roles 权限
            [
                'name' => '角色管理',
                'slug' => 'roles',
                'url' => '/roles',
                'icon' => 'fa fa-user-tag',
                'parent_id' => $systemId,
                'permission' => 'manage roles',
                'order' => 1,
            ],

            // 权限管理 - 需要 manage permissions 权限
            [
                'name' => '权限管理',
                'slug' => 'permissions',
                'url' => '/permissions',
                'icon' => 'fa fa-key',
                'parent_id' => $systemId,
                'permission' => 'manage permissions',
                'order' => 2,
            ],

            // 菜单管理 - 需要 manage menus 权限
            [
                'name' => '菜单管理',
                'slug' => 'menus',
                'url' => '/menus',
                'icon' => 'fa fa-bars',
                'parent_id' => $systemId,
                'permission' => 'manage menus',
                'order' => 3,
            ],

            // 系统设置 - 需要 manage settings 权限
            [
                'name' => '系统设置',
                'slug' => 'settings',
                'url' => '/settings',
                'icon' => 'fa fa-cogs',
                'parent_id' => $systemId,
                'permission' => 'manage settings',
                'order' => 4,
            ],
        ];

        foreach ($systemSubmenus as $submenu) {
            Menu::create($submenu);
        }
    }
}
