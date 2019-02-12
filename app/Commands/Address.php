<?php

namespace App\Commands;

use App\Utilities\TxRecord;
use Blockchain\Blockchain;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use SoapBox\Formatter\Formatter;

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
    protected $balance = 0;
    private $limit = 50;
    private $delay = 1.5;

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

      // Set balance;
      $this->balance = $address->final_balance;

      // Get all transaction history
      if ($this->option('all')) {
        $this->processAllTx($address, $Blockchain);
      }

      if ($this->option('download')) {
        $formatter = Formatter::make($this->records, Formatter::ARR);
        file_put_contents($address->address . "_txs.csv", $formatter->toCsv());
      }

      var_dump (json_encode($address));

    }

    protected function processAllTx (\Blockchain\Explorer\Address $address, Blockchain $Blockchain) {
      // Process all transactions if more available.
      for ($i = 0; $i < $address->n_tx; $i += $this->limit) {
        $address = $Blockchain->Explorer->getAddress($address->address, $this->limit, $i);
        $this->processTx($address);
        $this->info("Processing batch: $i of $address->n_tx");
        sleep($this->delay); // To fix rate limiting.
      }
    }

    protected function processTx (\Blockchain\Explorer\Address $address) {
      foreach ($address->transactions as $transaction) {
        $records = new TxRecord($transaction, $address);
        foreach ($records->getRecords() as $record) {
          $this->balance += $record->netBalance();
          $record->setBalance(number_format($this->balance, 8));
          array_push($this->records, (array) $record);
        }
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
