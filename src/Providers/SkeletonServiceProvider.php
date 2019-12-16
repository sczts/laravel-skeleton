<?php

namespace Sczts\Skeleton\Providers;

use Illuminate\Support\ServiceProvider;


class SkeletonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 发布中文语言包
        $this->publishes([
            __DIR__.'/../../resources/lang/zh-CN' => base_path('resources/lang/zh-CN'),
        ], 'skeleton-lang');
    }
}
