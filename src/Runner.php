<?php


namespace EasySwoole\Phpunit;


use PHPUnit\TextUI\Command;
use Swoole\ExitException;
use Swoole\Timer;
use Swoole\Coroutine\Scheduler;

class Runner
{
    public static function run($noCoroutine = true)
    {
        if($noCoroutine){
            try{
                return Command::main(false);
            }catch (\Throwable $throwable){
                /*
                 * 屏蔽swoole exit报错
                 */
                if(!$throwable instanceof ExitException){
                    throw $throwable;
                }
            }finally{
                Timer::clearAll();
            }
        }else{
            $exitCode = null;

            $scheduler = new Scheduler();
            $scheduler->add(function() use (&$exitCode) {
                $exitCode = Runner::run();
            });
            $scheduler->start();

            return $exitCode;
        }

    }
}