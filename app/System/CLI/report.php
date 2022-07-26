<?php

$requiredParams = [
    'startYear:', // start date - required
    'endYear:', // end date - required
];
$optionalParams = [
    'sendEmailToUserId::', // send email - not required, the user id to send the email
    'usage', // print help usage - not required, no value
];

$passedParams = getopt('', array_merge($requiredParams, $optionalParams));

if (isset($passedParams['usage'])) {
    // TODO: print the help menu
    echo 'usage menu' . PHP_EOL;

    exit(0);
}

//print_r($params);

foreach ($requiredParams as $requiredParam) {
    $replacedRequiredParam = str_replace(':', '', $requiredParam);
    // TODO: validate the input to be correct value. Hint endYear must be greater than or equal to startYear
    if (empty($passedParams[$replacedRequiredParam])) {
        $passedParams[$replacedRequiredParam] = readline("{$replacedRequiredParam}: ");
    }
}

//var_dump($passedParams);
require_once __DIR__ . '/../../../vendor/autoload.php';

// Get application instance so I can make DB queries
$application = \App\System\Application::getInstance();

$carsCollection = new \App\Model\Collections\CarsCollection();

$carsRegisteredBetween = $carsCollection->getCarsRegisteredBetween($passedParams['startYear'], $passedParams['endYear']);
print_r($carsRegisteredBetween);

// TODO print those as a table