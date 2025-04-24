<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Enums\APICodeEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:System:User:List');
    }

    /**
     * 获取所有用户
     */
    public function index()
    {
        // $users = User::with('roles')->paginate(15);
        // return api_res(APICodeEnum::SUCCESS, __('获取用户列表成功'), [
        //     'items' => UserResource::collection($users)
        // ]);

        $page = request('page', 1);
        $pageSize = request('pageSize', 15);
        $name = request('name', '');
        $email = request('email', '');
        $status = request('status', '');
        $start_time = request('startTime', '');
        $end_time = request('endTime', '');
        $role_name = request('role_name', '');

        $query = User::with('roles');

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($email) {
            $query->where('email', 'like', '%' . $email . '%');
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

        if ($role_name) {
            $query->whereHas('roles', function ($q) use ($role_name) {
                $q->where('name', $role_name);
            });
        }

        $users = $query->paginate($pageSize, ['*'], 'page', $page);

        return api_res(APICodeEnum::SUCCESS, __('获取用户列表成功'), [
            'items' => UserResource::collection($users),
            'total' => $users->total(),
            'page' => $page,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * 创建新用户
     */
    public function store(Request $request)
    {
        $all_data = $request->all();

        if (isset($all_data['realName'])) {
            $all_data['name'] = $all_data['realName'];
        }

        $validator = Validator::make($all_data, [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            // 'roles' => 'array',
            'role' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $all_data['password'] = Hash::make($all_data['password']);

        $user = User::create($all_data);

        // if ($request->has('roles')) {
        //     $user->syncRoles($request->roles);
        // }

        if ($request->has('role')) {
            $user->syncRoles([$request->role]);
        }

        return api_res(APICodeEnum::SUCCESS, __('用户创建成功'), [
            'user' => new UserResource($user->load('roles'))
        ]);
    }

    /**
     * 获取指定用户
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions', 'permissions']);
        return api_res(APICodeEnum::SUCCESS, __('获取用户信息成功'), [
            'user' => new UserResource($user)
        ]);
    }

    /**
     * 更新指定用户
     */
    public function update(Request $request, User $user)
    {
        if ($user->username == 'super') {
            return api_res(APICodeEnum::EXCEPTION, __('不能修改超级管理员'));
        }

        $all_data = $request->all();

        if (isset($all_data['realName'])) {
            $all_data['name'] = $all_data['realName'];
        }

        if (isset($all_data['username']) && !empty($all_data['username'])) {
            // 检查用户名是否存在
            $existingUser = User::where('username', $all_data['username'])->where('id', '!=', $user->id)->first();
            if ($existingUser) {
                return api_res(APICodeEnum::EXCEPTION, __('用户名已存在'));
            }
        }

        $validator = Validator::make($all_data, [
            // 'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // 'password' => 'nullable|string|min:6',
            // 'roles' => 'array',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        if (isset($all_data['password']) && !empty($all_data['password'])) {
            $all_data['password'] = Hash::make($all_data['password']);
        } else {
            unset($all_data['password']);
        }

        $user->update($all_data);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return api_res(APICodeEnum::SUCCESS, __('用户更新成功'), [
            'user' => new UserResource($user->load('roles'))
        ]);
    }

    /**
     * 删除指定用户
     */
    public function destroy(Request $request, int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return api_res(APICodeEnum::EXCEPTION, __('用户不存在'));
        }

        // 防止删除自己
        if ($user->id === auth()->id()) {
            return api_res(APICodeEnum::EXCEPTION, __('不能删除当前登录的用户'));
        }

        $user->delete();

        return api_res(APICodeEnum::SUCCESS, __('用户删除成功'));
    }
}
