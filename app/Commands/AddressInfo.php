<?php

namespace App\Commands;

use Blockchain\Blockchain;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use SoapBox\Formatter\Formatter;

class AddressInfo extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'addr:info { id : The blockchain address id } { --d|download : Boolean to download output to CSV }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get blockchain address information.';

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
        $formatter = Formatter::make($address, Formatter::ARR);

        if ($this->option('download')) {
            file_put_contents($id . "_info.csv", $formatter->toCsv());
        } else {
            var_dump($formatter->toJson());
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
