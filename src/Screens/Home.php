<?php

namespace Esdifi\PHP_USSD\Screens;

class Home extends Screen
{
    /**
     * Type of USSD Response given by this page.
     *
     * @var string
     */
    public $responseType = 'continue';

    /**
     * Session Data Field Key.
     *
     * @var string
     */
    protected $dataFieldKey = 'homeOptionSelected';

    /**
     * Menus within this page.
     *
     * @var array
     */
    protected $menus = [
        '1' => 'Continue',
        '2' => 'Cancel',
    ];

    /**
     * Return message to send back to client.
     *
     * @return string Message to return to client
     */
    public function message()
    {
        $menus = $this->menus();
        array_walk($menus, function (&$value, $key) {
            $value = "{$key}. $value";
        });
        $menus = implode("\r\n", $menus);

        return "Welcome to PHP USSD (PhUSSD) \r\n\r\n{$menus}";
    }

    /**
     * Return an instance of the next Page class to be sent to the user based on the user's last input.
     *
     * @param string $selectedOption
     * @return bool|string
     */
    public function next($selectedOption)
    {
        $className = false;

        switch ($selectedOption) {
            case '1':
            case '2':
                $className = HomeResponsePage::class;
                break;
        }

        return $className;
    }
}
