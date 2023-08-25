<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AvaliacaoModel;
use App\Models\ItemModel;
use App\Models\CategoriaModel;
use App\Models\ImageModel;
use App\Models\PerguntasRespostasModel;
use App\Models\User;

class ItemController extends BaseController
{
    protected $itemModel;
    protected $categoriaModel;
    protected $imageModel;
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->categoriaModel = new CategoriaModel();
        $this->imageModel = new ImageModel();
        $this->userModel = new User();  // Adicionando userModel
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['items'] = $this->itemModel->select('items.*, images.directory_path as image_directory_path')
                                     ->join('images', 'items.id = images.item_id', 'left')
                                     ->groupBy('items.id')
                                     ->findAll();
        return view('items/index', $data);
    }

    public function search()
    {
        $search = $this->request->getGet('search'); // Obter a entrada de busca do usuário
        $query = $this->itemModel->select('items.*, images.directory_path as image_directory_path')
                                 ->join('images', 'items.id = images.item_id', 'left')
                                 ->where('status', 'disponivel'); // Adicionada cláusula where para status "disponivel"
    
        // Se o usuário inseriu algo na barra de pesquisa, filtrar os resultados
        if ($search) {
            $query->like('nome', $search)
                  ->orLike('descricao', $search)
                  ->orLike('categoria_id', $search)
                  ->orLike('estado', $search)
                  ->orLike('tipo', $search);
        }
    
        $data['items'] = $query->groupBy('items.id')->findAll(); // Obter os itens filtrados
        return view('search_view', $data);
    }
    
    public function listTrocas()
    {
        // Atualizando a consulta
        $data['items'] = $this->itemModel
                            ->join('images', 'items.id = images.item_id', 'left')
                            ->select('items.*, images.directory_path as image_directory_path')
                            ->where('tipo', 'Troca')
                            ->where('status', 'disponivel') // Adicionada cláusula where para status "disponivel"
                            ->groupBy('items.id')  // Adicionado para agrupar por ID do item
                            ->findAll();
        return view('lista_trocas_view', $data);
    }
    
    public function listDoacoes()
    {
        // Atualizando a consulta para usar 'Doacao' com letras maiúsculas
        $data['items'] = $this->itemModel
                            ->join('images', 'items.id = images.item_id', 'left')
                            ->select('items.*, images.directory_path as image_directory_path')
                            ->where('tipo', 'Doacao') // Use 'Doacao' com D maiúsculo
                            ->where('status', 'disponivel') // Adicionada cláusula where para status "disponivel"
                            ->groupBy('items.id')
                            ->findAll();
        return view('lista_doacoes_view', $data);
    }
    
    public function listAllItems()
    {
        if (!session()->has('user')) {
            session()->setFlashdata('error', 'Você deve estar logado para visualizar seus itens.');
            return redirect()->to('/login');  
        }
    
        $user_id = session()->get('user')->id;
        $items = $this->itemModel->where('user_id', $user_id)
                                 ->where('status', 'disponivel') // Adicionada cláusula where para status "disponivel"
                                 ->findAll();
    
        $data['items'] = array_map(function($item) {
            $images = $this->imageModel->where('item_id', $item->id)->findAll();
            $item->imagens = $images;
            return $item;
        }, $items);
    
        return view('visualizar_item_view', $data);
    }
    

    public function create()
    {
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('criar_item_view', $data);
    }

    public function store()
    {
        if (!session()->has('user')) {
            session()->setFlashdata('error', 'Você deve estar logado para adicionar um item.');
            return redirect()->to('/login');  
        }
    
        $data = [
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'estado' => $this->request->getPost('estado'),
            'tipo' => $this->request->getPost('tipo'),
            'user_id' => session()->has('user') ? session()->get('user')->id : null
        ];
    
        // Inserir o item primeiro
        $itemId = $this->itemModel->insert($data, true);
    
        if (!$itemId) {
            session()->setFlashdata('error', 'Houve um erro ao adicionar o item.');
            return redirect()->back()->withInput();
        }
    
        // Depois, lidar com as imagens
        $imagesFiles = $this->request->getFiles()['imagens'];
    
        foreach ($imagesFiles as $imageFile) {
            if ($imageFile->isValid() && !$imageFile->hasMoved()) {
                if ($imageFile->getSize() <= 10 * 1024 * 1024) {
                    $allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (in_array($imageFile->getMimeType(), $allowedImageTypes)) {
                        $dimensions = getimagesize($imageFile->getTempName());
                        if ($dimensions[0] >= 500 || $dimensions[1] >= 500) {
                            $newName = $imageFile->getRandomName();
                            $imageFile->move(ROOTPATH . 'public/assets/images/items', $newName);
                            $imageData = ['directory_path' => $newName, 'item_id' => $itemId];
                            $this->imageModel->save($imageData);
                        } else {
                            session()->setFlashdata('error', 'A imagem precisa ter no mínimo 500px em um de seus lados.');
                            return redirect()->back()->withInput();
                        }
                    } else {
                        session()->setFlashdata('error', 'O formato da imagem não é suportado. Use JPG, JPEG, PNG ou WEBP.');
                        return redirect()->back()->withInput();
                    }
                } else {
                    session()->setFlashdata('error', 'O tamanho da imagem é muito grande. Deve ser menor que 10MB.');
                    return redirect()->back()->withInput();
                }
            }
        }
    
        session()->setFlashdata('success', 'Item adicionado com sucesso!');
        return redirect()->to('/inicio');
    }
    

public function edit($id)
{
    $item = $this->itemModel->find($id);

    if (!$item) {
        return redirect()->to('/404');
    }

    $itemImages = $this->imageModel->where('item_id', $id)->findAll();
    $item->imagens = $itemImages;

    $data = [
        'item' => $item,
        'categorias' => $this->categoriaModel->findAll()
    ];

    return view('editar_item_view', $data);
}

public function update($id) {
    $data = [
        'nome' => $this->request->getPost('nome'),
        'descricao' => $this->request->getPost('descricao'),
        'categoria_id' => $this->request->getPost('categoria_id'),
        'estado' => $this->request->getPost('estado'),
        'tipo' => $this->request->getPost('tipo')
    ];

    // Decodificando a string JSON para obter a lista de IDs das imagens a serem removidas
    $removedImagesRaw = $this->request->getPost('removedImages');
    $removedImages = [];
    if (is_string($removedImagesRaw)) {
        $removedImagesDecoded = json_decode($removedImagesRaw, true);
        if (is_array($removedImagesDecoded)) {
            $removedImages = $removedImagesDecoded;
        }
    }

    // Obtenha a contagem total de imagens associadas ao item
    $existingImagesCount = $this->imageModel->where('item_id', $id)->countAllResults();

    // Processando o upload das novas imagens
    $imagens = $this->request->getFiles()['imagens'];

    // Se o usuário está tentando remover todas as imagens e não enviou nenhuma nova imagem
    if (count($removedImages) == $existingImagesCount && empty($imagens)) {
        session()->setFlashdata('error', 'O item deve ter pelo menos uma imagem.');
        return redirect()->back()->withInput();
    }

    if ($imagens) {
        foreach ($imagens as $imagem) {
            if ($imagem->isValid() && !$imagem->hasMoved()) {
                if ($imagem->getSize() > 2048000) { // 2MB
                    session()->setFlashdata('error', 'Uma ou mais imagens excedem o tamanho máximo de 2MB.');
                    return redirect()->back()->withInput();
                }

                $extension = $imagem->getClientExtension();
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                    session()->setFlashdata('error', 'Formato de imagem inválido. Apenas jpg, jpeg, png e webp são permitidos.');
                    return redirect()->back()->withInput();
                }

                $imageName = $imagem->getRandomName();
                $imagem->move(ROOTPATH . 'public/assets/images/items', $imageName);

                $dataImagem = [
                    'directory_path' => $imageName,
                    'item_id' => $id
                ];
                $this->imageModel->save($dataImagem);
            }
        }
    }

    // Deletando as imagens marcadas para remoção
    foreach ($removedImages as $imageId) {
        $this->imageModel->deleteImage($imageId);
    }

    // Atualizando o item
    if ($this->itemModel->update($id, $data)) {
        session()->setFlashdata('success', 'Item atualizado com sucesso!');
        return redirect()->to('/inicio');
    } else {
        session()->setFlashdata('error', 'Houve um erro ao atualizar o item.');
        return redirect()->back()->withInput();
    }
}

    

    public function delete()
    {
        $id = $this->request->getPost('item_id');
    
        if ($this->itemModel->delete($id)) {
            session()->setFlashdata('success', 'Item excluído com sucesso!');
        } else {
            session()->setFlashdata('error', 'Houve um erro ao excluir o item.');
        }
        return redirect()->to('/visualizar-itens');
    }


    public function confirmarDados($itemId) {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/login');
        }
    
        $item = $this->itemModel->find($itemId);
        if (!$item) {
            return redirect()->to('/404');
        }
    
        return view('confirmar_dados_view', ['user' => $user, 'itemId' => $itemId]);
    }
    
    public function show($itemId)
    {
        $item = $this->itemModel->find($itemId);
    
        if (!$item) {
            return redirect()->to('/404');
        }
    
        // Adicione essa verificação de status
        if ($item->status == 'trocado') {
            $item->statusMessage = 'Este item já foi trocado';
        } elseif ($item->status == 'doado') {
            $item->statusMessage = 'Este item já foi doado';
        }
        
        $images = $this->imageModel->where('item_id', $itemId)->orderBy('id', 'ASC')->findAll();

        // Definir a imagem principal como a primeira imagem (baseado no menor ID de imagem)
        if (isset($images[0])) {
            $item->directory_path = $images[0]->directory_path;

            // Remover a primeira imagem da lista, pois ela já é a imagem principal
            array_shift($images);
        }

        // Todas as outras imagens serão tratadas como subimagens
        $item->sub_images = $images;

        // Buscar o nome da categoria usando categoria_id
        $categoria = $this->categoriaModel->find($item->categoria_id);
        $item->categoria = $categoria ? $categoria->nome : "Desconhecida";

        // Obter a média de avaliação do usuário
        $mediaAvaliacao = $this->getMediaAvaliacaoUsuario($item->user_id);

        // Passar a média de avaliação para a view
        $perguntasRespostasModel = new PerguntasRespostasModel();
        $perguntas = $perguntasRespostasModel->where('item_id', $itemId)->where('tipo', 'pergunta')->orderBy('data_hora', 'DESC')->findAll();
        $respostas = $perguntasRespostasModel->where('item_id', $itemId)->where('tipo', 'resposta')->findAll();

        $canSolicitar = $this->validarSolicitacao($item->user_id);

        // Buscar dados do usuário
        $user = $this->userModel->find($item->user_id);
        $item->user = $user;

        $data = [
            'item' => $item,
            'mediaAvaliacao' => $mediaAvaliacao,
            'perguntas' => $perguntas,
            'respostas' => $respostas,
            'canSolicitar' => $canSolicitar
        ];

        return view('item_details_view', $data);
    }

    // Função auxiliar para validar a solicitação
    protected function validarSolicitacao($itemUserId)
    {
        if (!session()->has('user')) {
            return false;
        }
        $user_id = session()->get('user')->id;
        return $itemUserId !== $user_id;
    }
    
    
    public function getMediaAvaliacaoUsuario($userId) {
        $avaliacaoModel = new AvaliacaoModel();
        
        $avaliacoes = $avaliacaoModel->where('avaliado_id', $userId)->findAll();
        
        $totalAvaliacoes = count($avaliacoes);
        $somaAvaliacoes = 0;
        
        foreach ($avaliacoes as $avaliacao) {
            $somaAvaliacoes += $avaliacao['avaliacao'];
        }
        
        return $totalAvaliacoes > 0 ? round($somaAvaliacoes / $totalAvaliacoes, 1) : 0;
    }
    
    public function postPerguntaResposta()
{
    $perguntasRespostasModel = new \App\Models\PerguntasRespostasModel();
    
    // Verificar se o usuário está logado
    if (!session()->has('user')) {
        session()->setFlashdata('error', 'Você deve estar logado para postar uma pergunta.');
        return redirect()->to('/login');
    }

    // Obter os dados do formulário
    $item_id = $this->request->getPost('item_id');
    $question_text = $this->request->getPost('texto');
    $response_text = $this->request->getPost('resposta');
    $perguntaId = $this->request->getPost('pergunta_id');

    // Carregar a biblioteca de validação
    $validation = \Config\Services::validation();

    // Definir as regras de validação
    $validation->setRule('texto', 'Pergunta', 'required');

    // Verificar a validação
    if (!$validation->withRequest($this->request)->run() && !$response_text) {
        // Se a validação falhar, retornar uma mensagem de erro
        return redirect()->back()->with('error', 'Você deve fornecer uma pergunta ou resposta.');
    }

    // Verificar se o usuário é o proprietário do item
    $itemModel = new \App\Models\ItemModel();
    $item = $itemModel->find($item_id);
    if ($item && $item->user_id === session()->get('user')->id) {
        // O usuário é o proprietário do item, então ele pode responder
        if ($question_text) {
            // Não permitir que o usuário faça uma pergunta em seu próprio item
            session()->setFlashdata('error', 'Você não pode fazer uma pergunta em seu próprio item.');
            return redirect()->back();
        }
            if ($response_text) {
                // Verificar se já existe uma resposta para a pergunta
                $existingResponse = $perguntasRespostasModel->where('parent_id', $perguntaId)->first();
                if ($existingResponse) {
                    session()->setFlashdata('error', 'Já existe uma resposta para esta pergunta.');
                    return redirect()->back();
                }
    
                $data = [
                    'item_id' => $item_id,
                    'user_id' => session()->get('user')->id,
                    'texto' => $response_text,
                    'tipo' => 'resposta',
                    'parent_id' => $perguntaId // Associar a resposta à pergunta original
                ];
                $perguntasRespostasModel->insert($data);
            } else {
                session()->setFlashdata('error', 'Você deve fornecer uma resposta.');
                return redirect()->back();
            }
        } else {
            // O usuário não é o proprietário do item, então ele pode perguntar
            if ($question_text) {
                $data = [
                    'item_id' => $item_id,
                    'user_id' => session()->get('user')->id,
                    'texto' => $question_text,
                    'tipo' => 'pergunta'
                ];
                $perguntasRespostasModel->insert($data);
            } else {
                session()->setFlashdata('error', 'Você deve fornecer uma pergunta.');
                return redirect()->back();
            }
        }
    
        session()->setFlashdata('success', 'Pergunta/Resposta enviada com sucesso!');
        return redirect()->back();
    }
    
    
    public function editResposta()
    {
        $perguntasRespostasModel = new \App\Models\PerguntasRespostasModel();
    
        // Verificar se o usuário está logado
        if (!session()->has('user')) {
            session()->setFlashdata('error', 'Você deve estar logado para editar uma resposta.');
            return redirect()->to('/login');
        }
    
        // Obter os dados do formulário
        $respostaId = $this->request->getPost('respostaId');
        $novoTexto = $this->request->getPost('texto');
        $itemId = $this->request->getPost('item_id');
    
        // Verificar se os campos foram preenchidos
        if (!$respostaId || !$novoTexto || !$itemId) {
            session()->setFlashdata('error', 'Dados inválidos.');
            return redirect()->back();
        }
    
        // Verificar se o usuário é o proprietário do item
        $itemModel = new \App\Models\ItemModel();
        $item = $itemModel->find($itemId);
        if (!$item || $item->user_id !== session()->get('user')->id) {
            session()->setFlashdata('error', 'Você não tem permissão para editar essa resposta.');
            return redirect()->back();
        }
    
        // Verificar se a resposta existe e está associada ao item
        $resposta = $perguntasRespostasModel->find($respostaId);
        if (!$resposta || $resposta->item_id !== $itemId) {
            session()->setFlashdata('error', 'Resposta não encontrada.');
            return redirect()->back();
        }
    
        // Atualizar o texto da resposta
        $data = ['texto' => $novoTexto];
        if ($perguntasRespostasModel->update($respostaId, $data)) {
            session()->setFlashdata('success', 'Resposta atualizada com sucesso!');
        } else {
            session()->setFlashdata('error', 'Houve um erro ao atualizar a resposta.');
        }
        return redirect()->back();
    }
    

    
    public function denunciarPergunta()
    {
        // Implementar lógica para denunciar a pergunta
    }
}    