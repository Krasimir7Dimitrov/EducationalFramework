<?php

namespace App\System\Debugbar;

class Decorator
{
    private $data;

    public function __construct()
    {
        $debug = new Debugbar();
        $data = $debug->getDebugData();
        $this->data = $data;
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

    public function downloadFile()
    {
        $filename = "persons.csv";
        header("Content-Type: text/csv; charset=UTF-16LE");
        header("Content-Disposition: attachment;filename=$filename");
        file_get_contents($filename);
        readfile($filename);
        exit();
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

        echo $html;
    }
}