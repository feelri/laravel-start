<h1 align="center">Laravel Start</h1>
<p align="center">
<a href="https://packagist.org/packages/feelri/laravel-start"><img src="https://img.shields.io/packagist/dt/feelri/laravel-start" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/feelri/laravel-start"><img src="https://img.shields.io/packagist/v/feelri/laravel-start" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/feelri/laravel-start"><img src="https://img.shields.io/packagist/l/feelri/laravel-start" alt="License"></a>
</p>

## 介绍
Laravel Start 是一个快速开发框架，基于 Laravel 框架，封装了常用功能，简化开发流程，提高开发效率。

## 快速开始
#### 【必须】安装
```bash
composer create-project feelri/laravel-start
```
或者
```bash
git clone https://github.com/feelri/laravel-start.git
```

#### 【必须】进入项目
```bash
cd laravel-start
```

#### 【可选】发布 Sanctum 相关文件
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" 
```

#### 【可选】填充默认数据
```bash
php artisan db:seed
```

#### 【可选】启动错误日志队列
```bash
php artisan queue:work --queue reportException
```

#### 【可选】启动内置服务
```bash
php artisan serve
```