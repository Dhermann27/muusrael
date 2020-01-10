<?php

namespace Tests;

use Laracasts\Behat\Context\Services\MailTrap;

trait MuusaTrap
{
    use MailTrap;

    protected function fetchBody($inboxId = null, $messageId = null)
    {
        return $this->requestClient()->get($this->getMailTrapBodyUrl($messageId))->getBody();
    }

    protected function getMailTrapBodyUrl($id)
    {
        return "/api/v1/inboxes/{$this->mailTrapInboxId}/messages/" . $id . "/body.txt";
    }

}
