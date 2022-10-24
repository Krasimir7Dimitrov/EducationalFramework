<?php

namespace App\System\DesignPatterns\Observer;

use SplSubject;

class UserObserver implements \SplObserver
{
    private array $changedUsers = [];

    /**
     * @inheritDoc
     */
    public function update(SplSubject $subject): void
    {
        $this->changedUsers[] = clone $subject;
    }

    /**
     * @return array
     */
    public function getChangedUsers(): array
    {
        return $this->changedUsers;
    }
}