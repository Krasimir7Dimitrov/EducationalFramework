<?php

namespace App\System\DesignPatterns\Observer;


use SplObserver;

class User implements \SplSubject
{
    private \SplObjectStorage $observers;
    private $email;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    /**
     * @inheritDoc
     */
    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * @inheritDoc
     */
    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    public function changeEmail(string $email): void
    {
        $this->email = $email;
        $this->notify();
    }


    /**
     * @inheritDoc
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}