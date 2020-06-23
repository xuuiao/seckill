<?php
/**
 * Created by PhpStorm.
 * User: XuHuitao
 * Date: 2020/6/17
 * Time: 16:17
 */

namespace App\Services;


use App\Models\ActivityRecord;
use App\Models\Order;
use App\Models\Product;
use App\Models\SecKillActivity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SecKillServices
{


    private $redis;

    public function __construct() {
        $this->redis = Redis::connection();
    }

    /**
     * getUserRequestLockKey
     * @desc 获取用户请求锁缓存key
     * @param $userId
     * @param $activityId
     * @return string
     * @date 2020-06-19
     * @author XuHuitao
     */
    private function getUserRequestLockKey($userId, $activityId) : string {
        return 'SEC_KILL_USE_LOCK_' . $userId. '_' . $activityId;
    }

    /**
     * lockUserRequest
     * @desc 获取用户请求锁，避免同一个用户同时多次请求秒杀接口
     * @param $userId int 用户id
     * @param $activityId int 活动id
     * @return bool
     * @date 2020-06-17
     * @author Sean
     */
    public function lockUserRequest($userId, $activityId) : bool {
        if ($userId <= 0 || $activityId <= 0) {
            return false;
        }
        $key = $this->getUserRequestLockKey($userId, $activityId);
        $ret = $this->redis->setnx($key, time() + 20);
        if ($ret === 1) {
            return true;
        }
        // 获取锁存储的时间戳，判断是否还有效
        $val = $this->redis->get($key);
        if ($val > time()) {    // 锁还有效
            return false;
        }
        // 锁已失效
        $this->redis->del([$key]);
        $ret = $this->redis->setnx($key, time() + 20);
        return $ret === 1;
    }

    /**
     * unlockUserRequest
     * @desc 释放用户请求锁
     * @param $userId
     * @param $activityId
     * @return bool
     * @date 2020-06-17
     * @author Sean
     */
    public function unlockUserRequest($userId, $activityId) : bool {
        if ($userId <= 0 || $activityId <= 0) {
            return false;
        }
        $key = $this->getUserRequestLockKey($userId, $activityId);
        $this->redis->del([$key]);
        return true;
    }

    /**
     * secKillFailed
     * @desc 秒杀失败处理逻辑
     * @param $userId
     * @param $activityId
     * @param $msg
     * @return array
     * @date 2020-06-17
     * @author XuHuitao
     */
    private function secKillFailed($userId, $activityId, $msg) : array {
        $result = [
            'ret' => false,
            'msg' => $msg,
            'data' => '',
        ];
        $this->unlockUserRequest($userId, $activityId); // 释放用户请求锁
        return $result;
    }

    /**
     * getActivityCacheKey
     * @desc 获取活动缓存key
     * @param $activityId
     * @return string
     * @date 2020-06-19
     * @author XuHuitao
     */
    private function getActivityCacheKey($activityId) : string {
        return 'SEC_KILL_ACTIVITY_' . $activityId;
    }

    /**
     * initActivityCache
     * @desc 初始化活动缓存数据
     * @param $activityId
     * @return bool
     * @date 2020-06-19
     * @author XuHuitao
     */
    public function initActivityCache($activityId) : bool {
        $key = $this->getActivityCacheKey($activityId);
        $cacheData = $this->redis->get($key);
        Log::debug('cache data:' . $cacheData);
        if (!empty($cacheData)) {
            Log::debug('cache exist');
            //return true;
        }
        $activityInfo = SecKillActivity::getActivityInfo($activityId);
        if (empty($activityInfo)) {
            return false;
        }
        $cacheInfo = json_encode($activityInfo);
        $this->redis->set($key, $cacheInfo);
        $curTime = time();
        $expireTime = $activityInfo['end_time'] > $curTime ? ($activityInfo['end_time'] - $curTime + 3600) : 86400;
        Log::debug('expireTime:' . $expireTime);
        $this->redis->expire($key, $expireTime);
        return true;
    }

    public function secKill($userId, $activityId) {
        $result = [
            'ret' => false,
            'msg' => '',
            'data' => '',
            'code' => 610003,
        ];
        // 检查用户信息
        //$userInfo = User::getUserInfo($userId);
        /*if (empty($userInfo)) {
            $result['msg'] = '用户信息不存在';
            return $result;
        }
        */
        // 获取用户请求锁
        if (!$this->lockUserRequest($userId, $activityId)) {
            return $this->secKillFailed($userId, $activityId, '重复请求');
        }

        // 获取活动信息
        $activityInfo = $this->getActivityInfo($activityId);
        if (empty($activityInfo)) {
            return $this->secKillFailed($userId, $activityId, '活动信息获取失败');
        }

        //检查活动是否在进行中，是否还有剩余库存
        if ($activityInfo['start_time'] > time()) {
            return $this->secKillFailed($userId,
                $activityId,
                '当前秒杀活动还未开始, 距离开始还有' . ($activityInfo['start_time'] - time()) . '秒');
        }
        if ($activityInfo['end_time'] < time()) {
            return $this->secKillFailed($userId, $activityId, '当前秒杀活动已经结束');
        }
        if ($activityInfo['remaining_num'] <= 0) {
            return $this->secKillFailed($userId, $activityId, '商品已被抢完了');
        }
        //检查用户是否已经抢购过商品
        if ($this->isUserSecKillSuccess($userId, $activityId)) {
            return $this->secKillFailed($userId, $activityId, '不能重复购买');
        }
        //调整活动库存
        $activityStockRet = $this->setActivityStock($activityId);
        if (!$activityStockRet['ret']) {
            return $this->secKillFailed($userId, $activityId, $activityStockRet['msg']);
        }
        Log::info('当前库存：' . $activityStockRet['remaining_num'] . ', 当前请求成功秒杀到一商品，用户id：' . $userId);
        try {
            DB::beginTransaction();
            Product::sellProduct($activityInfo['product_id']);
            SecKillActivity::updateStock($activityId);
            $orderInfo = Order::createOrder($userId, $activityInfo['product_id']);
            $recordInfo = ActivityRecord::addRecord($userId, $activityId, $orderInfo['id']);
            DB::commit();
        } catch (\Exception $e) {
            Log::error('秒杀创建订单失败, userId:' . $userId . ', activityId:' . $activityId);
            DB::rollBack();
            $this->recoverActivityStock($activityId);
            return $this->secKillFailed($userId, $activityId, '请求异常，请重新尝试');
        }
        //缓存用户购买记录数据
        $this->cacheUserActivityInfo($userId, $activityId, $activityInfo['end_time'] - time());

        $result['ret'] = true;
        $result['data'] = [
            'order' => $orderInfo,
            'user_activity_record' => $recordInfo
        ];
        $this->unlockUserRequest($userId, $activityId); // 释放用户请求锁
        return $result;
    }

    /**
     * recoverActivityStock
     * @desc 恢复缓存中的库存数据
     * @param $activityId
     * @date 2020-06-18
     * @author XuHuitao
     */
    private function recoverActivityStock($activityId) {
        $this->lockActivityStock($activityId, false);
        $key = $this->getActivityCacheKey($activityId);
        $cacheValue = $this->redis->get($key);
        $activityInfo = json_decode($cacheValue, true);
        $activityInfo['remaining_num'] += 1;
        $activityInfo['selled_num'] -= 1;
        Log::debug('缓存库存数据，remaining_num：' . $activityInfo['remaining_num'] . ', sold_num:' . $activityInfo['selled_num']);
        $this->redis->set($key, json_encode($activityInfo));
        $this->unlockActivityStock($activityId);
    }

    /**
     * setActivityStock
     * @desc 调整活动库存
     * @param $activityId
     * @return array
     * @date 2020-06-18
     * @author XuHuitao
     */
    private function setActivityStock($activityId) {
        $result = [
            'ret' => false,
            'msg' => '',
            'remaining_num' => 0,
        ];
        // 获取活动库存锁
        $startTime = microtime(true) * 1000;
        if (!$this->lockActivityStock($activityId)) {
            $result['msg'] = '网络拥堵，请再次尝试';
            return $result;
        }
        $key = $this->getActivityCacheKey($activityId);
        $cacheValue = $this->redis->get($key);
        $activityInfo = json_decode($cacheValue, true);
        if ($activityInfo['remaining_num'] <= 0) {
            $this->unlockActivityStock($activityId);
            $result['msg'] = '商品已被抢完了';
            return $result;
        }
        $activityInfo['remaining_num'] -= 1;
        $activityInfo['selled_num'] += 1;
        Log::debug('缓存库存数据，remaining_num：' . $activityInfo['remaining_num'] . ', selled_num:' . $activityInfo['selled_num']);
        $this->redis->set($key, json_encode($activityInfo));
        $this->unlockActivityStock($activityId);
        $endTime = microtime(true) * 1000;
        Log::info('加锁设置库存耗时(ms):' . ($endTime - $startTime));
        $result['ret'] = true;
        $result['remaining_num'] = $activityInfo['remaining_num'];
        return $result;
    }

    private function getActivityStockLockKey($activityId) {
        return 'SEC_KILL_ACTIVITY_STOCK_' . $activityId;
    }

    /**
     * lockActivityStock
     * @desc 获取活动库存锁
     * @param $activityId
     * @param $limit bool 是否限制循环等待次数
     * @return bool
     * @date 2020-06-18
     * @author XuHuitao
     */
    private function lockActivityStock($activityId, $limit = true) :bool {
        $key = $this->getActivityStockLockKey($activityId);
        $getLock = false;
        $loopTimes = 0;
        $curMS = microtime(true) * 1000;
        while (true) {
            $ret = $this->redis->setnx($key, $curMS);
            if ($ret === 1) {
                $this->redis->expire($key, 20); // 设置20秒有效期
                $getLock = true;
                break;
            }
            usleep(2000);
            Log::debug('哎呀，我等了。。。');
            if ($limit) {
                $loopTimes++;
                if ($loopTimes >= 1) {
                    break;
                }
            }
        }
        $endMS = microtime(true) * 1000;
        if ($getLock) {
            Log::notice('lock success, cost ' . ($endMS - $curMS) . 'ms');
        } else {
            Log::notice('lock failed, cost ' . ($endMS - $curMS) . 'ms');
        }
        return $getLock;
    }

    private function unlockActivityStock($activityId) {
        $key = $this->getActivityStockLockKey($activityId);
        $this->redis->del([$key]);
    }

    private function getUserActivityCacheKey($userId, $activityId) {
        return 'SEC_KILL_USER_ACTIVITY_' . $activityId . '_' . $userId;
    }

    /**
     * cacheUserActivityInfo
     * @desc 缓存用户秒杀成功信息
     * @param $userId
     * @param $activityId
     * @param $expireTime int 缓存有效期，取活动截止时间
     * @date 2020-06-18
     * @author XuHuitao
     */
    private function cacheUserActivityInfo($userId, $activityId, $expireTime) {
        $key = $this->getUserActivityCacheKey($userId, $activityId);
        $this->redis->setex($key, $expireTime, 1);
    }

    /**
     * isUserSecKillSuccess
     * @desc 检查用户是否秒杀成功
     * @param $userId int 用户id
     * @param $activityId int 活动id
     * @return bool
     * @date 2020-06-18
     * @author XuHuitao
     */
    private function isUserSecKillSuccess($userId, $activityId) {
        $key = $this->getUserActivityCacheKey($userId, $activityId);
        return !empty($this->redis->get($key));
    }

    /**
     * getActivityInfo
     * @desc 从缓存中获取秒杀活动信息
     * @param $activityId
     * @return array|mixed
     * @date 2020-06-18
     * @author XuHuitao
     */
    private function getActivityInfo($activityId) {
        $key = $this->getActivityCacheKey($activityId);
        $cacheData = $this->redis->get($key);
        if (empty($cacheData)) {
            if (!$this->initActivityCache($activityId)) {
                return [];
            }
        }
        $cacheData = $this->redis->get($key);
        return json_decode($cacheData, true);
    }
}