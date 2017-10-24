<?php
namespace app\model;
use think\Cache;

class CommonModel
{
    // 初始化值
    const initNumber = 0;
    
    /**
    * @user 获取cache缓存
    * @param $key 键名
    * @author jeeluo
    * @date 2017年3月3日下午2:37:03
    */
    public static function getCacheNumber($key) {
        return Cache::get($key) ?: self::initNumber;
    }
    
    /**
    * @user 设置cache缓存
    * @param $key 键名
    * @param $value 键值
    * @param $timeOut 过期时间
    * @author jeeluo
    * @date 2017年3月3日下午2:37:55
    */
    public static function setCacheNumber($key, $value, $timeOut) {
        return Cache::set($key, $value, $timeOut);
    }
    
    /**
    * @user 销毁cache缓存
    * @param $key 键名
    * @author jeeluo
    * @date 2017年3月3日下午2:38:55
    */
    public static function destoryNumber($key) {
        return Cache::set($key, null);
    }
}