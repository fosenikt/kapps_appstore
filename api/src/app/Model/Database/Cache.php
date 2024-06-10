<?php
namespace Kapps\Model\Database;

class Cache
{
	private static $cache_prefix = "myapp_"; // Prefix to avoid key collisions

    
	/**
	 * APC cache
	 */
    public static function get($key)
    {
        $cache_key = self::$cache_prefix . $key;
        $value = apcu_fetch($cache_key, $success);
        if ($success) {
            return $value;
        } else {
            return false;
        }
    }
    
    public static function set($key, $value, $ttl = 60)
    {
        $cache_key = self::$cache_prefix . $key;
        apcu_store($cache_key, $value, $ttl);
    }
    
    public static function delete($key)
    {
        $cache_key = self::$cache_prefix . $key;
        apcu_delete($cache_key);
    }
}
