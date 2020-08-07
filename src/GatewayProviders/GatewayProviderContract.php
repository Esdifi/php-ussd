<?php


namespace Dbilovd\PHP_USSD\GatewayProviders;


/**
 * Interface GatewayProviderContract
 * @package Dbilovd\PHP_USSD\GatewayProviders
 */
interface GatewayProviderContract
{
    /**
     * Get the instance of the current Gateway Provider Request
     *
     * @return GatewayProviderRequestContract
     */
    public function getRequest(): GatewayProviderRequestContract;

    /**
     * Get the instance of the current Gateway Provider Response
     *
     * @return GatewayProviderResponseContract
     */
    public function getResponse(): GatewayProviderResponseContract;
}