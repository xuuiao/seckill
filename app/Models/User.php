<?php
/**
 * Created by PhpStorm.
 * User: XuHuitao
 * Date: 2020/6/17
 * Time: 11:51
 */

namespace App\Models;

Class User extends Base {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * getUserInfo
     * @desc 获取用户信息
     * @param int $userId
     *
     * @return array
     * @date 2020-06-19
     * @author XuHuitao
     */
    public static function getUserInfo(int $userId) {
        $result = self::query()->whereKey($userId)
            ->select('id', 'name', 'phone', 'address')
            ->get();
        return $result->toArray();
    }
}
