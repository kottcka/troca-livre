<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class Login extends BaseController
{
    public function index()
    {
        return view('login_view');
    }

    public function store()
    {
        $validated = $this->validate([
            'email' => 'required|valid_email',
            'password' => 'required',
        ], [
            'email' => [
                'required' => 'O email é obrigatório',
                'valid_email' => 'O email tem que ser válido',
            ],
            'password' => [
                'required' => 'A senha é obrigatória',
            ],
        ]);

        if (!$validated) {
            return redirect()->route('login')->with('errors', $this->validator->getErrors());
        }

        $userModel = new User();
        $userFound = $userModel->where('email', $this->request->getPost('email'))->first();

        if (!$userFound || !password_verify($this->request->getPost('password'), $userFound->password)) {
            return redirect()->route('login')->with('error', 'Email ou senha incorretos!');
        }

        unset($userFound->password);
        session()->set('user', $userFound);

        return redirect()->route('HomeLogado');
    }

    public function destroy()
    {
        session()->destroy();
        return redirect()->route('Home');
    }

    // Método para exibir a tela de confirmação dos dados do usuário
    public function confirmUserData()
    {
        $userId = session()->get('user')->id;
        $userModel = new User();
        $user = $userModel->find($userId);
        
        return view('confirmar_dados_view', ['user' => $user, 'action_url' => '/confirmar-dados/atualizar']);
    }

    // Método para atualizar os dados do usuário no banco de dados
    public function updateUser()
    {
        $userId = session()->get('user')->id;
        $data = [
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'cpf' => $this->request->getPost('cpf'),
            'phone' => $this->request->getPost('phone'),
            'state' => $this->request->getPost('state'),
            'city' => $this->request->getPost('city'),
            'address' => $this->request->getPost('address')
        ];

        $userModel = new User();
        if ($userModel->update($userId, $data)) {
            session()->setFlashdata('success', 'Dados atualizados com sucesso!');
        } else {
            session()->setFlashdata('error', 'Houve um erro ao atualizar seus dados.');
        }
        return redirect()->back();
    }

}