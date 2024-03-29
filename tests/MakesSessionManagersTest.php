<?php

use Esdifi\PHP_USSD\Contracts\SessionManagersContract;
use Esdifi\PHP_USSD\Traits\MakesSessionManagers;
use PHPUnit\Framework\TestCase;

class MakesSessionManagersTest extends TestCase
{
    /** @test */
    public function it_returns_an_instance_of_session_managers_contract()
    {
        $sessionManager = $this->getMockForTrait(MakesSessionManagers::class)
            ->makeSessionManager();

        $this->assertTrue($sessionManager instanceof SessionManagersContract);
    }
}
