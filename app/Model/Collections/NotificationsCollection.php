<?php

namespace App\Model\Collections;

class NotificationsCollection extends \App\System\BaseCollection
{
    protected $table = 'notifications';

    /**
     * @param string $state
     * @return mixed
     * @author Hristo Stoyanov <hstoyanov@advisebrokers.com>
     */
    public function createNotification(string $state)
    {
        return $this->db->insert($this->table, ['notification_state' => $state]);
    }

    public function getNotificationsForProcessing(int $count = 10)
    {
        $sql = "SELECT * FROM notifications AS n WHERE n.is_send = 0 LIMIT :count";

        return $this->db->fetchAll($sql, compact('count'));
    }

    public function markNotificationAsSent($notificationId)
    {
        return $this->update(['notification_id' => (int) $notificationId], ['is_send' => 1]);
    }
}