<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nsq;
use NsqLookupd;
use NsqMessage;
use NsqException;

abstract class BaseConsumer extends Command
{
    /** @var Nsq */
    protected $nsqConsumer;
    protected $nsqProducer;
    public function handle()
    {
        $nsqLookupd = new NsqLookupd("nsqlookupd:4161"); //the nsqlookupd http addr

        $this->nsqConsumer = new Nsq(
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

        $method = env('USE_FAILOVER', false) ? 'processWithFailover' : 'process';
        $this->nsqConsumer->subscribe(
            $nsqLookupd,
            $config,
            [$this, $method]
        );
    }

    abstract public function getTopic(): string;

    public function getChannel(): string
    {
        return 'web';
    }

    public function processWithFailover(NsqMessage $nsqMessage, $bev) {
        try {
            $this->process(...func_get_args());
        }
        catch (\Exception $ex) {
            if (!$this->nsqProducer) {
                $nsqdAddr = [
                    "nsqd:4150"
                ];

                $this->nsqProducer = new Nsq(
                    [
                        'channel' => 'web',
                    ]
                );

                $this->nsqProducer->connectNsqd($nsqdAddr);
            }
            $this->nsqProducer->publish('failover', json_encode($nsqMessage));
        }
    }
    abstract public function process(NsqMessage $nsqMessage, $bev);
}
