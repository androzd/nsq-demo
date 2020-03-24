<?php

namespace App\Console\Commands\Consumer;

use App\Console\Commands\BaseConsumer;
use Nsq;
use NsqLookupd;
use NsqMessage;
use NsqException;

class JobListener extends BaseConsumer
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:listen';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function process(NsqMessage $msg, $bev)
    {
        $payload = json_decode($msg->payload, true);
        $this->info('Message received: '.json_encode($msg));
        logger()->info('message received', [
            'payload' => $payload,
            'messageId' => $msg->messageId,
            'timestamp' => $msg->timestamp,
            'attempts' => $msg->attempts,
        ]);
    }

    public function getTopic(): string
    {
        return 'some-test-queue';
    }
}
