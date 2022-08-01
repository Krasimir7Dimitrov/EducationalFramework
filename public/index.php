<?php
require_once __DIR__ . '/../vendor/autoload.php';

//$email = new \App\System\Notifications\Email\EmailNotification();
//var_dump($email);
//$email->printInstanceNumber();

//$cloneOfEmail = clone $email;
//var_dump($cloneOfEmail);

//$cloneOfEmail->printInstanceNumber();


//echo $email->test;
//
//$email->test();
//
//$email->alabala('shtaiga');

//unset($email->su);

//App\System\Notifications\EmailNotification::__set_state(array( 'from' => 'hstoyanov@parachut.com', 'to' => 'psabev@parachut.com', ));

//array ( 0 => 'print', 1 => 'echo', 2 => 101, )

//$email('kdimitrov@parachut.com', 'yozdhan', 'plamen');

//$serialize = serialize($email);
//var_dump($serialize);
//
//$emailUnserialized = unserialize($serialize);
//
//var_dump($emailUnserialized);

$application = \App\System\Application::getInstance();
$application->run();
