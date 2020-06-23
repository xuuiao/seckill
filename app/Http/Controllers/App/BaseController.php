<?php
/**
 * Created by PhpStorm.
 * User: 陈朔
 * Date: 2018/11/9
 * Time: 9:54 AM
 */

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
