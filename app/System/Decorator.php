<?php

namespace App\System;

use App\System\Debugbar\DebugbarDataInterface;

class Decorator
{
    public $data;

    public function __construct(DebugbarDataInterface $debugbarData)
    {
        $this->data = $debugbarData->getDebugData();
        $this->htmlAndJson();
    }

    public function htmlAndJson()
    {
        $html = '<table>';
        $html .= '<tr>';

        foreach ($this->data as $key => $value) {
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }

        $html .= '</tr>';
        $html .= '<tr>';

        foreach ($this->data as $key => $value) {
            $html .= '<td>' . htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT)) . '</td>';
        }

        $html .= '</tr>';
        $html .= '</table>';

        $jsonResult = json_encode($this->data, JSON_PRETTY_PRINT);

        $this->render($html, $jsonResult);
    }

    public function csv($data)
    {
        $data1 = [];
        foreach ($data as $key => $value) {
            $data2 = [];
            if (is_array($value)) {
                $counter = 0;
                foreach ($value as $index => $item) {
                    if ($counter === 0) array_push($data2, $key, $index, $item);
                    if ($counter !== 0) array_push($data2, '', $index, $item);
                    $counter++;
                    array_push($data1, $data2);
                    $data2= [];
                }
            } else {
                array_push($data2, $key, $value);
                array_push($data1, $data2);
            }

        }

        //Create a CSV file
        $file = fopen('Debug Data Exported.csv', 'w');
        foreach ($data1 as $line) {
            //put data into csv file
            fputcsv($file, $line);
        }
        fclose($file);
    }

    public function render($htmlResult = '', $jsonResult = '')
    {
        $html = '<h5>Debug bar</h5>';
        $html .= '<a href="Debug Data Exported.csv">Download Debug information as CSV</a>';

    $html .= '<input type="checkbox" id="title1" />
            <label for="title1">HTML</label>';

        $html .= '<div class="content">
            <p>' . $htmlResult . '</p>
            </div>';

        $html .= '<input type="checkbox" id="title2" />
            <label for="title2">JSON</label>';

        $html .= '<div class="content">
            <pre>' . $jsonResult . '</pre>
            </div>';

        echo $html;
    }
}