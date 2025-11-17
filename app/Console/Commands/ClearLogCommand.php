<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class ClearLogCommand extends Command
{
    protected $signature = 'log:clear';
    protected $description = 'Clear Laravel log files';

    public function handle()
    {
        $files = glob(storage_path('logs/*.log'));

        foreach ($files as $file) {
            file_put_contents($file, ''); // kosongkan file
        }

        $this->info('Laravel logs cleared!');
    }
}
