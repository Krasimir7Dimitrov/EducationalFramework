<?php

namespace App\System\Debugger;

class Debugger
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

    /**
     * @var string
     */
    private $userSession;

    private $controller;

    private $action;

    private $memoryUsed;

    //private $executionTime;

    private $httpMethod;

    private $queryString;

    private $postData;


    public function __construct()
    {
        $this->setBaseUrl();
        $this->setIp();
        $this->setUserSession();
        $this->setController();
        $this->setAction();
        $this->setMemoryUsed();
        $this->setHttpMethod();
        $this->setQueryString();
        $this->setPostData();
        $this->uniteAllValuesInArray();
        //$this->setExecutionTime();
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
        $baseUrl = ('URL: http://' . !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'N\A' . !empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : 'N\A';
        $this->baseUrl = $baseUrl;
    }

    private function setIp()
    {
        $this->ip = 'IP: ' . !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N\A';
    }

    private function setUserSession():void
    {
        $userSession = $_SESSION['user'];
        $this->userSession = $userSession;
    }

    private function setController()
    {
        $this->controller = 'CONTROLLER: ' . __CLASS__;
    }

    private function setAction()
    {
        $this->action = " ACTION: ";
    }

    private function setMemoryUsed()
    {
        $this->memoryUsed = 'MEMORY USAGE: '. memory_get_usage();
    }

    private function setExecutionTime()
    {
        $startTime = Registry::get('startTime');
        $endTime = Registry::get('endTime');
        $this->executionTime = 'EXECUTION TIME: ' . ($endTime - $startTime);
    }

    private function setHttpMethod()
    {
        $this->httpMethod = 'HTTP METHOD: ' . !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'N/A';
    }

    private function setQueryString()
    {
        $this->queryString = 'QUERY STRING: ' . !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : 'N/A';
    }

    private function setPostData()
    {
        $this->postData = !empty($_POST) ? $_POST : 'N/A';
    }

    private function uniteAllValuesInArray()
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties();

        foreach ($props as $prop) {
            if ($prop->name !== 'data') {
                $this->data[$prop->name] = $this->{$prop->name};
            }
        }
    }

    public function returnJson()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    public function returnCsv()
    {
        $allArrays = [];
        foreach ($this->data as $key => $value) {
            $singleArray = [];
            if (is_array($value)) {
                $counter = 0;
                foreach ($value as $secondKey => $val) {
                    if ($counter === 0) {
                        array_push($singleArray, $key, $secondKey, $val);
                    }
                    if ($counter !== 0) {
                        array_push($singleArray, '', $secondKey, $val);
                    }
                    $counter++;
                    array_push($allArrays, $singleArray);
                    $singleArray = [];
                }
            }
            if (!is_array($value)) {
                array_push($singleArray, $key, $value);
                array_push($allArrays, $singleArray);
            }
        }

        $fp = fopen('persons.csv', 'w');

        // Loop through file pointer and a line
        foreach ($allArrays as $fields) {
            fputcsv($fp, $fields);
        }        fclose($fp);

    }


    public function returnHtml()
    {
    $html = '<style>
                table, th, td {
                    border: 1px solid black;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 5px;
                    text-align: left;
                },
                td:empty {
                  visibility: hidden;
                }
            </style>';
        $html .=  '<hr>';
        $html .= '<h1>This is debug mode<h1/>';
        $html .= '<table>';
        foreach ($this->data as $key => $value) {
            $html .= '<tr>';
            if (is_array($value)) {
                $counter = 0;
                foreach ($value as $secondKey => $val) {
                    $html .= '<tr>';
                    if ($counter === 0) {
                        $html .= '<th>' . $key . '<th/>';
                        $html .= '<th>' . $secondKey . '<th/>';
                        $html .= '<td>' . $val . '<td/>';
                    }
                    if ($counter !== 0) {
                        $html .= '<th><th/>';
                        $html .= '<th>' . $secondKey . '<th/>';
                        $html .= '<td>' . $val . '<td/>';
                    }
                    $counter++;
                    $html .= '<tr/>';
                }
            }
            if (!is_array($value)) {
                $html .= '<th>' . $key . '<th/>';
                $html .= '<td>' . $value . '<td/>';
            }
            $html .= '<tr/>';
        }
        $html .= '<table/>';

        return $html;
    }


}
