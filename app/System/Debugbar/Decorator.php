<?php

namespace App\System\Debugbar;

use App\System\Debugbar\Enums\DecorationTypes;

class Decorator
{
    private $data;

    public function __construct(array $debugBar)
    {
        $this->data = $debugBar;
    }

    public function returnJson()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    private function returnCsv()
    {
        $allArrays = [];
        foreach ($this->data as $key => $value) {
            $singleArray = [];
            if (is_array($value)) {
                $counter = 0;
                foreach ($value as $secondKey => $val) {
                    $singleSecondArray = [];
                    if (is_array($val)) {
                        $secondCounter = 0;
                        foreach ($val as $thirdKey => $secondVal) {
                            if ($secondCounter === 0) {
                                array_push($singleArray, '', $secondKey, $thirdKey, $secondVal);
                            }
                            if ($secondCounter !== 0) {
                                array_push($singleArray, '', '', $thirdKey, $secondVal);
                            }
                            $secondCounter++;
                            array_push($allArrays, $singleArray);
                            $singleArray = [];
                        }
                    }

                    if ($counter === 0 && !empty($val) && !is_array($val)) {
                        array_push($singleArray, $key, $secondKey, $val);
                    }
                    if ($counter !== 0 && !empty($val) && !is_array($val)) {
                        array_push($singleArray, '', $secondKey, $val);
                    }

                    $counter++;
                    if ($val !== "" && !is_array($val)) {
                        array_push($allArrays, $singleArray);
                    }
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

    private function downloadFile()
    {
        $filename = "persons.csv";
        header("Content-Type: text/csv; charset=UTF-16LE");
        header("Content-Disposition: attachment;filename=$filename");
        file_get_contents($filename);
        readfile($filename);
        exit();
    }

    private function returnHtml()
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
                    if (is_array($val)) {
                        $secondCounter = 0;
                        foreach ($val as $thirdKey => $secondVal) {
                            $html .= '<tr>';
                            if ($secondCounter === 0) {
                                $html .= '<th><th/>';
                                $html .= '<th>' . $secondKey . '<th/>';
                                $html .= '<td>' . $thirdKey . '<td/>';
                                $html .= '<td>' . $secondVal . '<td/>';
                            }
                            if ($secondCounter !== 0) {
                                $html .= '<th><th/>';
                                $html .= '<th><th/>';
                                $html .= '<td>' . $thirdKey . '<td/>';
                                $html .= '<td>' . $secondVal . '<td/>';
                            }
                            $secondCounter++;
                        }
                    }
                    $html .= '<tr>';
                    if ($counter === 0 && !is_array($val)) {
                        $html .= '<th>' . $key . '<th/>';
                        $html .= '<th>' . $secondKey . '<th/>';
                        $html .= '<td>' . $val . '<td/>';
                    }
                    if ($counter !== 0 && !is_array($val)) {
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

    public function render(DecorationTypes $type)
    {
        switch ($type) {
            case DecorationTypes::HTML():
                $this->returnHtml();
            break;

            case DecorationTypes::CSV():
                $this->returnCsv();
            break;

            case DecorationTypes::MYJSON():
                echo $this->returnJson();
            break;
        }
    }
}