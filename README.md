# skeleton

> 基于laravel的基础框架拓展包

1. 使用 composer 安装
    ```bash
    composer require sczts/skeleton
    ```

2. 发布配置文件
    ```bash
    # 这条命令会在 config 下增加一个 rbac.php 的配置文件
    php artisan vendor:publish --provider="Sczts\Skeleton\Providers\SkeletonServiceProvider"
    ```

