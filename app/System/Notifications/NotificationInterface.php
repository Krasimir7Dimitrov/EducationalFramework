<?php

namespace App\System\Notifications;

interface NotificationInterface
{
    /**
     * Try to send notification and returns if it is sent successfully
     * @return bool
     * @author Hristo Stoyanov <hstoyanov@advisebrokers.com>
     */
    public function send(): bool;

    public function postToQueue(): bool;
}