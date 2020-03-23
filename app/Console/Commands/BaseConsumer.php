<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nsq;
use NsqLookupd;
use NsqMessage;
use NsqException;

abstract class BaseConsumer extends Command
{
    public function handle()
    {
        $nsqLookupd = new NsqLookupd("127.0.0.1:4161"); //the nsqlookupd http addr

        $nsq = new Nsq(
            [
                'channel' => 'web',
            ]
        );
        $config = [
            'topic' => $this->getTopic(),
            'channel' => $this->getChannel(),
            'rdy' => 2,                //optional , default 1
            'connect_num' => 1,        //optional , default 1
            'retry_delay_time' => 5000,  //optional, default 0 , if run callback failed, after 5000 msec, message will be retried
            'auto_finish' => true, //default true
        ];

        $nsq->subscribe(
            $nsqLookupd,
            $config,
            [$this, 'process']
        );
    }

    abstract public function getTopic(): string;

    public function getChannel(): string
    {
        return 'web';
    }

    abstract public function process(NsqMessage $nsqMessage, $bev);
}
