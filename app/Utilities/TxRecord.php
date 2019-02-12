<?php
/**
 * Created by PhpStorm.
 * User: kyletaylor
 * Date: 2019-02-12
 * Time: 00:42
 */

namespace App\Utilities\TxRecord;

use App\Utilities\Record\Record;
use Blockchain\Explorer\Transaction;

class TxRecord {

  protected $hash;

  protected $dateFormat = "Y-m-d\TH:i:s\Z";

  protected $date;

  protected $balance = 0;

  protected $records = [];

  protected $transaction;

  /**
   * TxRecord constructor.
   *
   * @param \Blockchain\Explorer\Transaction $transaction
   */
  public function __construct(Transaction $transaction) {
    $this->transaction = $transaction;
    $this->date = $this->formatDate($transaction->time);
    $this->hash = $transaction->hash;
  }

  /**
   * Format date to common pattern.
   *
   * @param $time
   *
   * @return false|string
   */
  protected function formatDate($time) {
    return date($this->dateFormat, $time);
  }

  /**
   * Fetch array of all records extracted out of transaction.
   * @return array
   */
  public function getRecords() {
    $this->processInputs();
    $this->processOutputs();
    $this->processFee();

    return $this->records;
  }

  /**
   * Process inputs into records.
   */
  protected function processInputs() {
    foreach ($this->transaction->inputs as $input) {
      $this->balance += $input->value;
      $this->records[] = new Record($input->value, $input->address, NULL, NULL, NULL, $this->date, $this->hash);
    }
  }

  /**
   * Process outputs into records.
   */
  protected function processOutputs() {
    foreach ($this->transaction->outputs as $output) {
      $this->balance -= $output->value;
      $this->records[] = new Record(NULL,NULL, $output->value, $output->address,NULL, $this->date, $this->hash);
    }
  }

  /**
   * Determine if final balance on transaction is a fee.
   */
  protected function processFee() {
    if ($this->balance > 0) {
      $this->records[] = new Record(NULL,NULL, $this->balance, NULL,TRUE, $this->date, $this->hash);
    }
  }
}
