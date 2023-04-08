<?php
namespace App\System\Asynchronous;

use GuzzleHttp\Client;
use function Amp\ParallelFunctions\parallelMap;
use function Amp\Promise\wait;
use Amp\MultiReasonException;

class ParallelCallMultipleUrl extends \PHPUnit\Framework\TestCase
{
    public function testParallelCallMultipleUrl()
    {
        $start = \microtime(true);
        $testUrl = "https://api.rapidmock.com/mocks/89mEw";

        $paramArray = [
            [
                'startTime'     => $start,
                'url'           => $testUrl,
                'headerDelay'   => 10000
            ],
            [
                'startTime'     => $start,
                'url'           => $testUrl,
                'headerDelay'   => 7500
            ],
            [
                'startTime'     => $start,
                'url'           => $testUrl,
                'headerDelay'   => 500
            ],
            [
                'startTime'     => $start,
                'url'           => $testUrl,
                'headerDelay'   => 8600
            ],
            [
                'startTime'     => $start,
                'url'           => $testUrl,
                'headerDelay'   => 9200
            ],
        ];

        $targetFunction = function ($data) {
            $functionExecutionTimeStart = microtime(true);
            $difference = $data['startTime'] - $functionExecutionTimeStart;

            //Create a new Guzzle client
            $client = new Client();
            //Define the request headers
            $headers = [
                'x-rapidmock-delay' => $data['headerDelay']
            ];
            //Make the request
            $response = $client->get($data['url'],
            [
                'headers' => $headers
            ]);
            //Get the response body as a string
            $body = $response->getBody()->getContents();

            $result = [
                'time' => 'Function execution start = ' . $functionExecutionTimeStart,
                'difference' => $difference,
                'body' => $body
            ];

            return array_merge($result, $data);
        };


        try {
            print_r(wait(parallelMap($paramArray, $targetFunction)));
        } catch (\Throwable $e) {
            var_dump($e->getReasons());
        }

        print 'Took ' . (\microtime(true) - $start) . ' milliseconds' . \PHP_EOL;

        die();
    }
}