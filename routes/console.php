<?php

use Illuminate\Console\Scheduling\Schedule;

/*
|--------------------------------------------------------------------------
| Module Schedule
|--------------------------------------------------------------------------
|
| Define the scheduled tasks executed by this module.
| All schedules are automatically registered when the module is loaded
| and become part of the host application's scheduler.
|
*/

return function (Schedule $schedule) {

    // example schedule
    $schedule->command('content:module:test-command')->everyMinute();

};