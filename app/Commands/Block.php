<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Block extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'block:latest';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
      $Blockchain = new \Blockchain\Blockchain();
      $latest = $Blockchain->Explorer->getLatestBlock();
      $latest = $Blockchain->Explorer->getAddress("1A1ryxZtz4LX7o1GEne9Qz49w3DFgfJaB");
      return (json_encode($latest));
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
