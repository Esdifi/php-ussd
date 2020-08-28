<?php

use Dbilovd\PHP_USSD\Screens\Screen;
use PHPUnit\Framework\TestCase;

class ScreenBootableMethodsTest extends TestCase
{
    /** @test */
    public function when_a_new_screen_class_is_created_the_boot_method_should_be_executed()
    {
        $screen = new ScreenBootingTestStub;
        $this->assertTrue($screen->isBooted());
    }
}

class ScreenBootingTestStub extends Screen
{
    protected $customProperty = false;

    protected function boot(): void
    {
        $this->customProperty = true;
    }

    public function isBooted()
    {
        return $this->customProperty === true;
    }
}
