<?php

namespace App\System\Debugbar;

class Decorator
{
    private $data;

    public function __construct(DebugBarDataInterface $debugBar)
    {
        $this->data = $debugBar->getDebugData();
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
                    if (is_array($val)) {
                        $secondCounter = 0;
                        foreach ($val as $thirdKey => $val2) {
                            if ($secondCounter === 0) {
                                array_push($singleArray, $key, $secondKey, $thirdKey, $val2);
                            }
                            if ($secondCounter !== 0) {
                                array_push($singleArray, '', '', $thirdKey, $val2);
                            }
                            $secondCounter++;
                            $singleArray = [];
                        }
                    }

                    if ($counter === 0) {
                        array_push($singleArray, $key, $secondKey, $val);
                    }
                    if ($counter !== 0) {
                        array_push($singleArray, '', $secondKey, $val);
                    }
                    $counter++;
                    if (!is_array($val))
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
        }
        fclose($fp);
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