<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Baojia::class,
        Commands\MinuteBaojiaMap::class,
        Commands\Vouchers::class,
        Commands\HourBaojiaMap::class,
        Commands\XzjCommand::class,
        Commands\OrderSincerity::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
          $schedule->command('baojia:map') //对报价的数据处理
                   ->everyMinute();

         $schedule->command('DJQ:send')
                   ->everyMinute();

         $schedule->command('baojia:hour_map')
                ->hourly()
                ->between('9:00', '17:10');

         $schedule->command('xzj:timeout')
                  ->everyTenMinutes()
                  ->between('17:00', '17:35');

         $schedule->command('order:sincerity')
                  ->everyMinute()
                  ->everyMinute('9:00', '17:30');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
