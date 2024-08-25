<?php

namespace App\Console;

use App\Console\Commands\MyAccessTokenPpobCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        MyAccessTokenPpobCommand::class, // Menambahkan perintah Anda ke daftar perintah
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(MyAccessTokenPpobCommand::class)
                 ->everyMinute(); // Jadwalkan perintah untuk dijalankan setiap menit
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {

    }
}

