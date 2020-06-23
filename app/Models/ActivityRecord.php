<?php
/**
 * Created by PhpStorm.
 * User: XuHuitao
 * Date: 2020/6/18
 * Time: 16:01
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ActivityRecord extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_record';

    protected $fillable = ['user_id', 'activity_id', 'order_id'];

    /**
     * addRecord
     * @desc 增加记录
     * @param $userId
     * @param $activityId
     * @param $orderId
     * @return $this|Model
     * @date 2020-06-18
     * @author XuHuitao
     */
    public static function addRecord($userId, $activityId, $orderId) {
        $data = [
            'user_id' => $userId,
            'activity_id' => $activityId,
            'order_id' => $orderId,
        ];
        return self::query()->create($data);
    }
}