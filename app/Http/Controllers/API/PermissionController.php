<?php

namespace App\Http\Controllers\API;

use App\Enums\APICodeEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:manage permissions');
    }

    /**
     * 获取所有权限
     */
    public function index()
    {
        $permissions = Permission::all();
        return api_res(APICodeEnum::SUCCESS, __('获取权限列表成功'), [
            'permissions' => PermissionResource::collection($permissions)
        ]);
    }

    /**
     * 创建新权限
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $permission = Permission::create(['name' => $request->name]);

        return api_res(APICodeEnum::SUCCESS, __('权限创建成功'), [
            'permission' => new PermissionResource($permission)
        ]);
    }

    /**
     * 获取指定权限
     */
    public function show(Permission $permission)
    {
        return api_res(APICodeEnum::SUCCESS, __('获取权限成功'), [
            'permission' => new PermissionResource($permission)
        ]);
    }

    /**
     * 更新指定权限
     */
    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $permission->update(['name' => $request->name]);

        return api_res(APICodeEnum::SUCCESS, __('权限更新成功'), [
            'permission' => new PermissionResource($permission)
        ]);
    }

    /**
     * 删除指定权限
     */
    public function destroy(Permission $permission)
    {
        // 检查是否为系统关键权限
        $criticalPermissions = ['manage roles', 'manage permissions', 'manage users', 'manage menus'];
        if (in_array($permission->name, $criticalPermissions)) {
            return api_res(APICodeEnum::EXCEPTION, __('不能删除系统关键权限'));
        }

        $permission->delete();

        return api_res(APICodeEnum::SUCCESS, __('权限删除成功'));
    }
}
