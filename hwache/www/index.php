<?php
/**
 * 华车网系统
 *
 * @author      技安 <php360@qq.com>
 * @link        http://www.hwache.com
 * @copyright   苏州华车网络科技有限公司
 */

/**
 * 定义目录分隔符
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * 系统根路径
 */
define('BP', __DIR__.DS);

/**
 * 加载laravel框架
 */
require dirname(BP).DS.'laravel'.DS.'public'.DS.'index.php';
