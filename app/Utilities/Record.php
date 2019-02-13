<?php
/**
 * Created by PhpStorm.
 * User: kyletaylor
 * Date: 2019-02-12
 * Time: 01:41
 */

namespace App\Utilities;

use function App\BlockchainInfo\balance;

class Record
{
    public $date;
    public $wallet;
    public $hash;
    public $index;
    public $inputValue;
    public $inputAddress;
    public $outputValue;
    public $outputAddress;
    public $fee;
    public $running_balance;

    public function __construct($iv = null, $ia = null, $ov = null, $oa = null, $f = null, $d = null, $h = null, $i = null, $w = null) 
    {
        $this->date= $d;
        $this->hash= $h;
        $this->wallet = $w;
        $this->index = $i;
        $this->inputValue = $iv;
        $this->inputAddress = $ia;
        $this->outputValue = $ov;
        $this->outputAddress = $oa;
        $this->fee = $f;
    }

    public function netBalance() 
    {
        if ($this->outputValue) {
            return 0 - $this->outputValue;
        } else {
            return $this->inputValue;
        }
    }

    public function setBalance($balance) 
    {
        $this->running_balance = $balance;
    }
}