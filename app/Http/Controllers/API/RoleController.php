<?php

namespace App\Http\Controllers\API;

use App\Enums\APICodeEnum;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:System:Role:List');
    }

    /**
     * 获取所有角色
     */
    public function index()
    {
        // if (!auth()->user()->hasPermissionTo('test1')) {
        //     return api_res(APICodeEnum::EXCEPTION, __('没有权限'));
        // }

        // $roles = Role::with('permissions')->get();
        // return api_res(APICodeEnum::SUCCESS, __('获取角色列表成功'), [
        //     'items' => RoleResource::collection($roles)
        // ]);

        $page = request('page', 1);
        $pageSize = request('pageSize', 15);
        $name = request('name', '');
        $status = request('status', '');
        $start_time = request('startTime', '');
        $end_time = request('endTime', '');

        $query = Role::with('permissions');

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($status != '') {
            $query->where('status', $status);
        }

        if ($start_time) {
            $query->where('created_at', '>=', $start_time);
        }

        if ($end_time) {
            $query->where('created_at', '<=', $end_time . ' 23:59:59');
        }

        $roles = $query->paginate($pageSize, ['*'], 'page', $page);

        return api_res(APICodeEnum::SUCCESS, __('获取角色列表成功'), [
            'items' => RoleResource::collection($roles),
            'total' => $roles->total(),
            'page' => $page,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * 创建新角色
     */
    public function store(Request $request)
    {
        $all_data = $request->all();

        $validator = Validator::make($all_data, [
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();

    $role = Role::create($all_data);

        if ($request->has('permissions')) {
            // $permissions = Permission::whereIn('name', $request->permissions)->get();

            // $request->permissions 是menu表的id
            $permission_code_arr = Menu::whereIn('id', $request->permissions)->pluck('permission')->toArray();
            $permissions = Permission::whereIn('name', $permission_code_arr)->get();

            $role->syncPermissions($permissions);
        }

        DB::commit();

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
        $all_data = $request->all();

        $validator = Validator::make($all_data, [
            // 'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            // 'permissions' => 'array',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $role->update($all_data);

        if ($request->has('permissions')) {
            // $permissions = Permission::whereIn('name', $request->permissions)->get();

            // $request->permissions 是menu表的id
            $permission_code_arr = Menu::whereIn('id', $request->permissions)->pluck('permission')->toArray();

            $permissions = Permission::whereIn('name', $permission_code_arr)->get();

            $role->syncPermissions($permissions);
        }

        return api_res(APICodeEnum::SUCCESS, __('角色更新成功'), [
            'role' => new RoleResource($role->load('permissions'))
        ]);
    }

    /**
     * 删除指定角色
     */
    public function destroy(Request $request, int $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return api_res(APICodeEnum::EXCEPTION, __('角色不存在'));
        }

        // 检查角色是否有用户
        if ($role->users()->count() > 0) {
            return api_res(APICodeEnum::EXCEPTION, __('角色下有用户，不能删除'));
        }

        // 防止删除超级管理员角色
        if ($role->name === 'super-admin') {
            return api_res(APICodeEnum::EXCEPTION, __('不能删除超级管理员角色'));
        }

        $role->delete();

        return api_res(APICodeEnum::SUCCESS, __('角色删除成功'));
    }
}
