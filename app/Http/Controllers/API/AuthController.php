<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Enums\APICodeEnum;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /** @var JWTGuard $auth */
    protected $auth;

    public function __construct()
    {
        $this->auth = auth();

        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * 用户注册
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
            'name' => [
                'required',
                'string',
                'min:6',
                'max:255',
                'regex:/^[\p{Han}a-zA-Z0-9_]+$/u', // 允许汉字、字母、数字和下划线
            ],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // 默认分配用户角色
        $user->assignRole('user');

        return api_res(APICodeEnum::SUCCESS, __('用户注册成功'), [
            'user' => new UserResource($user)
        ]);
    }

    /**
     * 用户登录
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $credentials = $request->only('username', 'password');

        if (!$token = $this->auth->attempt($credentials)) {
            return api_res(APICodeEnum::EXCEPTION, __('用户名或密码错误'));
        }

        /** @var User $user_model */
        $user_model = $this->auth->user();

        if ($user_model->status == 0) {
            return api_res(APICodeEnum::EXCEPTION, __('用户已被禁用'));
        }

        $ret_data = [
            'realName' => $user_model->name,
            'roles' => $user_model->roles->pluck('name'),
            'username' => $user_model->username,
            'accessToken' => $token,
        ];

        return api_res(APICodeEnum::SUCCESS, __('登录成功'), $ret_data);
    }

    /**
     * 获取当前登录用户信息
     */
    public function me()
    {
        // 包含角色的permissions 和 用户的 permissions
        /** @var User $user_model */
        $user_model = $this->auth->user();
        $user = $user_model->load(['roles.permissions', 'permissions']);

        $ret_data = [
            'realName' => $user->name,
            'roles' => $user->roles->pluck('name'),
            'username' => $user->username,
        ];

        return api_res(APICodeEnum::SUCCESS, __('获取用户信息成功'), $ret_data);
    }

    /**
     * 用户登出
     */
    public function logout()
    {
        $this->auth->logout();
        return api_res(APICodeEnum::SUCCESS, __('成功登出'));
    }

    /**
     * 刷新Token
     */
    public function refresh()
    {
        return $this->respondWithToken($this->auth->refresh(), __('Token刷新成功'));
    }

    /**
     * 返回token响应
     */
    protected function respondWithToken($token, $msg)
    {
        /** @var User $user_model */
        $user_model = $this->auth->user();

        return api_res(APICodeEnum::SUCCESS, $msg, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60,
            'user' => new UserResource($user_model->load('roles'))
        ]);
    }


    public function codes()
    {
        $ret_data = [];

        return api_res(APICodeEnum::SUCCESS, __('获取权限码成功'), $ret_data);
    }
}
