<?php

$shortParams = '';     // initialization
$shortParams .= 's:';  // start date - required
$shortParams .= 'e:';  // end date - required
$shortParams .= 'm::'; // send email - not required, boolean
$shortParams .= 'u::'; // user id to send email - not required, int
$longParams  = [
    'startYear:', // start date - required
    'endYear:', // end date - required
    'sendEmail::', // send email - not required, boolean
    'userId::', // user id to send email - not required, int
    'usage', // print help usage - not required, no value
];

$params = getopt($shortParams, $longParams);

if (empty($params) || isset($params['usage'])) {
    // TODO: print the help menu
    echo 'usage menu' . PHP_EOL;

    exit(0);
}

print_r($params);