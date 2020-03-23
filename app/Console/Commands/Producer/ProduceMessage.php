<?php

namespace App\Console\Commands\Producer;

use Illuminate\Console\Command;

use Nsq;
use NsqLookupd;
use NsqMessage;
use NsqException;

class ProduceMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produce:message';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $message = [
            'id' => time(),
            'message' => 'Olala',
        ];

        $nsqdAddr = [
            "127.0.0.1:4150"
        ];

        $nsq = new Nsq(
            [
                'channel' => 'web',
            ]
        );

        $nsq->connectNsqd($nsqdAddr);

        for ($i = 0; $i < 10000; $i++) {
            $nsq->publish(
                'some-test-queue',
                json_encode($message)
            );
        }
        $nsq->closeNsqdConnection();
    }
}
