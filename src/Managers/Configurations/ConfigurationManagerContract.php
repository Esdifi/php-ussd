<?php

namespace Esdifi\PHP_USSD\Managers\Configurations;

interface ConfigurationManagerContract
{
    /**
     * Get value of configuration option.
     *
     * @param string $key 	Name of configuration key
     * @return mixed 		Value of configuration option
     */
    public function get(string $key);
}
