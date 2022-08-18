<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

$application = \App\System\Application::getInstance();
//$command = new \App\System\CLI\RequireParamsCommand();

$carsCollection = new \App\Model\Collections\CarsCollection();

$requiredParams = [
    'startYear:', // start date - required
    'endYear:', // end date - required
];
$optionalParams = [
    'sendEmailToUserId::', // send email - not required, the user id to send the email
    'usage', // print help usage - not required, no value
];

$passedParams = getopt('', $optionalParams);

if (isset($passedParams['usage'])) {
    // TODO: print the help menu
    echo 'usage menu' . PHP_EOL;

    exit(0);
}

$lastParam = '0';
foreach ($requiredParams as $requiredParam) {
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

if (isset($passedParams['sendEmailToUserId'])) {

    $html = '';
    $email = $carsCollection->getClientEmail((int)$passedParams['sendEmailToUserId'])['email'];


    $html .= "<table style='border: 1px solid black'>";
    $html .= "<tr>
                <th style='border: 1px solid black'>Make</th>
                <th></th>
                <th style='border: 1px solid black'>Model</th>
                <th></th>
                <th style='border: 1px solid black'>First registration</th>
              <tr/>";
    foreach ($carsRegisteredBetween as $value) {
        $html .= "<tr>";
            $html .= "<td style='border: 1px solid black'>{$value['make']}<td/>
                      <td style='border: 1px solid black'>{$value['model']}<td/>
                      <td style='border: 1px solid black'>{$value['first_registration']}<td/>";
        $html .= "<tr/>";
    }
    $html .= "<table/>";

    $mail = new \App\System\Email\Email();
    $mail->to = $email;
    $mail->subject = 'Kupuvaj bezotgovorno pich';
    $mail->body = $html;
    $send = new \App\System\Email\NotificationEmail($mail);
    $send->send();
}
