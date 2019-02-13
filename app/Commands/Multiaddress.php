<?php

namespace App\Commands;

use Blockchain\Blockchain;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Multiaddress extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'addr:multi { ids="id1,id2,..." : Comma separated list of blockchaind IDs } { --d|download : }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get blockchain info from multiple addresses.';

  /**
   * Execute the console command.
   *
   * @return mixed
   * @throws \Blockchain\Exception\FormatError
   */
    public function handle()
    {
      $addresses = explode(',', $this->argument('ids'));
      $Blockchain = new Blockchain();

      $info = $Blockchain->Explorer->getMultiAddress($addresses);

      print "";

      return 0;

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
