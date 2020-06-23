<?php
/**
 * Created by PhpStorm.
 * User: XuHuitao
 * Date: 2020/6/18
 * Time: 15:01
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * sellProduct
     * @desc 售卖产品，数据库中更新产品库存和锁定数
     * @param $productId
     * @param int $num
     * @return bool
     * @date 2020-06-18
     * @author XuHuitao
     */
    public static function sellProduct($productId, $num = 1) {
        $ret = self::query()->whereKey($productId)
            ->decrement('stock', $num, ['locked_num' => DB::raw('locked_num + ' . $num)]);
        return $ret > 0;
    }
}