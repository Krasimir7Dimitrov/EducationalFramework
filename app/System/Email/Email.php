<?php

namespace App\System\Email;

/**
 * @property $to
 * @property $subject
 * @property $body
 */
class Email
{
    private static array $propertyArray = [
        'to', 'subject', 'body'
    ];

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        if (!in_array($name, self::$propertyArray)) {
            throw new \Exception(sprintf('The property %s is incorrect!', $name));
        }
        $this->$name = $value;
    }
}