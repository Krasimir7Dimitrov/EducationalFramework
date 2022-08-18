<?php

namespace App\System\CLI;

abstract class Commands implements CommandInterface
{
    /**
     * @var array
     */
    private $requireParams;

    /**
     * @var array
     */
    private $optionalParams;


    public function __construct()
    {
        $this->setRequireParams();
        $this->setOptionalParams();
        $this->execute();
    }

    public abstract function execute();

    /**
     * @param array $optionalParams
     */
    private function setOptionalParams(): void
    {
        $optionalParams = [
            'sendEmailToUserId::', // send email - not required, the user id to send the email
            'usage',
            'hi'// print help usage - not required, no value
        ];
        $this->optionalParams = $optionalParams;
    }

    /**
     * @param array $requireParams
     */
    private function setRequireParams(): void
    {
        $requireParams = [
            'startYear:', // start date - required
            'endYear:', // end date - required
        ];
        $this->requireParams = $requireParams;
    }

    /**
     * @return array
     */
    protected function getOptionalParams(): array
    {
        return $this->optionalParams;
    }

    /**
     * @return array
     */
    protected function getRequireParams(): array
    {
        return $this->requireParams;
    }
}