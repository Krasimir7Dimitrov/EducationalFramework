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
    echo 'Usage menu' . PHP_EOL;
    $helperTextForCli = 'This script can be used to generate a report for all cars from the database registered between certain period of time. ';
    $helperTextForCli .= 'startYear and endYear could be passed as params both when prompted or directly after the script name followed by \'--\'. ';
    $helperTextForCli .= 'For example if you want to pass \'startYear\' before prompt, you should do the following \'docker compose run --rm composer report -- --startYear=2020\'. ';
    $helperTextForCli .= 'If there are other required params that you do not pass on the same line, you will be prompted to enter them when run the script. ';
    $helperTextForCli .= 'The following params are required for the script: \'startYear\', \'endYear\'. ';
    $helperTextForCli .= 'Those params are optional : \'sendEmailToUserId\', \'usage\' ';
    $helperTextForCli .= 'The param \'sendEmailToUserId\' can be passed in order to send email with the generated report to a certain user. This param will be the \'id\' from the database. ';
    $helperTextForCli .= 'For example if you want to generate a report for all cars registered between 2020 and 2022 and send it to user with \'id\' = 1, you should do the following : 
    \'docker compose run --rm composer report -- --startYear=2020 --endYear=2022 --sendEmailToUserId=1\'' . PHP_EOL;
    echo $helperTextForCli;
    exit(0);
}

foreach ($requiredParams as $requiredParam) {
    $replacedRequiredParam = str_replace(':', '', $requiredParam);
    // TODO: validate the input to be correct value. Hint endYear must be greater than or equal to startYear
    if (empty($passedParams[$replacedRequiredParam])) {
        $passedParams[$replacedRequiredParam] = readline("{$replacedRequiredParam}: ");
    }
}

if (isset($passedParams['startYear']) && isset($passedParams['endYear'])) {
    if ($passedParams['endYear'] < $passedParams['startYear']) {
        echo 'The endYear should be greater than or equal to startYear' . PHP_EOL;
        exit(6);
    }
}
require_once __DIR__ . '/../../../vendor/autoload.php';

// Get application instance so I can make DB queries
$application = \App\System\Application::getInstance();

$carsCollection = new \App\Model\Collections\CarsCollection();
$carsRegisteredBetween = $carsCollection->getCarsRegisteredBetween($passedParams['startYear'], $passedParams['endYear']);
if (isset($passedParams['sendEmailToUserId'])) {
    $userEmail = $carsCollection->getEmailByUserId($passedParams['sendEmailToUserId']);
}

//print command line params as a table
$table = "|%10s |%10s |%20s |". PHP_EOL;
printf($table, 'Start Year', 'End Year', 'Send Email To User');
printf($table, $passedParams['startYear'], $passedParams['endYear'], $passedParams['sendEmailToUserId'] ? 'Yes' : 'No');

$tableRows = [];
foreach ($carsCollection as $index => $item) {
    $tableRows[] = $item;
}

var_dump($tableRows);

//$resultsTable = "|%20s |%20s |%20s |%20s |%20s |%20s |%20s";
//printf($resultsTable, 'ID', 'MAKE', 'MODEL', 'REGISTRATION YEAR', 'TRANSMISSION', 'CREATED AT', 'UPDATED AT');
//printf($resultsTable, )
//var_dump($carsRegisteredBetween);
