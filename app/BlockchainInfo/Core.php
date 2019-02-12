<?php
/**
 * Created by PhpStorm.
 * User: kyletaylor
 * Date: 2019-02-11
 * Time: 22:36
 */

namespace App\BlockchainInfo;

use GuzzleHttp\Client;

class Core {

  protected $host = 'https://blockchain.info';
  protected $delay = 2500;
  protected $cors = false;


- rawblock/$blockhash
- rawtx/$tx_hash
- charts/$chart-type?format=json
- block-height/$block_height?format=json
- rawaddr/$btc_address
- multiaddr?active=$address|$address
- unspent?active=$address|$address
- balance?active=$address
- latestblock
- unconfirmed-transactions?format=json


  public function rawBlock ($hash) {
    return $this->request($hash);
  }

  public function rawTx ($txid) {
    return $this->request($txid);
  }

public function addrValidate (addr) {
  return request(addrValidate, addr)
}

public function addr () {
  let args = Array.from(arguments)
  let address = args[0]
  let opts

  switch (args.length) {
    case 1:
      opts = {}
      break
    case 2:
      opts = args[1]
      break
    default:
      throw new TypeError('Invalid arguments')
  }

  return request(addr, [address, opts])
}

public function balance (addr) {
  return request(balance, addr)
}

public function totalReceived (addr) {
  return request(totalReceived, addr)
}

public function totalSent (addr) {
  return request(totalSent, addr)
}

public function unconfirmedBalance (addr) {
  return request(unconfirmedBalance, addr)
}

public function utxo rawblock() {
  let args = Array.from(arguments)
  let address = args[0]
  let opts

  switch (args.length) {
    case 1:
      opts = {}
      break
    case 2:
      opts = args[1]
      break
    default:
      throw new TypeError('Invalid arguments')
  }

  return request(utxo, [address, opts])
}

API.txsBlock = function (hash) {
  return request(txsBlock, hash)
}

API.txsAddress = function () {
  let args = Array.from(arguments)
  let address = args[0]
  let opts

  switch (args.length) {
    case 1:
      opts = {}
      break
    case 2:
      opts = args[1]
      break
    default:
      throw new TypeError('Invalid arguments')
  }

  return request(txsAddress, [address, opts])
}

API.txSend = function (rawtx) {
  return postRequest(txSend, rawtx)
}

API.getInfo = function () {
  return request(getInfo, null)
}

API.getBlockCount = function () {
  return request(getBlockCount, null)
}

API.getDifficulty = function () {
  return request(getDifficulty, null)
}

API.getBestBlockHash = function () {
  return request(getBestBlockHash, null)
}

API.getLastBlockHash = function () {
  return request(getLastBlockHash, null)
}

API.sync = function () {
  return request(sync, null)
}

API.peer = function () {
  return request(peer, null)
}

API.estimateFee = function (nbBlocks = 2) {
  return request(estimateFee, nbBlocks)
}

function block (hash) {
  return 'block/' + hash
}

function rawBlock (hash) {
  return 'rawblock/' + hash
}

function blockIndex (height) {
  return 'block-index/' + height
}

function tx (txid) {
  return 'tx/' + txid
}

function rawTx (txid) {
  return 'rawtx/' + txid
}

function addrValidate (addr) {
  return 'addr-validate/' + addr
}

function addr () {
  let args = Array.from(arguments)
  let address = args[0]
  let opts = args[1]

  if (opts.hasOwnProperty('noTxList') === false) {
    opts.noTxList = false
  }

  if (opts.hasOwnProperty('noCache') === false) {
    opts.noCache = false
  }

  return 'addr/' + address + '?noTxList=' + (opts.noTxList === true ? '1' : '0') + '&noCache=' + (opts.noCache === true ? '1' : '0')
}

function balance (addr) {
  return 'addr/' + addr + '/balance'
}

function totalReceived (addr) {
  return 'addr/' + addr + '/totalReceived'
}

function totalSent (addr) {
  return 'addr/' + addr + '/totalSent'
}

function unconfirmedBalance (addr) {
  return 'addr/' + addr + '/unconfirmedBalance'
}

function utxo () {
  let args = Array.from(arguments)
  let address = args[0]
  let opts = args[1]

  if (opts.hasOwnProperty('noCache') === false) {
    opts.noCache = false
  }

  if (Array.isArray(address) === true) {
    address = address.join(',')

    return 'addrs/' + address + '/utxo'
  }

  return 'addr/' + address + '/utxo?noCache=' + (opts.noCache === true ? '1' : '0')
}

function txsBlock (hash) {
  return 'txs/?block=' + hash
}

function txsAddress () {
  let args = Array.from(arguments)
  let address = args[0]
  let opts = args[1]

  if (opts.hasOwnProperty('from') === false) {
    opts.from = ''
  }

  if (opts.hasOwnProperty('to') === false) {
    opts.to = ''
  }

  if (Array.isArray(address)) {
    address = address.join(',')

    return 'addrs/' + address + '/txs?from=' + opts.from + '&to=' + opts.to
  }

  return 'txs/?address=' + address
}

function txSend (rawtx) {
  return {url: baseApiUrl + 'tx/send', form: {rawtx: rawtx}}
}

function getInfo () {
  return 'status?q=getInfo'
}

function getBlockCount () {
  return 'status?q=getBlockCount'
}

function getDifficulty () {
  return 'status?q=getDifficulty'
}

function getBestBlockHash () {
  return 'status?q=getBestBlockHash'
}

function getLastBlockHash () {
  return 'status?q=getLastBlockHash'
}

function sync () {
  return 'sync'
}

function peer () {
  return 'peer'
}

function estimateFee (nbBlocks = 2) {
  return 'utils/estimatefee?nbBlocks=' + nbBlocks
}

function request (api, args) {
  if (Array.isArray(args) === false) {
    args = [args]
  }

  return rp(API.baseApiUrl + api.apply(this, args))
}

function postRequest (api, args, callback) {
  if (Array.isArray(args) === false) {
    args = [args]
  }

  let obj = api.apply(this, args)
  return rp.post(obj)
}

}
