<?php

namespace App\System\Notifications\Email;

/**
 * @property string $to
 * @property string $subject
 * @property string $body
 */
class Email
{
    private static array $allowedProperties = [
        'to', 'subject', 'body'
    ];

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (!in_array($name, self::$allowedProperties)) {
            throw new \Exception(sprintf('The property "%s" is not allowed!!!', $name));
        }

        $this->$name = $value;
    }
}