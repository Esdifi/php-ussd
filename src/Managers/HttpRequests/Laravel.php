<?php

namespace Dbilovd\PHUSSD\Managers\HttpRequests;

class Laravel implements HttpRequestManagerContract
{
	/**
	 * [methodName description]
	 *
	 * @return [type] [description]
	 */
	public function request()
	{
		return request();
	}
}
