<?php

namespace App\Http\Controllers\App\Demo;

use App\Http\Controllers\App\BaseController;

class ListController extends BaseController
{
    /**
     * @return array
     */
    public function index()
    {
        return success(
            ['app' => '小程序', 'work' => '企业微信', 'console' => '管理后台']
        );
    }
}
