<?php

namespace Config;

use App\Entities\Schedule;
use App\Notifications\CanceledScheduleNotification;
use App\Notifications\ConfirmedScheduleNotification;
use App\Notifications\NewScheduleNotification;
use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        $value = ini_get('zlib.output_compression');

        if (filter_var($value, FILTER_VALIDATE_BOOLEAN) || (int) $value > 0) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

/**
 * Envia o e-mail de notificação de agendamento criado
 */
Events::on('schedule_created', static function (string $email, Schedule $schedule) {
    
    (new NewScheduleNotification(email: $email, schedule: $schedule))-> send();

});

/**
 * Envia o e-mail de notificação de cancelamento de agendamento
 */
Events::on('schedule_canceled', static function (string $email, Schedule $schedule) {
    
    (new CanceledScheduleNotification(email: $email, schedule: $schedule))-> send();

});

/**
 * Envia o e-mail de agradecimento após a confirmação do atendimento.
 */
Events::on('schedule_confirmed', static function (string $email, Schedule $schedule) {
    (new ConfirmedScheduleNotification(email: $email, schedule: $schedule))->send();
});

/**
 * Persiste o telefone informado no cadastro do Shield.
 */
Events::on('register', static function ($user): void {
    $digits = preg_replace('/\D+/', '', (string) service('request')->getPost('phone'));
    if (strlen($digits) === 11) {
        $phone = sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 5), substr($digits, 7));
    } else {
        return;
    }

    model(\App\Models\UserModel::class)->update($user->id, ['phone' => $phone]);
});
