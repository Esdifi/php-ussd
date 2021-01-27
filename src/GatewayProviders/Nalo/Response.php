<?php

namespace Dbilovd\PHP_USSD\GatewayProviders\Nalo;

use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderResponseContract;

class Response implements GatewayProviderResponseContract
{
    /**
     * Request object.
     *
     * @var Dbilovd\PHP_USSD\GatewayProviders\Nalo\Request
     */
    protected $request;

    /**
     * Response header content type.
     *
     * @var string
     */
    public $responseContentType = 'application/json';

    /**
     * Constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getResponseType($type): string
    {
        switch ($type) {
            case 'end':
                return false;
                break;

            case 'continue':
            default:
                return true;
                break;
        }
    }

    /**
     * Format response to be sent to gateway provider.
     *
     * @param [type] $screen The screen to send back as response
     * @return string
     */
    public function format($screen): string
    {
        $responseMessage = $screen->message();

        $responseType = $this->getResponseType(
            $screen->responseType() ?: false
        );

        return json_encode([
            'USERID' 	=> $this->request->getSessionFieldValue(),
            'MSISDN' 	=> $this->request->getMSISDN(),
            'MSG' 		=> $responseMessage,
            'MSGTYPE' 	=> $responseType ? true : false,
        ]);
    }
}
