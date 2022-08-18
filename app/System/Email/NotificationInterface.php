<?php

namespace App\System\Email;

interface NotificationInterface
{
    public function send();

    public function postQueue();
}