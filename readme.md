# 框架介绍

该框架为lumen5.6 (laravel 官方精简版)

# 学习文档

https://laravel-china.org/docs/lumen/5.6

# 安装

1. git clone

2. cd lumen-framework/src

3. composer install

4. lumen-framework/src/logs 给写权限

5. cp .env.example .env

6. 修改 .env  APP_KEY  建议 : md5(uniqid())

7. 虚拟主机配置站点目录至  lumen/framework/src/public


# 数据库迁移

使用 laravel migration 进行维护

make:migration

# 日志

日志级别: Info Debug Error ... [必须]按照指定级别使用

日志使用: Log::info('log message');

日志生成规则: storage/logs/{year}/{month}/{day}/h-{H}.log

# Json 响应数据

* 正确结果 `return success([])`

* 错误结果 `return error(100001)`

# 文档维护

文档维护在 doc 目录下
