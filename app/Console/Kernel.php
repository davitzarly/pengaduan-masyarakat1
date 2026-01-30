<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SetupDatabase::class,
    ];

    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        // Define scheduled tasks here if needed
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        $console = base_path('routes/console.php');
        if (file_exists($console)) {
            require $console;
        }
    }
}
