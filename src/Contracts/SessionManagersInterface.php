<?php

namespace Dbilovd\PHP_USSD\Contracts;

interface SessionManagersInterface
{
    /**
     * Check if key exists.
     *
     * @param string $key Key to check if it exists
     * @return bool
     */
    public function exists($key);

    /**
     * Set value of a sub key.
     *
     * @param string $key The key of the hash in redis
     * @param string $subKey The key within the harsh
     * @param mixed $value The value to set for that subkey
     * @return void
     */
    public function setValueOfSubKey($key, $subKey, $value);

    /**
     * Get value of a sub key
     *
     * @param string $key Name of Redis key to fetch
     * @param string $subKey Name of key in hash
     * @return mixed
     */
    public function getValueOfSubKey($key, $subKey);

    /**
     * Get value of key
     *
     * @param string $key Name of key to fetch
     * @return mixed
     */
    public function getValueOfKey($key);
}
