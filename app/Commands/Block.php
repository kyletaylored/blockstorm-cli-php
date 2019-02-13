<?php

namespace App\Commands;

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
    protected $description = 'Retrieve the latest block info.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $Blockchain = new \Blockchain\Blockchain();
        $latest = $Blockchain->Explorer->getLatestBlock();
        print $latest;
    }
}
