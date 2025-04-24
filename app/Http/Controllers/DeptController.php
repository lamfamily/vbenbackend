<?php

namespace App\Http\Controllers;

use App\Models\Dept;
use App\Enums\APICodeEnum;
use App\Http\Resources\DeptResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:System:Dept:List');
    }

    public function index()
    {
        $depts = Dept::with('allChildren')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return api_res(APICodeEnum::SUCCESS, __('获取部门列表成功'), DeptResource::collection($depts));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'pid' => 'nullable|exists:depts,id',
            'status' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'remark' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        // 校验name 是否存在
        // $exists = Dept::where('name', $request->name)->exists();
        // 如果 pid 存在，要带上
        $exists = Dept::where('name', $request->name)
            ->where(function ($query) use ($request) {
                if ($request->pid) {
                    $query->where('parent_id', $request->pid);
                }
            })
            ->exists();
        if ($exists) {
            return api_res(APICodeEnum::EXCEPTION, __('部门名称已存在'));
        }

        // 校验pid 是否存在
        if ($request->pid) {
            $parentDept = Dept::find($request->pid);
            if (!$parentDept) {
                return api_res(APICodeEnum::EXCEPTION, __('上级部门不存在'));
            }
        }


        // 创建一个新的数据数组，将pid映射为parent_id
        $data = $request->all();
        if (isset($data['pid'])) {
            $data['parent_id'] = $data['pid'];
            unset($data['pid']); // 移除pid字段
        }

        $dept = Dept::create($data);

        return api_res(APICodeEnum::SUCCESS, __('部门创建成功'), new DeptResource($dept));
    }


    public function show(Dept $dept)
    {
        $dept->load('allChildren');

        return api_res(APICodeEnum::SUCCESS, __('获取部门详情成功'), new DeptResource($dept));
    }

    public function update(Request $request, Dept $dept)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'pid' => 'nullable|exists:depts,id',
            'status' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'remark' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        // 校验name 是否存在
        $exists = Dept::where('name', $request->name)->where('id', '!=', $dept->id)->exists();

        if ($exists) {
            return api_res(APICodeEnum::EXCEPTION, __('部门名称已存在'));
        }

        // 校验pid 是否存在
        if ($request->pid) {
            $parentDept = Dept::find($request->pid);
            if (!$parentDept) {
                return api_res(APICodeEnum::EXCEPTION, __('上级部门不存在'));
            }
        }

        // 创建一个新的数据数组，将pid映射为parent_id
        $data = $request->all();
        if (isset($data['pid'])) {
            $data['parent_id'] = $data['pid'];
            unset($data['pid']); // 移除pid字段
        }

        $dept->update($data);

        return api_res(APICodeEnum::SUCCESS, __('部门更新成功'), new DeptResource($dept));
    }


    public function destroy(Request $request, int $id)
    {
        $dept = Dept::find($id);

        if (!$dept) {
            return api_res(APICodeEnum::EXCEPTION, __('部门不存在'));
        }

        // 检查是否有子分类
        if ($dept->children()->exists()) {
            return api_res(APICodeEnum::EXCEPTION, __('请先删除子部门'));
        }

        $dept->delete();

        return api_res(APICodeEnum::SUCCESS, __('部门删除成功'), new DeptResource($dept));
    }
}
