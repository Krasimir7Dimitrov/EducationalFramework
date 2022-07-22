<?php

namespace App\System;

class Decorator
{
    public $data;

    public function __construct($format)
    {
        $this->data = Registry::get('debugBarProps');
        $this->formatFactory($format);
    }

    public function formatFactory($format)
    {
        switch ($format) {
            case 'html':
                $this->html($this->data);
                break;
            case 'json':
                $this->json($this->data);
                break;
            case 'csv':
                $this->csv($this->data);
                break;
            default:
                $this->html($this->data);
        }

    }

    public function html($data)
    {
        $html = '<table>';
        $html .= '<tr>';

        foreach ($data as $key => $value) {
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }

        $html .= '</tr>';
        $html .= '<tr>';

        foreach ($data as $key => $value) {
            $html .= '<td>' . htmlspecialchars($value) . '</td>';
        }

        $html .= '</tr>';
        $html .= '</table>';

        echo $html;
    }

    public function json($data)
    {
        $result = json_encode($data, JSON_PRETTY_PRINT);
        var_dump($result);
    }

    public function csv($data)
    {
        $data1 = [];
        foreach ($data as $key => $value) {
            $data2 = [];

            array_push($data2, $key, $value);
            array_push($data1, $data2);
        }

        //Create a CSV file
        $file = fopen('Debug Data Exported.csv', 'w');
        foreach ($data1 as $line) {
            //put data into csv file
            fputcsv($file, $line);
        }
        fclose($file);
    }

    public function render($result)
    {

    }
}