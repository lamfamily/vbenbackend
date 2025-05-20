<?php

use Spatie\Permission\Models\Role;
use Modules\Leguo\App\Models\Goods;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Modules\Leguo\App\Models\GoodsCategory;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('make-user', function () {
    $users = \App\Models\User::factory()->count(10)->create();
    $this->info("User created!");
});


Artisan::command('set-permission-for-admin', function () {
    $admin_role = Role::where('name', 'admin')->first();

    $admin_role->givePermissionTo('manage roles');
    $admin_role->givePermissionTo('manage permissions');

    $permission_name = 'manage depts';
    // Permission::create(['name' => $permission_name]);

    $admin_role->givePermissionTo($permission_name);

    echo "<pre>";
    var_dump($admin_role->toArray(), $admin_role->getAllPermissions()->pluck('name')->toArray());
    echo "</pre>";
    exit();
});

Artisan::command('test-j5-trans', function() {
    echo "<pre>";
    var_dump(app()->getLocale(),
    j5_trans('登录成功'),
    j5_trans('The :attribute field is required.', ['attribute' => 'username']),
    j5_trans_choice('There is one apple|There are many apples', 1)
);
    echo "</pre>";
    exit();
});

Artisan::command('create-goods-category', function() {
    GoodsCategory::factory()->count(10)->create();

    $this->info("GoodsCategory created!");
});

Artisan::command('create-goods', function() {
    Goods::factory()->count(10)->create();

    $this->info("Goods created!");
});

Artisan::command('test-query-deleted-goods', function() {
    $goods_list1 = Goods::where('status', -1)->get()->toArray();

    $goods_list2 = Goods::withoutGlobalScope('active')->where('status', -1)->get()->toArray();

    echo "<pre>";
    var_dump($goods_list1, $goods_list2);
    echo "</pre>";
    exit();
});
