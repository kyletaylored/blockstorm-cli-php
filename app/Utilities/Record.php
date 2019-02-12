<?php
/**
 * Created by PhpStorm.
 * User: kyletaylor
 * Date: 2019-02-12
 * Time: 01:41
 */

namespace App\Utilities\Record;

class Record {
  public $inputValue;
  public $inputAddress;
  public $outputValue;
  public $outputAddress;
  public $fee;
  public $date;
  public $hash;

  public function __construct($iv = NULL, $ia = NULL, $ov = NULL, $oa = NULL, $f = NULL, $d = NULL, $h = NULL) {
    $this->inputValue = $iv;
    $this->inputAddress = $iv;
    $this->outputValue = $ov;
    $this->outputAddress = $oa;
    $this->fee = $f;
    $this->date= $d;
    $this->hash= $h;
  }
}