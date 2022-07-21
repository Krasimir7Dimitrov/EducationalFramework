<?php

namespace App\System\Debugbar;

class AbstractDebugger
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $ip;

    public function __construct()
    {
        $this->setBaseUrl();
        $this->setIp();
        $this->uniteAllValuesInArray();
    }

    /**
     * @return array
     */
    public function getDebugData(): array
    {
        return $this->data;
    }

    private function setBaseUrl():void
    {
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->baseUrl = $baseUrl;
    }

    private function setIp():void
    {
        $ip = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->ip = $ip;
    }

    public function uniteAllValuesInArray()
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties();

//        foreach ($props as $prop) {
//            $this->data[$prop->name] = $this->{$prop->name};
//        }

        for ($x = 0; $x <= count($props); $x++) {
            $this->data[$prop->name[$x]] = $this->{$props[$prop->name]};
        }
    }
}