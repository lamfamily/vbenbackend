<?php

namespace App\Exceptions;

use Throwable;
use App\Enums\APICodeEnum;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // reportable 关注日志和报告（后台）
        $this->reportable(function (Throwable $e) {
            //
        });

        // renderable 关注用户体验和响应展示（前台）
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {

                // 需要返回401状态码给vben
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return api_res(APICodeEnum::EXCEPTION, __('未授权'), [
                        'message' => $e->getMessage()
                    ], 401);
                }

                // 处理 ModelNotFoundException
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return api_res(APICodeEnum::EXCEPTION, j5_trans('数据不存在'), [
                        'message' => $e->getMessage()
                    ]);
                }

                // 统一做多语言处理
                $msg = $e->getMessage();
                if (__($msg) !== $msg) {
                    $msg = __($msg);
                }

                return api_res(APICodeEnum::EXCEPTION, $msg);
            }
        });
    }
}
