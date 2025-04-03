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
        $this->middleware('permission:manage users');
    }

    /**
     * 获取所有用户
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return api_res(APICodeEnum::SUCCESS, __('获取用户列表成功'), [
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * 创建新用户
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'roles' => 'array',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'array',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

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
    public function destroy(User $user)
    {
        // 防止删除自己
        if ($user->id === auth()->id()) {
            return api_res(APICodeEnum::EXCEPTION, __('不能删除当前登录的用户'));
        }

        $user->delete();

        return api_res(APICodeEnum::SUCCESS, __('用户删除成功'));
    }
}
