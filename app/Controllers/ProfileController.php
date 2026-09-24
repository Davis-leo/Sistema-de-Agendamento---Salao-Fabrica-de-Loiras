<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class ProfileController extends BaseController
{
    public function index(): string
    {
        return view('Front/Profile/index', [
            'title' => 'Meus Dados',
            'user' => auth()->user(),
        ]);
    }

    public function update(): RedirectResponse
    {
        $this->checkMethod('post');

        $username = trim((string) $this->request->getPost('username'));
        $phone = $this->normalizePhone((string) $this->request->getPost('phone'));
        $errors = [];
        if (!preg_match('/\A[\p{L}\p{N}]+(?:[ .\x{27}-][\p{L}\p{N}]+)*\z/u', $username)) {
            $errors[] = 'O nome deve ter entre 3 e 30 caracteres e conter apenas letras, números, espaços, hífen ou apóstrofo.';
        }
        if ($phone === null) {
            $errors[] = 'Informe um telefone celular válido com DDD, no formato (00) 00000-0000.';
        }
        if ($errors) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $user = auth()->user();
        $user->username = $username;
        $user->phone = $phone;
        $model = model(UserModel::class);
        if (!$user->hasChanged()) {
            return redirect()->route('profile')->with('info', 'Não há dados para atualizar.');
        }
        if (!$model->save($user)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->route('profile')->with('success', 'Seus dados foram atualizados.');
    }

    private function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (strlen($digits) === 11) {
            return sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 5), substr($digits, 7));
        }

        return null;
    }
}
