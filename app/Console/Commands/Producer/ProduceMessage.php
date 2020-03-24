<?php

namespace App\Console\Commands\Producer;

use Illuminate\Console\Command;

use Nsq;
use NsqLookupd;
use NsqMessage;
use NsqException;
use Illuminate\Foundation\Inspiring;

class ProduceMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produce:message {count}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $nsqdAddr = [
            "nsqd:4150"
        ];

        $nsq = new Nsq(
            [
                'channel' => 'web',
            ]
        );

        $nsq->connectNsqd($nsqdAddr);

        for ($i = 0; $i < $this->argument('count') ?? 1; $i++) {
            $message = [
                'id' => $i,
                'quotes' => Inspiring::quote(),
            ];
            $nsq->publish(
                'inspires',
                json_encode($message)
            );
        }
        $nsq->closeNsqdConnection();
    }
}
