<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\UpdateInterviewStatuses;
use App\Http\Middleware\SetViewTypeCookie;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.user.type' => \App\Http\Middleware\CheckUserType::class,
        ]);
        $middleware->web(SetViewTypeCookie::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withCommands([
        UpdateInterviewStatuses::class,
    ])
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('app:update-interview-statuses')->everyMinute();
    })
    ->create();
