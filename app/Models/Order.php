<?php
/**
 * Created by PhpStorm.
 * User: XuHuitao
 * Date: 2020/6/18
 * Time: 16:00
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';

    protected $fillable = ['user_id', 'product_id', 'status'];

    public static function createOrder($userId, $productId) {
        $data = [
            'user_id' => $userId,
            'product_id' => $productId,
            'status' => 1,
        ];
        return self::query()->create($data);
    }
}