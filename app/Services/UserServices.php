<?php
/**
 * Created by PhpStorm.
 * User: XuHuitao
 * Date: 2020/6/17
 * Time: 16:07
 */

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class UserServices{

    public function getUserLock() {
        $redis = Redis::connection();
        var_dump($redis);
    }

}