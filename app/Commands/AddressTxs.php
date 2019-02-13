<?php

namespace App\Commands;

use App\Utilities\Toolbelt;
use App\Utilities\TxRecord;
use Blockchain\Blockchain;
use LaravelZero\Framework\Commands\Command;
use SoapBox\Formatter\Formatter;

class AddressTxs extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'addr:txs { id : The blockchain address id } { --a|all : Fetch all transactions } { --d|download : Boolean to download output to CSV }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get transaction history for blockchain address.';

    /**
     * Base records array to store for processing at the end.
     *
     * @var array
     */
    protected $records = [];

    /**
     * Maintains running balance.
     *
     * @var float
     */
    protected $balance = 0;

    /**
     * Fixed limit for blockchain.info requests.
     *
     * @var integer
     */
    private $limit = 50;

    /**
     * Delay of sleep requests in seconds.
     *
     * @var float
     */
    private $delay = 1.5;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $Blockchain = new Blockchain();
        $address = $Blockchain->Explorer->getAddress($id);

        // Set balance;
        $this->balance = $address->final_balance;

        // Get all transaction history
        if ($this->option('all')) {
            $this->processAllTx($address, $Blockchain);
        }

        // Format all records into array.
        $formatter = Formatter::make($this->records, Formatter::ARR);

        if ($this->option('download')) {
            $name = $id . "_txs.csv";
            file_put_contents($name, $formatter->toCsv());
            $this->alert("$name has been downloaded to this directory.");
        } else {
            print $formatter->toJson();
        }
        return 0;
    }

    /**
   * Process all transactions instead of the base limit by Blockchain.info.
   *
   * @param \Blockchain\Explorer\Address $address
   * @param \Blockchain\Blockchain       $Blockchain
   */
    protected function processAllTx(\Blockchain\Explorer\Address $address, Blockchain $Blockchain) 
    {
        // Process all transactions if more available.
        $this->output->progressStart($address->n_tx);
        // Loop through all transactions.
        for ($i = 0; $i < $address->n_tx; $i += $this->limit) {
            $address = $Blockchain->Explorer->getAddress($address->address, $this->limit, $i);
            $this->processTx($address);

            // To fix rate limiting.
            sleep($this->delay);
        }
        // Finish progress bar.
        $this->output->progressFinish();
    }

    /**
   * Process single transaction to parse through inputs/outputs.
   *
   * @param \Blockchain\Explorer\Address $address
   */
    protected function processTx(\Blockchain\Explorer\Address $address) 
    {
        foreach ($address->transactions as $transaction) {
            $this->output->progressAdvance();
            $records = new TxRecord($transaction, $address);
            foreach ($records->getRecords() as $record) {
                $this->balance += $record->netBalance();
                $record->setBalance(Toolbelt::toSatoshi($this->balance));
                array_push($this->records, (array) $record);
            }
        }
    }
}
