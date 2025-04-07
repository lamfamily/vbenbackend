<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Enums\APICodeEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:manage menus')->except(['index', 'show', 'userMenu']);
    }

    /**
     * 获取所有菜单
     */
    public function index()
    {
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();

        return api_res(APICodeEnum::SUCCESS, __('获取菜单成功'), MenuResource::collection($menus));
    }

    /**
     * 获取当前用户的菜单
     */
    public function userMenu()
    {
        $user = auth()->user();
        $allMenus = Menu::with('children')
            ->whereNull('parent_id')
            ->where('active', true)
            ->orderBy('order')
            ->get();

        // 递归过滤菜单
        $filteredMenus = $this->filterMenusForUser($allMenus, $user);

        return api_res(APICodeEnum::SUCCESS, __('获取菜单成功'), [
            'menus' => MenuResource::collection($filteredMenus)
        ]);
    }

    /**
     * 根据用户权限递归过滤菜单
     */
    private function filterMenusForUser($menus, $user)
    {
        return $menus->filter(function ($menu) use ($user) {
            // 检查当前菜单项是否有权限
            $hasPermission = $menu->authorizeFor($user);

            // 如果有子菜单，递归过滤
            if ($menu->children->count() > 0) {
                $filteredChildren = $this->filterMenusForUser($menu->children, $user);
                $menu->setRelation('children', $filteredChildren);

                // 如果没有权限但有可访问的子菜单，也应该显示
                $hasPermission = $hasPermission || $filteredChildren->count() > 0;
            }

            return $hasPermission;
        });
    }

    /**
     * 保存新菜单
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus',
            'order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $menu = Menu::create($request->all());

        return api_res(APICodeEnum::SUCCESS, __('菜单创建成功'), [
            'menu' => new MenuResource($menu)
        ]);
    }

    /**
     * 获取指定菜单
     */
    public function show(Menu $menu)
    {
        $menu->load('children');
        return api_res(APICodeEnum::SUCCESS, __('获取菜单成功'), [
            'menu' => new MenuResource($menu)
        ]);
    }

    /**
     * 更新指定菜单
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
            'order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $menu->update($request->all());

        return api_res(APICodeEnum::SUCCESS, __('菜单更新成功'), [
            'menu' => new MenuResource($menu)
        ]);
    }

    /**
     * 删除指定菜单
     */
    public function destroy(Request $request, int $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return api_res(APICodeEnum::EXCEPTION, __('菜单不存在'));
        }

        // 检查是否有子菜单
        if ($menu->children()->exists() > 0) {
            return api_res(APICodeEnum::EXCEPTION, __('请先删除子菜单'));
        }

        $menu->delete();

        return api_res(APICodeEnum::SUCCESS, __('菜单删除成功'), [
            'menu' => new MenuResource($menu)
        ]);
    }


    public function checkNameExists(Request $request)
    {
        $all_data = $request->all();

        $id = $all_data['id'] ?? '';
        $name = $all_data['name'] ?? '';

        if ($id) {
            $menu = Menu::where('name', $name)->where('id', '!=', $id)->first();
        } else {
            $menu = Menu::where('name', $name)->first();
        }

        if ($menu) {
            return api_res(APICodeEnum::EXCEPTION, __('菜单名称已存在'));
        } else {
            return api_res(APICodeEnum::SUCCESS, __('菜单名称可用'));
        }
    }


    public function checkPathExists(Request $request)
    {
        $all_data = $request->all();

        $id = $all_data['id'] ?? '';
        $name = $all_data['name'] ?? '';

        if ($id) {
            $menu = Menu::where('url', $name)->where('id', '!=', $id)->first();
        } else {
            $menu = Menu::where('url', $name)->first();
        }

        if ($menu) {
            return api_res(APICodeEnum::EXCEPTION, __('菜单路径已存在'));
        } else {
            return api_res(APICodeEnum::SUCCESS, __('菜单路径可用'));
        }
    }
}
