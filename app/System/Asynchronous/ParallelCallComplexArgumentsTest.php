<?php
namespace App\System\Asynchronous;

use function Amp\ParallelFunctions\parallelMap;
use function Amp\Promise\wait;

class ParallelCallComplexArgumentsTest extends \PHPUnit\Framework\TestCase
{
    public function testParallelCallComplexArguments()
    {
        $start = \microtime(true);

        $array = array_map(function () {
           return rand(0, 100);
        }, array_fill(0, 10, null));

        $paramsArray = [
            [
                'startTime' => $start,
                'array'     => $array,
                'object'    => new \stdClass(),
            ],
            [
                'startTime' => $start,
                'array'     => $array,
                'object'    => new \stdClass(),
            ],
            [
                'startTime' => $start,
                'array'     => $array,
                'object'    => new \stdClass(),
            ],
        ];

        $targetFunction = function ($data) {
            $functionExecutionTimeStart = \microtime(true);
            $difference = $data['startTime'] - $functionExecutionTimeStart;

            $result = [
                'time' => 'Execution time start = ' . $functionExecutionTimeStart,
                'difference' => 'Time diff = ' . $difference,
            ];

            return array_merge($result, $data);
        };

        try {
            var_dump(wait(parallelMap($paramsArray, $targetFunction)));
        } catch (\Exception $e) {
            var_dump($e->getReasons());
        }

        print 'Took ' . (\microtime(true) - $start) . ' milliseconds' . \PHP_EOL;

        die();
    }
}