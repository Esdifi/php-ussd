<?php

namespace Dbilovd\PHUSSD\Listeners;

use Dbilovd\PHUSSD\Events\BillPaymentFormCompleted;
use Dbilovd\PHUSSD\Events\IssueRecorded;
use Dbilovd\PHUSSD\Events\IssueReported;
use Dbilovd\PHUSSD\Traits\CanSubmitABillPayment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class SubmitBillPayment
{
    use CanSubmitABillPayment;

    /**
     * Session ID of session to record
     *
     * @var String
     */
    protected $sessionId;

    /**
     * Handle the event.
     *
     * @todo   Move clearing of redis record to a different Listener
     * @param  IssueReported  $event
     * @return void
     */
    public function handle(BillPaymentFormCompleted $event)
    {
        $this->sessionId = $event->sessionId;

        $issue = $this->submitBillPayment(
            $this->fetchSessionData($this->sessionId)
        );

        if ($issue) {
            // @todo Move this to a different event
            // We need to remove Redis record to save cost
            // An ideal thing to do would be to back the record
            // somewhere cheaper - file or db - and remove from
            // there instead.
            Redis::hDel($this->sessionId, "*");
            Redis::del($this->sessionId);

            event(new IssueRecorded($issue, $this->sessionId));
        }
    }
}
