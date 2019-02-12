<?php

namespace App\Commands;

use App\Utilities\TxRecord\TxRecord;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Address extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'addr:raw { id : The blockchain address id } { --a|all : Fetch all transactions } { --d|download : Boolean to download output to CSV }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get blockchain address information.';

    protected $records = [];
    private $limit = 50;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $id = $this->argument('id');
      $Blockchain = new \Blockchain\Blockchain();
      $address = $Blockchain->Explorer->getAddress($id);

      if ($this->option('all')) {
        $this->processTx($address);
      }

      return (json_encode($address));

    }

    protected function processAllTx (\Blockchain\Explorer\Address $address) {
      // Process all transactions if more available.
      if (count($address->transactions) < $address->n_tx) {
        for ($i = $this->limit; $i < $address->n_tx; $i += $this->limit) {
          $t = '';
        }
      }
    }

    protected function processTx (\Blockchain\Explorer\Address $address) {
      foreach ($address->transactions as $transaction) {
        $records = new TxRecord($transaction);
        array_push($this->records, $records->getRecords());
      }
    }

    public function getTx (\Blockchain\Explorer\Address $address) {
      $txs = [];
      for ($i = 50; $i < $address->n_tx; $i = $i + 50) {

      }
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
