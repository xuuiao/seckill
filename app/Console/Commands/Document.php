<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/11/14
 * Time: 8:01 PM
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Document extends Command
{
    /**
     * 初始化模板
     */
    const TEMPLATE = <<<EOT
# 接口文档名称

> 接口特别说明：

## 接口地址

`{domain}/app/user/login`

## 请求方式

`GET`

## 请求参数

| 字段 | 类型 | 必填  | 描述 |
| - | - | - | - |
| code | string | __是__ | 该参数必填 |
| name | string | 否 | 该参数非必填 |

## 响应参数

| 字段 | 类型 | 描述 |
| - | - | - |
| url | string | 返回网址 |

## 响应示例

```json
{
    "url": "https://www.vchangyi.com"
}
```
EOT;

    /**
     * @var string
     */
    protected $signature = 'doc {file}';

    /**
     * @var string
     */
    protected $description = '生成接口文档';

    public function handle()
    {
        $file = $this->argument('file');

        if (!preg_match('/\.md$/', $file)) {
            $file = $file.'.md';
        }

        $docPath = storage_path('../../doc/');

        if (!is_dir($docPath)) {
            $this->error('文档目录根目录不存在: '.$docPath);
            return false;
        }

        $dir = $docPath.dirname($file);

        if (!is_dir($dir)) {
            $this->error('文档目录模块不存在: '.$dir);
            return false;
        }

        if (file_exists($docPath.$file)) {
            $this->error('该文件已经存在: '.$docPath.$file);
            return false;
        }

        $file = $docPath.$file;
        file_put_contents($file, self::TEMPLATE, true);

        $this->info('文档生成成功: '.$file);

        return true;
    }
}
