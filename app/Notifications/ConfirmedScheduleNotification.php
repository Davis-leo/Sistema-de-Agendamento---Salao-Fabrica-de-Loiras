<?php

namespace App\Notifications;

use App\Entities\Schedule;
use CodeIgniter\Email\Email;
use Config\Services;

class ConfirmedScheduleNotification
{
    protected Email $service;
    protected string $email;
    protected Schedule $schedule;

    public function __construct(string $email, Schedule $schedule)
    {
        $this->service = Services::email();
        $this->email = $email;
        $this->schedule = $schedule;
    }

    public function send(): bool
    {
        $this->service->setTo($this->email);
        $this->service->setSubject('Obrigado por escolher nosso salão');
        $this->service->setMessage(view('Front/Email/schedule_confirmed', [
            'chosen_date' => $this->schedule->chosen_date,
            'unit' => $this->schedule->unit,
            'service' => $this->schedule->service,
            'professional' => $this->schedule->service_professionals ?: $this->schedule->professional,
            'address' => $this->schedule->address,
        ]));
        $this->service->setMailType('html');

        if (!$this->service->send()) {
            log_message('error', $this->service->printDebugger());
            return false;
        }

        return true;
    }
}
