<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2019/1/7
 * Time: 4:05 PM
 */

namespace App\Task;

use Illuminate\Support\Facades\Log;

class User
{
    public function handle()
    {
        sleep(5);
        Log::debug(date('Y-m-d H:i:s', time()));
        return ['user'];
    }
}
