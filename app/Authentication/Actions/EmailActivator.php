<?php

namespace App\Authentication\Actions;

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Actions\ActionInterface;
use CodeIgniter\Shield\Authentication\Actions\EmailActivator as ShieldEmailActivator;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Traits\Viewable;

class EmailActivator extends ShieldEmailActivator implements ActionInterface
{
    use Viewable;

    private const CODE_LIFETIME_MINUTES = 15;
    private const IDENTITY_TYPE = Session::ID_TYPE_EMAIL_ACTIVATE;

    public function show(): string
    {
        $authenticator = auth('session')->getAuthenticator();
        $user = $authenticator->getPendingUser();
        if ($user === null) {
            throw new \RuntimeException('Cannot get the pending login User.');
        }

        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityByType($user, self::IDENTITY_TYPE);
        if ($identity === null || ($identity->expires instanceof Time && $identity->expires->isBefore(Time::now()))) {
            $identityModel->deleteIdentitiesByType($user, self::IDENTITY_TYPE);
            $code = $this->createIdentity($user);
        } else {
            $code = (string) $identity->secret;
        }

        helper('email');
        $request = service('request');
        $email = emailer(['mailType' => 'html'])
            ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '')
            ->setTo($user->email)
            ->setSubject(lang('Auth.emailActivateSubject'))
            ->setMessage($this->view(
                setting('Auth.views')['action_email_activate_email'],
                [
                    'code' => $code,
                    'user' => $user,
                    'ipAddress' => $request->getIPAddress(),
                    'userAgent' => (string) $request->getUserAgent(),
                    'date' => Time::now()->toDateTimeString(),
                ],
                ['debug' => false],
            ));

        if ($email->send(false) === false) {
            throw new \RuntimeException('Cannot send email for user: ' . $user->email . "\n" . $email->printDebugger(['headers']));
        }

        $email->clear();
        return $this->view(setting('Auth.views')['action_email_activate_show'], ['user' => $user]);
    }

    public function createIdentity(User $user): string
    {
        $identityModel = model(UserIdentityModel::class);
        $generator = static fn (): string => random_string('nozero', 6);

        return $identityModel->createCodeIdentity(
            $user,
            [
                'type' => self::IDENTITY_TYPE,
                'name' => 'register',
                'expires' => Time::now()->addMinutes(self::CODE_LIFETIME_MINUTES),
                'extra' => lang('Auth.needVerification'),
            ],
            $generator,
        );
    }
}
