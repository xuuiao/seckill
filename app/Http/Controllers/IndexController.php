<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/11/9
 * Time: 10:00 AM
 */

namespace App\Http\Controllers;

use App\Services\SecKillServices;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * 首页
     *≠≠
     * 仅在开发环境才会输入日志
     *
     * @param Request $request
     * @return array|mixed
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/12/7 10:48 AM
     */
    public function index(Request $request)
    {
        $env = config('app.env');

        // 如果是开发环境才输出日志
        if ($env === 'dev') {

            $file = empty($request->get('file')) ? date('Y/m/d/\h-H', time()).'.log' : $request->get('file');
            $file = storage_path('logs/'.$file);

            if (!file_exists($file)) {
                return notice(100007);
            }

            $data = file_get_contents($file);
            return view('logging')
                ->with('file', $file)
                ->with('data', $data);
        }

        return 'shop.'.$env;
    }

    /**
     * seckill
     * @desc 秒杀接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @date 2020-06-19
     * @author XuHuitao
     */
    public function seckill(Request $request) {
        $userId = (int)$request->get('user_id', 0);
        $activityId = (int)$request->get('activity_id', 0);
        if ($userId <= 0) {
            return error(610001);
        }
        if ($activityId <= 0) {
            return error(610002);
        }
        $secKillObj = new SecKillServices();
        $result = $secKillObj->secKill($userId, $activityId);
        if (empty($result['ret'])) {
            return error(610003, [], 500, null, [], $result);
        }
        return success();

    }

    /**
     * initcache
     * @desc 初始化秒杀活动缓存数据
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|void
     * @date 2020-06-19
     * @author XuHuitao
     */
    public function initcache(Request $request) {
    $activityId = (int)$request->get('activity_id', 0);
    if ($activityId <= 0) {
        return error(610002);
    }
    $secKillObj = new SecKillServices();
    if ($secKillObj->initActivityCache($activityId)) {
        return success();
    }
    return error(610002);
}

    public function test() {

    }
}
