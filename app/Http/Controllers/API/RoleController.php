<?php

namespace App\Http\Controllers\API;

use App\Enums\APICodeEnum;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:manage roles');
    }

    /**
     * 获取所有角色
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return api_res(APICodeEnum::SUCCESS, __('获取角色列表成功'), [
            'items' => RoleResource::collection($roles)
        ]);
    }

    /**
     * 创建新角色
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return api_res(APICodeEnum::SUCCESS, __('角色创建成功'), [
            'role' => new RoleResource($role->load('permissions'))
        ]);
    }

    /**
     * 获取指定角色
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        return api_res(APICodeEnum::SUCCESS, __('获取角色成功'), [
            'role' => new RoleResource($role)
        ]);
    }

    /**
     * 更新指定角色
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return api_res(APICodeEnum::SUCCESS, __('角色更新成功'), [
            'role' => new RoleResource($role->load('permissions'))
        ]);
    }

    /**
     * 删除指定角色
     */
    public function destroy(Role $role)
    {
        // 防止删除超级管理员角色
        if ($role->name === 'super-admin') {
            // return response()->json(['error' => '不能删除超级管理员角色'], 403);
            return api_res(APICodeEnum::EXCEPTION, __('不能删除超级管理员角色'));
        }

        $role->delete();

        return api_res(APICodeEnum::SUCCESS, __('角色删除成功'));
    }
}
