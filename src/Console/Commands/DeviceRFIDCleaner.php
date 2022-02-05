<?php

namespace WalkerChiu\DeviceRFID\Console\Commands;

use WalkerChiu\Core\Console\Commands\Cleaner;

class DeviceRFIDCleaner extends Cleaner
{
    /**
     * The name and signature of the console command.
     *
     * @var String
     */
    protected $signature = 'command:DeviceRFIDCleaner';

    /**
     * The console command description.
     *
     * @var String
     */
    protected $description = 'Truncate tables';

    /**
     * Execute the console command.
     *
     * @return Mixed
     */
    public function handle()
    {
        parent::clean('device-rfid');
    }
}
