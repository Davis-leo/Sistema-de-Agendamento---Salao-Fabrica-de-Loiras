<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileCompletionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!auth()->loggedIn() || auth()->user()->inGroup('superadmin') || !empty(auth()->user()->phone)) {
            return null;
        }

        return redirect()->route('profile')->with('info', 'Informe seu telefone para continuar usando os agendamentos.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
