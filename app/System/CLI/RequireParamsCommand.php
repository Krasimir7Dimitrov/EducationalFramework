<?php

namespace App\System\CLI;

class RequireParamsCommand extends Commands
{

    public function execute()
    {
        $hi = $this->getOpt();

        $carsCollection = new \App\Model\Collections\CarsCollection();
        $lastParam = '0';
        foreach ($this->getRequireParams() as $requiredParam) {
            $replacedRequiredParam = str_replace(':', '', $requiredParam);
            if (empty($passedParams[$replacedRequiredParam])) {
                $passedParams[$replacedRequiredParam] = readline("{$replacedRequiredParam}: ");
                if ($passedParams[$replacedRequiredParam] < $lastParam) {
                    echo "End year must be equal or greater than Start year" . PHP_EOL;
                    exit(1); //End application with code difficult from null
                }
                $lastParam = $passedParams[$replacedRequiredParam];
                $toInt = intval($passedParams[$replacedRequiredParam]);
                if ($toInt === 0) {
                    echo "This value must be integer" . PHP_EOL;
                    exit(1);
                }
            }
        }

        $carsRegisteredBetween = $carsCollection->getCarsRegisteredBetween($passedParams['startYear'], $passedParams['endYear']);

        foreach ($carsRegisteredBetween as $value) {
            echo '--------------------------------------------------------' . PHP_EOL;
            foreach ($value as $key => $val) {
                echo str_pad( $key, 25 ) . '|   ' . $val . "\n";
            }
        }
    }

    public function getOpt()
    {
        return getopt('');
    }
}