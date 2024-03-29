<?php
$startTime = microtime(true);
 echo $startTime . PHP_EOL;

require_once __DIR__ . '/../../../vendor/autoload.php';
\App\System\Registry::set('startTime2', $startTime);

\App\System\Registry::set('startTime', $startTime);
// Get application instance so I can make DB queries
$application = \App\System\Application::getInstance();

$notificationsCollection = new \App\Model\Collections\NotificationsCollection();

$notificationsForProcessing = $notificationsCollection->getNotificationsForProcessing(249);

$counter = 0;
foreach ($notificationsForProcessing as $notification) {
    //echo sprintf('Unserializing notification id "%s"', $notification['notification_id']) . PHP_EOL;
    $email = unserialize($notification['notification_state']);
    $emailNotification = new \App\System\Notifications\Email\EmailNotification($email);
    //echo 'Sending the notification' .  PHP_EOL;
    if ($emailNotification->send()) {
        echo 'Notification sent!' . $counter++ . PHP_EOL;
        $notificationsCollection->markNotificationAsSent($notification['notification_id']);
    }
}

echo 'THE END. Execution time was: ' . (microtime(true) - $startTime) . PHP_EOL;