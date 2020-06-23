<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/11/14
 * Time: 10:11 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Base extends Model
{
    /**
     * 启用软删除
     */
    use SoftDeletes;

    /**
     * 需要被转换日期时间格式的字段
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * 设置日期时间格式
     *
     * @var string
     */
    public $dateFormat = 'U';
}
