<?php
namespace App\System\Asynchronous;

use function Amp\ParallelFunctions\parallelMap;
use function Amp\Promise\wait;

class ParallelCallTest extends \PHPUnit\Framework\TestCase
{
    public function testParallelCall()
    {
        $start = \microtime(true);
        $mysquare = function ($x) {
            sleep($x);
            return $x * $x;
        };

//        $array = [5,4,3,2,1,6,7,8,9,10];
//        foreach ($array as $item) {
//            $result = $mysquare($item);
//            print 'Took ' . (\microtime(true) - $start) . ' milliseconds' . \PHP_EOL;
//        }


        print_r(wait(parallelMap([5,4,3,2,1,6,7,8,9,10], $mysquare)));
        print 'Took ' . (\microtime(true) - $start) . ' milliseconds' . \PHP_EOL;

        die();
    }
}