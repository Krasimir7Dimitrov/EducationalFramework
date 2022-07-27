<?php

namespace App\System\Notifications;

/**
 * Class EmailNotification
 * @package App\System\Notifications
 * @author Hristo Stoyanov <hstoyanov@advisebrokers.com>
 *
 * @property string $subject
 * @method test(string $testValue)
 */
class EmailNotification
{
    private string $from;
    private string $to;

    private static $instances = 0;
    private $instance;


    public function __construct()
    {
        echo 'Construction started at ' . time() . PHP_EOL;
        $this->from = 'hstoyanov@parachut.com';
        $this->to = 'psabev@parachut.com';

        $this->sendNotification();
        sleep(2);
        echo 'Construction ended at ' . time() . PHP_EOL;
        $this->instance = ++self::$instances;
    }

    public function __clone()
    {
        echo 'Increase the instance number' . PHP_EOL;
        $this->instance = ++self::$instances;
    }

    public function printInstanceNumber()
    {
        echo sprintf('The current instance number is -> %d', $this->instance);
    }

    private function sendNotification($to = null)
    {

        echo sprintf('Notification was sent to "%s"', ($to ?? $this->to)) . PHP_EOL;
    }

    public function __sleep()
    {
        echo 'I will make you sleep!' . PHP_EOL;

        return ['to'];
    }

    public function __serialize()
    : array
    {
        echo 'I will make you serialize!' . PHP_EOL;


        return ['to' => $this->to, 'mySpecialValue' => 65];
    }

    public function __wakeup()
    {
        echo 'Sending notification via __wakeup method is freaking awesome!!!' . PHP_EOL;
        $this->sendNotification();
    }

    public function __unserialize(array $data)
    : void
    {
        echo 'Unserialized like a boss!!' . PHP_EOL;
        $this->to = $data['to'];

        var_dump($data);

        echo 'Sending notification via __unserialize method is freaking awesome!!!' . PHP_EOL;

        $this->sendNotification();
    }

    public function __toString(): string
    {
        return __CLASS__ . ' to string was called!';
    }

    public function __invoke()
    {
        echo sprintf('Changing the state! $this->from = "%s"', $this->from);
    }

    public static function __set_state(array $array)
    {
        foreach ($array as $property => $value) {
            echo "key => '{$property}', value => '{$value}'";
        }
    }

    public function __debugInfo(): array
    {
        return [
            'test' => $this->from . ' prashta na ' . $this->to,
        ];
    }

    public function __set($name, $value)
    {
        echo "Setting '$name' to '$value'\n";
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __isset($name)
    {
        echo sprintf('Checking if we have a property "%s"', $name);
    }

    public function __unset($name)
    {
        echo sprintf('Trying to unset property "%s"', $name);
    }

    public function __call($name, $arguments)
    {
//        self::$counter++;
//        echo sprintf('Calling counts "%d"', self::$counter) . PHP_EOL;
//        $this->$name($arguments[0]);
    }
}