<?php

namespace Esdifi\PHP_USSD\SessionManagers;

use Esdifi\PHP_USSD\Contracts\SessionManagersInterface;
use Illuminate\Support\Facades\Redis;

class RedisSessionManager implements SessionManagersInterface
{
    /**
     * Redis: Check if key exists.
     *
     * @param string $key Key to check if it exists
     * @return bool
     */
    public function exists($key)
    {
        return Redis::exists($key);
    }

    /**
     * Set value of a key.
     *
     * @param string $key The key of the hash in redis
     * @param mixed $value The value to set for that subkey
     * @return void
     */
    public function setValueOfKey($key, $value)
    {
        Redis::set($key, $value);
    }

    /**
     * Redis: Set value of hash key.
     *
     * @param string $key The key of the hash in redis
     * @param string $subKey The key within the harsh
     * @param mixed $value The value to set for that subkey
     * @return void
     */
    public function setValueOfSubKey($key, $subKey, $value)
    {
        Redis::hSet($key, $subKey, $value);
    }

    /**
     * Redis: HGet.
     *
     * @param string $key Name of Redis key to fetch
     * @param string $subKey Name of key in hash
     * @return mixed
     */
    public function getValueOfSubKey($key, $subKey)
    {
        return Redis::hGet($key, $subKey);
    }

    /**
     * Redis: HGetall.
     *
     * @param string $key Name of key to fetch
     * @return mixed
     */
    public function getValueOfKey($key)
    {
        return Redis::hGetAll($key);
    }
}
