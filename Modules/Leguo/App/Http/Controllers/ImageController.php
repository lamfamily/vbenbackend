<?php

namespace Modules\Leguo\App\Http\Controllers;

use App\Enums\APICodeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Leguo\App\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    /*
     * 支持上传base64图片
     * @author: Justin Lin
     * @date : 2025-05-21 10:19:39
     */
    public function upload(Request $request): JsonResponse
    {
        $all_data = $request->all();
        $validator = validator($all_data, [
            'images'   => 'required|array|min:1',
            'images.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        $results = [];
        foreach ($all_data['images'] as $base64str) {
            // 解析 base64
            if (preg_match('/^data:(image\/\w+);base64,/', $base64str, $matches)) {
                $mime = $matches[1];
                $img_data = substr($base64str, strpos($base64str, ',') + 1);
                $img_data = base64_decode($img_data);
                if ($img_data === false) continue; // 解码失败跳过
                $ext = explode('/', $mime)[1] ?? 'png';
            } else {
                // 如果只传裸 base64，不含前缀
                $mime = 'image/png';
                $img_data = base64_decode($base64str);
                $ext = 'png';
                if ($img_data === false) continue;
            }

            $hash = hash('sha256', $img_data);
            $image = Image::where('hash', $hash)->first();

            if (!$image) {
                // 临时文件保存
                $tmpFile = tempnam(sys_get_temp_dir(), 'img_');
                file_put_contents($tmpFile, $img_data);

                $manager = new ImageManager(new Driver());

                $imgInfo = $manager->read($tmpFile);
                $width = $imgInfo->width();
                $height = $imgInfo->height();
                $size = filesize($tmpFile);

                // 文件名
                $filename = 'images/' . uniqid() . '.' . $ext;
                Storage::disk('public')->put($filename, $img_data);

                $image = Image::create([
                    'hash'   => $hash,
                    'path'   => $filename,
                    'url'    => Storage::url($filename),
                    'size'   => $size,
                    'mime'   => $mime,
                    'width'  => $width,
                    'height' => $height,
                    'ext'    => $ext,
                ]);

                // 删除临时文件
                @unlink($tmpFile);
            }

            $results[] = [
                'id'  => $image->id,
                'url' => $image->url,
            ];
        }

        return api_res(APICodeEnum::SUCCESS, j5_trans('操作成功'), [
            'images' => $results
        ]);
    }
}
