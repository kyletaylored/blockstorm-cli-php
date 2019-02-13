<?php
/**
 * Created by PhpStorm.
 * User: kyletaylor
 * Date: 2019-02-12
 * Time: 00:42
 */

namespace App\Utilities;

use Blockchain\Explorer\Address;
use Blockchain\Explorer\Transaction;

class TxRecord
{

    protected $hash;
    protected $dateFormat = "Y-m-d H:i:s";
    protected $date;
    protected $balance = 0;
    protected $records = [];
    protected $transaction;
    protected $walletId;

    /**
   * TxRecord constructor.
   *
   * @param \Blockchain\Explorer\Transaction $transaction
   */
    public function __construct(Transaction $transaction, Address $address) 
    {
        $this->transaction = $transaction;
        $this->date = $this->formatDate($transaction->time);
        $this->hash = $transaction->hash;
        $this->walletId = $address->address;
    }

    /**
   * Format date to common pattern.
   *
   * @param $time
   *
   * @return false|string
   */
    protected function formatDate($time) 
    {
        return date($this->dateFormat, $time);
    }

    /**
   * Fetch array of all records extracted out of transaction.
   *
   * @return array
   */
    public function getRecords() 
    {
        $this->processInputs();
        $this->processOutputs();
        $this->processFee();

        return $this->records;
    }

    /**
   * Process inputs into records.
   */
    protected function processInputs() 
    {
        foreach ($this->transaction->inputs as $input) {
            $this->balance += $input->value;
            $this->records[] = new Record($input->value, $input->address, null, null, null, $this->date, $this->hash, $input->tx_index, $this->walletId);
        }
    }

    /**
   * Process outputs into records.
   */
    protected function processOutputs() 
    {
        foreach ($this->transaction->outputs as $output) {
            $this->balance -= $output->value;
            $this->records[] = new Record(null, null, $output->value, $output->address, null, $this->date, $this->hash, $output->tx_index, $this->walletId);
        }
    }

    /**
   * Determine if final balance on transaction is a fee.
   */
    protected function processFee() 
    {
        if ($this->balance > 0) {
            $balance = number_format($this->balance, 8);
            $this->records[] = new Record(null, null, $balance, null, true, $this->date, $this->hash, null, $this->walletId);
        }
    }
}
