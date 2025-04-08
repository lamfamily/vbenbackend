<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Enums\APICodeEnum;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $menus = Menu::with('allChildren')->whereNull('parent_id')->orderBy('order')->get();

        return api_res(APICodeEnum::SUCCESS, __('获取菜单成功'), MenuResource::collection($menus));
    }

    /**
     * 获取当前用户的菜单
     */
    public function userMenu()
    {
        $user = auth()->user();
        $allMenus = Menu::with('allChildren')
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
        $all_data = $request->all();

        $validator = Validator::make($all_data, [
            'name' => 'required|string|unique:menus|max:255',
            // 'slug' => 'required|string|max:255|unique:menus',
            'order' => 'integer|min:0',
            'meta' => 'nullable|array',
        ]);

        $all_data['slug'] = Str::slug($all_data['name']);
        $all_data['url'] = $all_data['path'] ?? '';
        $all_data['parent_id'] = $all_data['pid'] ?? null;
        $all_data['active'] = $all_data['status'] ?? 1;
        $all_data['permission'] = $all_data['authCode'] ?? '';

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $menu = Menu::create($all_data);

        return api_res(APICodeEnum::SUCCESS, __('菜单创建成功'), [
            'menu' => new MenuResource($menu)
        ]);
    }

    /**
     * 获取指定菜单
     */
    public function show(Menu $menu)
    {
        $menu->load('allChildren');
        return api_res(APICodeEnum::SUCCESS, __('获取菜单成功'), new MenuResource($menu));
    }

    /**
     * 更新指定菜单
     */
    public function update(Request $request, Menu $menu)
    {
        $all_data = $request->all();
        $validator = Validator::make($all_data, [
            'name' => 'required|string|max:255',
            // 'order' => 'integer|min:0',
            // 'slug' => [
            //     'required',
            //     'string',
            //     'max:255',
            //     Rule::unique('menus')->ignore($menu->id),
            // ]
        ]);

        $all_data['slug'] = Str::slug($all_data['name']);

        if (isset($all_data['path'])) {
            $all_data['url'] = $all_data['path'];
        }
        if (isset($all_data['pid'])) {
            $all_data['parent_id'] = $all_data['pid'];
        }
        if (isset($all_data['status'])) {
            $all_data['active'] = $all_data['status'];
        }
        if (isset($all_data['authCode'])) {
            $all_data['permission'] = $all_data['authCode'];
        }

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $menu->update($all_data);

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

        $is_menu_exists = $menu ? true : false;

        if ($menu) {
            return api_res(APICodeEnum::EXCEPTION, __('菜单名称已存在'), $is_menu_exists);
        } else {
            return api_res(APICodeEnum::SUCCESS, __('菜单名称可用'), $is_menu_exists);
        }
    }


    public function checkPathExists(Request $request)
    {
        $all_data = $request->all();

        $id = $all_data['id'] ?? '';
        $path = $all_data['path'] ?? '';

        if ($id) {
            $menu = Menu::where('url', $path)->where('id', '!=', $id)->first();
        } else {
            $menu = Menu::where('url', $path)->first();
        }

        $is_path_exists = $menu ? true : false;

        if ($menu) {
            return api_res(APICodeEnum::EXCEPTION, __('菜单路径已存在'), $is_path_exists);
        } else {
            return api_res(APICodeEnum::SUCCESS, __('菜单路径可用'), $is_path_exists);
        }
    }
}
