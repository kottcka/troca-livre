<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AvaliacaoModel;
use App\Models\SolicitacaoModel;
use App\Models\ItemModel;
use App\Models\User;
use App\Libraries\Mail;
use App\Models\CategoriaModel;

class SolicitacaoController extends BaseController
{
    protected $solicitacaoModel;
    protected $itemModel;

    public function __construct()
    {
        $this->solicitacaoModel = new SolicitacaoModel();
        $this->itemModel = new ItemModel();
    }

    public function confirmarDadosTroca($itemId)
    {
        $userData = session()->get('user');
    
        $data = [
            'user' => $userData,
            'action_url' => base_url('solicitacao/selecionarItemTroca/' . $itemId),
            'itemId' => $itemId
        ];
    
        return view('confirmar_dados_view', $data);
    }
    
    public function confirmarDadosDoacao($itemId)
    {
        $data = [
            'itemId' => $itemId,
            'user' => session()->get('user'),
            'action_url' => base_url('solicitacao/processarDoacao/' . $itemId)  // Altere esta linha
        ];
        
        return view('confirmar_dados_view', $data);
    }
    
    
    public function selecionarItemTroca($itemId)
    {
        $userId = session()->get('user')->id;
        $items = $this->itemModel->getItensUsuarioLogado($userId, 'Troca'); // Passa 'Troca' como o segundo argumento
        
        $data = [
            'items' => $items,
            'itemIdSolicitado' => $itemId
        ];
     
        return view('selecionar_item_troca_view', $data);
    }
    
    
    public function processarTroca($itemIdSolicitado)
{
    $itemOferecidoId = $this->request->getPost('item_oferecido_id');
    $userData = session()->get('user');
    
    // Checando se o item oferecido é válido
    $oferecido = $this->itemModel->find($itemOferecidoId);
    if (!$oferecido) {
        session()->setFlashdata('error', 'Item oferecido não encontrado.');
        return redirect()->back();
    }

    // Verificar se o item oferecido pertence ao usuário logado
    if ($oferecido->user_id != $userData->id) {
        session()->setFlashdata('error', 'O item oferecido não pertence ao usuário logado.');
        return redirect()->back();
    }
    
    // Checando se o item solicitado é válido
    $solicitado = $this->itemModel->find($itemIdSolicitado);
    if (!$solicitado) {
        session()->setFlashdata('error', 'Item solicitado não encontrado.');
        return redirect()->back();
    }

    // Verificar se o itemIdSolicitado pertence a um usuário diferente
    if ($solicitado->user_id == $userData->id) {
        session()->setFlashdata('error', 'O item solicitado pertence ao usuário logado. Não é possível trocar com si mesmo.');
        return redirect()->back();
    }
    
    $data = [
        'item_id' => $itemIdSolicitado,
        'item_oferecido_id' => $itemOferecidoId,
        'solicitante_id' => $userData->id,
        'destinatario_id' => $solicitado->user_id,  // Adicionada linha para definir o destinatário
        'status' => 'pendente',
        'tipo' => 'Troca'
    ];
    
    if ($this->solicitacaoModel->insert($data)) {

        // Notificação por e-mail
        $userModel = new User(); // Suponha que User seja o modelo de usuário
        $destinatario = $userModel->find($solicitado->user_id);
        $solicitante = $userModel->find($userData->id);
        $mail = new Mail();
        $mail->setFrom([
            'name' => 'Lesley Vinicius',
            'email' => 'lesleyvamacedo@academico.unirv.edu.br'
        ]);
        $mail->setTo((string)$destinatario->email); // Substitua pelo e-mail do destinatário
        $mail->setSubject('Nova Solicitação de Troca');
        
        $categoriaModel = new \App\Models\CategoriaModel();
        $oferecido->categoria = $categoriaModel->find($oferecido->categoria_id)->nome;

        $mail->setTemplate('emails/template_de_email_troca', [
            'solicitante' => $solicitante,
            'destinatario' => $destinatario,
            'item' => $solicitado,
            'oferecido' => $oferecido
        ]);
        
        $mail->send();

        session()->setFlashdata('success', 'Solicitação de troca enviada com sucesso!');
        return redirect()->to(base_url('solicitacoes/enviadas'));
    } else {
        session()->setFlashdata('error', 'Erro ao enviar solicitação de troca.');
        return redirect()->back();
    }
}

    
    

    public function solicitarTroca($itemId)
    {
        return redirect()->to(base_url('solicitacao/confirmarDadosTroca/' . $itemId));
    }

    public function solicitarDoacao($itemId)
    {
        return redirect()->to(base_url('solicitacao/confirmarDadosDoacao/' . $itemId));
    }

    public function solicitacoesEnviadas()
    {
        $userId = session()->get('user')->id;
        $data['solicitacoes'] = $this->solicitacaoModel->getSolicitacoesEnviadas($userId);
        return view('solicitacoes_enviadas_view', $data);
    }

    public function solicitacoesRecebidas()
    {
        $userId = session()->get('user')->id;
        $data['solicitacoes'] = $this->solicitacaoModel->getSolicitacoesRecebidas($userId);
        return view('solicitacoes_recebidas_view', $data);
    }

    public function aceitarSolicitacao($solicitacaoId)
    {
        // Verifique se a solicitação existe no modelo de SolicitacaoModel
        $solicitacao = $this->solicitacaoModel->find($solicitacaoId);
    
        if (!$solicitacao) {
            session()->setFlashdata('error', 'Solicitação não encontrada.');
            return redirect()->back();
        }
    
        // Atualize o status da solicitação para 'aceito' no modelo de SolicitacaoModel
        $this->solicitacaoModel->update($solicitacaoId, ['status' => 'aceito']);
    
        // Verifique o tipo de solicitação
        $tipo = $solicitacao->tipo;
    
        // Atualize o status do item no banco de dados
        if ($tipo == 'Troca') {
            $this->itemModel->update($solicitacao->item_id, ['status' => 'trocado']);
            $this->itemModel->update($solicitacao->item_oferecido_id, ['status' => 'trocado']);
        } elseif ($tipo == 'Doacao') {
            $this->itemModel->update($solicitacao->item_id, ['status' => 'doado']);
        }
    
        // Notificação por e-mail
        $mail = new Mail();
        $mail->setFrom([
            'name' => 'Lesley Vinicius',
            'email' => 'lesleyvamacedo@academico.unirv.edu.br'
        ]);
    
        $userModel = new User(); // Suponha que User seja o modelo de usuário
    
        // Obter detalhes do solicitante
        $solicitante = $userModel->find($solicitacao->solicitante_id);
    
        // Obter detalhes do destinatário
        $destinatario = $userModel->find($solicitacao->destinatario_id);
    
        $mail->setTo((string)$solicitante->email); // Enviar para o solicitante
    
        // Carregue o modelo de item para obter os detalhes do item solicitado
        $itemModel = new ItemModel(); // Suponha que ItemModel seja o modelo de item
        $itemSolicitado = $itemModel->find($solicitacao->item_id);
    
        // Carregue o modelo de item novamente para obter os detalhes do item oferecido
        $itemOferecido = $itemModel->find($solicitacao->item_oferecido_id);
    
        // Escolha o template com base no tipo de solicitação
        $template = ($tipo == 'Troca') ? 'emails/template_de_email_troca_aceita' : 'emails/template_de_email_doacao_aceita';
    
        // Carregue o modelo de categoria para obter o nome da categoria do item solicitado
        $categoriaModel = new CategoriaModel(); // Suponha que CategoriaModel seja o modelo de categoria
        $categoriaSolicitado = $categoriaModel->find($itemSolicitado->categoria_id)->nome;
    
        // Carregue o modelo de categoria novamente para obter o nome da categoria do item oferecido
        $categoriaOferecido = $categoriaModel->find($itemOferecido->categoria_id)->nome;
    
        $mail->setSubject('Solicitação de ' . $tipo . ' Aceita');
        $mail->setTemplate($template, [
            'solicitante' => $solicitante,
            'destinatario' => $destinatario,
            'solicitacao' => $solicitacao,
            'itemSolicitado' => $itemSolicitado,
            'categoriaSolicitado' => $categoriaSolicitado,
            'itemOferecido' => $itemOferecido,
            'categoriaOferecido' => $categoriaOferecido
        ]);
    
        $mail->send();
    
        session()->setFlashdata('success', 'Solicitação aceita com sucesso!');
        return redirect()->to(base_url('solicitacoes/recebidas'));
    }
    
    

    

    public function negarSolicitacao($solicitacaoId)
{
    // Encontra a solicitação usando o modelo de Solicitacao
    $solicitacao = $this->solicitacaoModel->find($solicitacaoId);
    if (!$solicitacao) {
        session()->setFlashdata('error', 'Solicitação não encontrada.');
        return redirect()->back();
    }

    // Atualiza o status da solicitação para 'negado'
    $this->solicitacaoModel->update($solicitacaoId, ['status' => 'negado']);

    // Carrega o modelo de item para obter os detalhes do item solicitado
    $itemModel = new ItemModel();
    $itemSolicitado = $itemModel->find($solicitacao->item_id);

    // Carrega o modelo de usuário para obter os detalhes do solicitante
    $userModel = new User();
    $solicitante = $userModel->find($solicitacao->solicitante_id);

    // Configura e envia o e-mail
    $mail = new Mail();
    $mail->setFrom([
        'name' => 'Lesley Vinicius',
        'email' => 'lesleyvamacedo@academico.unirv.edu.br'
    ]);
    $mail->setTo((string)$solicitante->email);
    $mail->setSubject('Solicitação Negada');
    $mail->setTemplate('emails/template_de_email_negado', [
        'solicitacao' => $solicitacao,
        'itemSolicitado' => $itemSolicitado,
        'solicitante_name' => $solicitante->full_name // Supondo que 'full_name' seja o campo que contém o nome completo do usuário
    ]);

    $mail->send();

    // Configura a mensagem de sucesso e redireciona
    session()->setFlashdata('success', 'Solicitação negada com sucesso!');
    return redirect()->back();
}

    
    
    
    public function trocarItem($solicitacaoId)
{
    $solicitacao = $this->solicitacaoModel->find($solicitacaoId);
    
    if (!$solicitacao || $solicitacao->status != 'aceito' || $solicitacao->tipo != 'Troca') {
        session()->setFlashdata('error', 'Solicitação inválida ou não aceita.');
        return redirect()->back();
    }
    
    $itemSolicitado = $this->itemModel->find($solicitacao->item_id);
    $itemOferecido = $this->itemModel->find($solicitacao->item_oferecido_id);
    
    if (!$itemSolicitado || !$itemOferecido) {
        session()->setFlashdata('error', 'Um ou ambos os itens envolvidos na troca não foram encontrados.');
        return redirect()->back();
    }
    
    $tempUserId = $itemSolicitado->user_id;
    $itemSolicitado->user_id = $itemOferecido->user_id;
    $itemOferecido->user_id = $tempUserId;
    
    $this->itemModel->save($itemSolicitado);
    $this->itemModel->save($itemOferecido);
    
    session()->setFlashdata('success', 'Troca de itens realizada com sucesso!');
    return redirect()->back();
}

public function solicitacoesFinalizadas()
{
    $user_id = session()->get('user')->id;
    $solicitacoes = $this->solicitacaoModel->getSolicitacoesFinalizadas($user_id);
    $data = [
        'solicitacoes' => $solicitacoes
    ];
    return view('solicitacoes_finalizadas_view', $data);
}

public function processaAvaliacao() 
{
    $avaliacaoModel = new AvaliacaoModel();
    $solicitacaoModel = new SolicitacaoModel();

    $avaliado_id = $this->request->getPost('avaliado_id');
    $solicitacao_id = $this->request->getPost('solicitacao_id');
    $avaliador_id = session()->get('user')->id;

    $solicitacao = $solicitacaoModel->find($solicitacao_id);
    if (!$solicitacao) {
        session()->setFlashdata('error', 'Solicitação não encontrada.');
        return redirect()->back();
    }

    $avaliado_id = ($solicitacao->solicitante_id == $avaliador_id) ? $solicitacao->destinatario_id : $solicitacao->solicitante_id;

    if ($solicitacaoModel->hasUserReviewed($solicitacao_id, $avaliador_id, $avaliado_id)) {
        session()->setFlashdata('error', 'Você já avaliou esta solicitação.');
        return redirect()->back();
    }

    $data = [
        'avaliador_id' => $avaliador_id,
        'avaliado_id' => $avaliado_id,
        'solicitacao_id' => $solicitacao_id,
        'avaliacao' => $this->request->getPost('avaliacao')
    ];
    
    $result = $avaliacaoModel->insert($data);
    if (!$result) {
        $error = $avaliacaoModel->errors();
        session()->setFlashdata('error', 'Erro ao inserir avaliação: ' . implode(', ', $error));
        return redirect()->back();
    }

    return redirect()->to('/solicitacoes-finalizadas');
}

public function processarDoacao($itemIdSolicitado)
{
    $userData = session()->get('user');
    
    // Checando se o item solicitado é válido
    $solicitado = $this->itemModel->find($itemIdSolicitado);
    if (!$solicitado) {
        session()->setFlashdata('error', 'Item solicitado não encontrado.');
        return redirect()->back();
    }

    // Verificar se o itemIdSolicitado pertence a um usuário diferente
    if ($solicitado->user_id == $userData->id) {
        session()->setFlashdata('error', 'O item solicitado pertence ao usuário logado. Não é possível doar para si mesmo.');
        return redirect()->back();
    }
    
    // Recuperar a categoria do item usando uma junção com a tabela de categorias
    $solicitado = $this->itemModel
        ->select('items.*, categorias.nome as categoria')
        ->join('categorias', 'items.categoria_id = categorias.id', 'left')
        ->find($itemIdSolicitado);

    // Definir um valor padrão para a categoria, caso não seja encontrada
    $categoriaSolicitado = isset($solicitado->categoria) ? $solicitado->categoria : 'Categoria Desconhecida';

    $data = [
        'item_id' => $itemIdSolicitado,
        'solicitante_id' => $userData->id,
        'destinatario_id' => $solicitado->user_id,
        'status' => 'pendente',
        'tipo' => 'Doacao'
    ];

    if ($this->solicitacaoModel->insert($data)) {
        // Notificação por e-mail
        $userModel = new User(); // Suponha que User seja o modelo de usuário
        $destinatario = $userModel->find($solicitado->user_id);
        $solicitante = $userModel->find($userData->id);
        $mail = new Mail();
        $mail->setFrom([
            'name' => 'Lesley Vinicius',
            'email' => 'lesleyvamacedo@academico.unirv.edu.br'
        ]);
        $mail->setTo((string)$destinatario->email); // Substitua pelo e-mail do destinatário
        $mail->setSubject('Nova Solicitação de Doação');

        $mail->setTemplate('emails/template_de_email_doacao', [
            'solicitante' => $solicitante,
            'destinatario' => $destinatario,
            'itemSolicitado' => $solicitado,
            'categoriaSolicitado' => $categoriaSolicitado,
        ]);

        $mail->send();

        session()->setFlashdata('success', 'Solicitação de doação enviada com sucesso!');
        return redirect()->to(base_url('solicitacoes/enviadas'));
    } else {
        session()->setFlashdata('error', 'Erro ao enviar solicitação de doação.');
        return redirect()->back();
    }
}

public function finalizarItem($itemId, $status)
{
    // Atualize o status do item no banco de dados
    $this->itemModel->update($itemId, ['status' => $status]);
}



}
