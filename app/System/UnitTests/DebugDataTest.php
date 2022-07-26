<?php

namespace App\System\UnitTests;

use App\System\Debugbar\DebugData;
use PHPUnit\Framework\TestCase;

class DebugDataTest extends TestCase
{

    public static function getInstance()
    {
        return new DebugData("myUrl", "myIp", ["myKey" => "mySession"], "myController", "myControllerAction", 0.0, "myHttpMethod", ["myRequestData"], "myQuery", 5.5);
    }

    public function testGetController()
    {
        $debugData = self::getInstance();
        $controller = $debugData->getController();
        $this->assertEquals("myController", $controller);
    }

    public function testGetIp()
    {
        $debugData = self::getInstance();
        $ip = $debugData->getIp();
        $this->assertEquals("myIp", $ip);
        $this->assertIsString($ip);
    }

    public function testGetMemoryUsed()
    {
        $debugData = self::getInstance();
        $memory = $debugData->getMemoryUsed();
        $this->assertEquals(0, $memory);
        $this->assertIsInt($memory);
    }

    public function testGetHttpMethod()
    {
        $debugData = self::getInstance();
        $method = $debugData->getHttpMethod();
        $this->assertEquals('myHttpMethod', $method);
        $this->assertIsString($method);
    }

    public function testGetAction()
    {
        $debugData = self::getInstance();
        $action = $debugData->getAction();
        $this->assertEquals('myControllerAction', $action);
        $this->assertIsString($action);
    }

    public function testGetExecutionTime()
    {
        $debugData = self::getInstance();
        $execTime = $debugData->getExecutionTime();
        $this->assertEquals(5.5, $execTime);
        $this->assertIsFloat($execTime);
    }

    public function testGetUserSession()
    {
        $debugData = self::getInstance();
        $session = $debugData->getUserSession();
        $this->assertIsArray($session);
    }

    public function testGetQueryString()
    {
        $debugData = self::getInstance();
        $query = $debugData->getQueryString();
        $this->assertEquals('myQuery', $query);
        $this->assertIsString($query);
    }

    public function testGetRequestData()
    {
        $debugData = self::getInstance();
        $request = $debugData->getRequestData();
        $this->assertIsArray($request);
    }

    public function testGetBaseUrl()
    {
        $debugData = self::getInstance();
        $url = $debugData->getBaseUrl();
        $this->assertEquals('myUrl', $url);
        $this->assertIsString($url);
    }
}
