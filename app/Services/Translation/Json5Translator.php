<?php

namespace App\Services\Translation;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ColinODell\Json5\Json5Decoder;
use Illuminate\Support\Facades\Log;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;

class Json5Translator
{
    /**
     * 文件系统实例
     */
    protected $files;

    /**
     * 翻译缓存
     */
    protected $translations = [];

    /**
     * 缓存有效期（分钟）
     */
    protected $cacheTime = 60;

    /**
     * 是否使用缓存
     */
    protected $useCache = false;

    /**
     * 创建一个新的Json5Translator实例
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * 获取给定语言的JSON5翻译
     *
     * @param  string  $locale
     * @return array
     */
    public function getTranslations($locale)
    {
        if (isset($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        if ($this->useCache) {
            return $this->translations[$locale] = Cache::remember(
                'json5_translations_' . $locale,
                $this->cacheTime,
                function () use ($locale) {
                    return $this->loadTranslations($locale);
                }
            );
        }

        return $this->translations[$locale] = $this->loadTranslations($locale);
    }

    /**
     * 加载给定语言的JSON5翻译
     *
     * @param  string  $locale
     * @return array
     */
    protected function loadTranslations($locale)
    {
        $translations = [];

        // 主语言文件路径
        $paths = [
            // resource_path('lang'),
            lang_path(),
        ];

        // 添加vendor语言包路径
        $vendorPath = resource_path('lang/vendor');
        if ($this->files->isDirectory($vendorPath)) {
            $vendorDirectories = $this->files->directories($vendorPath);
            $paths = array_merge($paths, $vendorDirectories);
        }

        // 加载所有路径中的JSON5文件
        foreach ($paths as $path) {
            $jsonPath = $path . '/' . $locale . '.json5';

            if ($this->files->exists($jsonPath)) {
                try {
                    $content = $this->files->get($jsonPath);
                    $decoded = Json5Decoder::decode($content, true);

                    if (is_array($decoded)) {
                        $translations = array_merge($translations, $decoded);
                    }
                } catch (\Exception $e) {
                    if (config('app.debug')) {
                        Log::error("Error parsing JSON5 file: {$jsonPath}", ['error' => $e->getMessage()]);
                    }
                }
            }
        }

        return $translations;
    }

    /**
     * 翻译给定的消息
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    public function translate($key, array $replace = [], $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        $translations = $this->getTranslations($locale);

        $line = Arr::get($translations, $key, $key);

        if (is_string($line)) {
            return $this->makeReplacements($line, $replace);
        }

        return $key;
    }

    /**
     * 在翻译中替换参数
     *
     * @param  string  $line
     * @param  array  $replace
     * @return string
     */
    protected function makeReplacements($line, array $replace)
    {
        if (empty($replace)) {
            return $line;
        }

        foreach ($replace as $key => $value) {
            $line = str_replace(
                [':' . $key, ':' . Str::upper($key), ':' . Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }

        return $line;
    }

    /**
     * 清除翻译缓存
     *
     * @return void
     */
    public function clearCache()
    {
        $this->translations = [];

        $locales = $this->getAvailableLocales();
        foreach ($locales as $locale) {
            Cache::forget('json5_translations_' . $locale);
        }
    }

    /**
     * 获取可用的语言列表
     *
     * @return array
     */
    public function getAvailableLocales()
    {
        // $langPath = resource_path('lang');
        $langPath = lang_path();
        $locales = [];

        if ($this->files->isDirectory($langPath)) {
            foreach ($this->files->files($langPath) as $file) {
                if ($this->files->extension($file) === 'json5') {
                    $locales[] = $this->files->name($file);
                }
            }
        }

        return $locales;
    }
}
