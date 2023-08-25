<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use PhpParser\Node\Expr\FuncCall;

class Register extends BaseController
{
    public function index()
    {
                // Verifica se a sessão 'user' não está definida
                if(!session()->has('user')) {
                    // Se não estiver definida, redirecione para a tela de login
                    
                    return view('register_view');;
                }
        
                // Se estiver definida, renderize a view 'homelogado_view'
                return redirect()->route('HomeLogado');
    }

    
private function validaCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}


private function validaCelular($numero) {
    $numero = preg_replace('/[^0-9]/', '', $numero);
    return strlen($numero) == 11 && $numero[2] == 9;
}

private function validaCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    return strlen($cep) == 8;
}

public function store()
    {
        $validated = $this->validate([
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[8]', // Aqui, estou supondo um tamanho mínimo de 8 caracteres para a senha.
            'full_name' => 'required|alpha_space',  // Verifica se o campo contém apenas caracteres alfabéticos e espaços.
            'cpf'      => 'required|exact_length[11]', // Supondo que o CPF não tem pontos ou traços.
            'cep'      => 'required|exact_length[8]',  // Supondo que o CEP não tem traço.
            'phone'    => 'required', // Você pode adicionar mais regras aqui dependendo do formato desejado.
            'state'    => 'required|alpha_space',
            'city'     => 'required|alpha_space',
            'address'  => 'required'
        ],[
            'email' => [
                'required' => 'O campo email é obrigatório',
                'valid_email' => 'Informe um email válido',
                'is_unique' => 'Este email já está registrado'
            ],
            'password' => [
                'required' => 'O campo senha é obrigatório',
                'min_length' => 'A senha deve ter pelo menos 8 caracteres'
            ],
            'full_name' => [
                'required' => 'O campo nome é obrigatório',
                'alpha_space' => 'O nome deve conter apenas letras e espaços'
            ],
            'cpf' => [
                'required' => 'O campo CPF é obrigatório',
                'exact_length' => 'O CPF deve ter exatamente 11 caracteres'
            ],
            'cep' => [
                'required' => 'O campo CEP é obrigatório',
                'exact_length' => 'O CEP deve ter exatamente 8 caracteres'
            ],
            'phone' => [
                'required' => 'O campo telefone é obrigatório'
            ],
            'state' => [
                'required' => 'O campo estado é obrigatório',
                'alpha_space' => 'O estado deve conter apenas letras e espaços'
            ],
            'city' => [
                'required' => 'O campo cidade é obrigatório',
                'alpha_space' => 'A cidade deve conter apenas letras e espaços'
            ],
            'address' => [
                'required' => 'O campo endereço é obrigatório'
            ]
        ]);
        
        if(!$validated){
            return redirect()->route('register')->with('errors', $this->validator->getErrors());
        }

        if (!$this->validaCPF($this->request->getPost('cpf'))) {
            session()->setFlashdata('errors', ['cpf' => 'CPF inválido.']);
            return redirect()->back()->withInput();
        }
        
        if (!$this->validaCelular($this->request->getPost('phone'))) {
            session()->setFlashdata('errors', ['phone' => 'Número de celular inválido.']);
            return redirect()->back()->withInput();
        }
        $user = new User();
        $inserted = $user->insert($this->request->getPost());

        if (!$inserted) {
            return redirect()->route('register')->with('error', 'Ocorreu um erro ao efetuar o cadastro!');
        } else {
            session()->setFlashdata('success', 'Cadastrado com sucesso!');
            return redirect()->to('/login');
        }        
        
    }

    public function edit() {
        // Verifica se a sessão 'user' está definida
        if(!session()->has('user')) {
            // Se não estiver definida, redirecione para a tela de login
            return redirect()->to('/login');
        }
    
        $userModel = new User();
        $userData = $userModel->find(session()->get('user')->id);
    
        return view('edit_user_view', ['userData' => $userData]);
    }

    public function update() {
        // Verifique se o usuário está logado
        if(!session()->has('user')) {
            return redirect()->to('/login');
        }
        
        $userModel = new User();
        $userId = session()->get('user')->id;
    
        $userData = $this->request->getPost();
    
        // Verifique se a senha fornecida é vazia. Se for, remova-a dos dados de atualização.
        if (empty($userData['password'])) {
            unset($userData['password']);
        }
    
        $updated = $userModel->update($userId, $userData);
    
        if (!$updated) {
            return redirect()->route('register.edit')->with('error', 'Ocorreu um erro ao atualizar seus dados!');
        } else {
            session()->setFlashdata('success', 'Dados atualizados com sucesso!');
            return redirect()->to('/perfil');
        }
    }

    public function deleteUser()
    {
        $userId = session()->get('user')->id;  // Obtém o ID do usuário logado
        $userModel = new User();
        
        if ($userModel->delete($userId)) {  // Tenta excluir o usuário do banco de dados
            session()->destroy();  // Destrói a sessão do usuário
            return redirect()->route('Home')->with('success', 'Perfil excluído com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Erro ao tentar excluir o perfil. Tente novamente.');
        }
    }
    
    
    
}
