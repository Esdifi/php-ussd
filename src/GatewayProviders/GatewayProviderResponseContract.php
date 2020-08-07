<?php


namespace Dbilovd\PHUSSD\GatewayProviders;


interface GatewayProviderResponseContract
{
    /**
     *
     *
     * @param string $type
     * @return string
     */
	public function getResponseType($type): string;

    /**
     * Format response to be sent to gateway provider
     *
     * @param $page
     * @return string
     */
	public function format($page): string;
}