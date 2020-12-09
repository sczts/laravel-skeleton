# skeleton

> laravel 常用类，Trait 及语言包

1. 使用 composer 安装
    ```bash
    composer require sczts/skeleton
    ```

2. 发布文件
    ```bash
    # 发布相关文件到项目
    php artisan vendor:publish --provider="Sczts\Skeleton\Providers\SkeletonServiceProvider"
    ```

3. 修改语言配置
    `config/app.php`
    ```php
       'locale' => 'zh-Cn'
    ```
