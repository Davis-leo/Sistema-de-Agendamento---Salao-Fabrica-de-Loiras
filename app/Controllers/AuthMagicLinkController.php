<?php

namespace App\Controllers;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Controllers\MagicLinkController as ShieldMagicLinkController;
use CodeIgniter\Shield\Models\UserIdentityModel;

class AuthMagicLinkController extends ShieldMagicLinkController
{
    public function verify(): RedirectResponse
    {
        if (!setting('Auth.allowMagicLinkLogins')) {
            return redirect()->route('login')->with('error', lang('Auth.magicLinkDisabled'));
        }

        if ($this->request->getUserAgent()->isRobot()) {
            throw PageNotFoundException::forPageNotFound();
        }

        $authenticator = auth('session')->getAuthenticator();

        if ($authenticator->loggedIn()) {
            return redirect()->to(config('Auth')->loginRedirect())
                ->with('message', 'Você já está conectado. O link mágico não alterou sua conta atual.');
        }

        if ($authenticator->isPending()) {
            $authenticator->logout();
            $authenticator = auth('session')->getAuthenticator();
        }

        $token = $this->request->getGet('token');
        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityBySecret(Session::ID_TYPE_MAGIC_LINK, $token);
        $identifier = $token ?? '';

        if ($identity === null) {
            $this->recordLoginAttempt($identifier, false);
            Events::trigger('failedLogin', ['magicLinkToken' => $token]);
            return redirect()->route('magic-link')->with('error', lang('Auth.magicTokenNotFound'));
        }

        $identityModel->delete($identity->id);

        if ($identity->expires === null || Time::now()->isAfter($identity->expires)) {
            $this->recordLoginAttempt($identifier, false);
            Events::trigger('failedLogin', ['magicLinkToken' => $token]);
            return redirect()->route('magic-link')->with('error', lang('Auth.magicLinkExpired'));
        }

        if ($authenticator->hasAction($identity->user_id)) {
            return redirect()->route('auth-action-show')->with('error', lang('Auth.needActivate'));
        }

        $user = $this->provider->findById($identity->user_id);
        if ($user instanceof \CodeIgniter\Shield\Entities\User && $authenticator->startUpAction('login', $user) && $authenticator->hasAction($user->id)) {
            $this->recordLoginAttempt($identifier, true, $user->id);
            $authenticator->setPendingLoginMethod(Session::ID_TYPE_MAGIC_LINK);
            return redirect()->route('auth-action-show');
        }

        $authenticator->setPendingLoginMethod(Session::ID_TYPE_MAGIC_LINK);
        $authenticator->loginById($identity->user_id);
        $user = $authenticator->getUser();
        $this->recordLoginAttempt($identifier, true, $user->id);

        return redirect()->to(config('Auth')->loginRedirect());
    }
}
