<?php
/**
 * Created by PhpStorm.
 * User: XuHuitao
 * Date: 2020/6/17
 * Time: 17:19
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class SecKillActivity extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seckill_activity';

    /**
     * getActivityInfo
     * @desc 从数据库获取秒杀活动信息
     * @param $activityId
     * @return array
     * @date 2020-06-17
     * @author XuHuitao
     */
    public static function getActivityInfo($activityId) {
        $obj = self::query()->whereKey($activityId)
            ->select('id', 'title', 'start_time', 'end_time', 'product_id', 'product_num', 'selled_num', 'remaining_num')
            ->first();
        if (empty($obj)) {
            return [];
        }
        return $obj->toArray();
    }

    /**
     * updateStock
     * @desc 更新活动库存
     * @param $activityId
     * @return bool
     * @date 2020-06-18
     * @author XuHuitao
     */
    public static function updateStock($activityId) {
        $ret = self::query()->whereKey($activityId)
            ->increment('selled_num', 1, ['remaining_num' => DB::raw('remaining_num - 1')]);
        return $ret > 0;
    }
}