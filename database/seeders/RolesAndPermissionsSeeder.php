<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 清除缓存
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 创建权限
        $permissions = [
            'access dashboard',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage menus',
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            'manage categories',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 创建角色并分配权限
        // 超级管理员
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // 管理员
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'access dashboard',
            'manage users',
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            'manage categories',
            'manage settings',
            'manage menus',
        ]);

        // 编辑
        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo([
            'access dashboard',
            'view articles',
            'create articles',
            'edit articles',
            'manage categories',
        ]);

        // 作者
        $role = Role::create(['name' => 'author']);
        $role->givePermissionTo([
            'access dashboard',
            'view articles',
            'create articles',
            'edit articles',
        ]);

        // 普通用户
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo([
            'access dashboard',
            'view articles',
        ]);

        // 创建测试用户并分配角色
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'super@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('super-admin');

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('admin');

        $user = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('editor');

        $user = User::create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');
    }
}
