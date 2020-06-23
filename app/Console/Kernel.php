<?php

namespace App\Console;

use App\Console\Commands\Document;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // api document
        'doc' => Document::class,

        // swoole
        'swoole:client' => Commands\Swoole\Client::class,
        'swoole:server' => Commands\Swoole\Server::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
