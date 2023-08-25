<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitacaoModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table = 'solicitacoes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['item_id', 'item_oferecido_id', 'solicitante_id', 'destinatario_id', 'status', 'tipo'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getSolicitacoesEnviadas($userId)
    {
        return $this->select('solicitacoes.*, items.*, solicitacoes.status, images.directory_path') 
                    ->join('items', 'solicitacoes.item_id = items.id')
                    ->join('images', 'solicitacoes.item_id = images.item_id', 'left') 
                    ->where('solicitante_id', $userId)
                    ->groupBy('solicitacoes.id')
                    ->findAll();
    }
    
    public function getSolicitacoesRecebidas($userId)
    {
        return $this->select('solicitacoes.id as solicitacao_id, solicitacoes.*, items.*, 
                              images.directory_path, 
                              users.full_name as solicitante_name, 
                              offered_items.nome as nome_oferecido,
                              offered_images.directory_path as directory_path_oferecido')
                    ->join('items', 'solicitacoes.item_id = items.id')
                    ->join('items as offered_items', 'solicitacoes.item_oferecido_id = offered_items.id', 'left')
                    ->join('images', 'solicitacoes.item_id = images.item_id', 'left')
                    ->join('images as offered_images', 'solicitacoes.item_oferecido_id = offered_images.item_id', 'left')
                    ->join('users', 'solicitacoes.solicitante_id = users.id')
                    ->whereNotIn('solicitacoes.status', ['aceito', 'negado'])
                    ->where('destinatario_id', $userId)
                    ->groupBy('solicitacoes.id')
                    ->findAll();
    }

    public function aceitarSolicitacao($solicitacaoId)
    {
        // Verifique se a solicitação existe
        $solicitacao = $this->find($solicitacaoId);
        if (!$solicitacao) {
            return false;
        }
    
        // Atualize o status da solicitação para 'aceito'
        $this->update($solicitacaoId, ['status' => 'aceito']);
    
        return true;
    }
    public function getSolicitacoesFinalizadas($userId)
    {
        $solicitacoes = $this->select('solicitacoes.id as solicitacao_id, solicitacoes.solicitante_id, solicitacoes.destinatario_id, solicitacoes.status, items.nome, images.directory_path, avaliacoes.avaliacao, destinatario.email as email, destinatario.phone as phone, solicitante.phone as solicitante_phone, solicitante.email as solicitante_email, offered_items.nome as nome_oferecido, offered_images.directory_path as directory_path_oferecido')
                ->join('items', 'solicitacoes.item_id = items.id')
                ->join('items as offered_items', 'solicitacoes.item_oferecido_id = offered_items.id', 'left')
                ->join('images', 'solicitacoes.item_id = images.item_id', 'left')
                ->join('images as offered_images', 'solicitacoes.item_oferecido_id = offered_images.item_id', 'left')
                ->join('avaliacoes', 'avaliacoes.solicitacao_id = solicitacoes.id AND avaliacoes.avaliador_id = ' . $userId, 'left')
                ->join('users as destinatario', 'solicitacoes.destinatario_id = destinatario.id')
                ->join('users as solicitante', 'solicitacoes.solicitante_id = solicitante.id') // Adicione esta linha para obter os detalhes do solicitante
                ->whereIn('solicitacoes.status', ['aceito', 'negado'])
                ->groupStart() // Adicionando groupStart aqui
                ->where('solicitacoes.destinatario_id', $userId)
                ->orWhere('solicitacoes.solicitante_id', $userId) // Adicionando esta linha para incluir as solicitações em que o usuário é o solicitante
                ->groupEnd() // Adicionando groupEnd aqui
                ->groupBy('solicitacoes.id')
                ->findAll();
    
        foreach ($solicitacoes as $solicitacao) {
            $avaliado_id = ($solicitacao->solicitante_id == $userId) ? $solicitacao->destinatario_id : $solicitacao->solicitante_id;
            $solicitacao->canReview = !$this->hasUserReviewed($solicitacao->solicitacao_id, $userId, $avaliado_id);
        }
    
        return $solicitacoes;
    }
    
    

    
       
    public function hasUserReviewed($solicitacao_id, $avaliador_id, $avaliado_id) {
        $builder = $this->db->table('avaliacoes');
        $builder->where('solicitacao_id', $solicitacao_id);
        $builder->where('avaliador_id', $avaliador_id);
        $builder->where('avaliado_id', $avaliado_id);
        $query = $builder->get();
    
        // Retorna verdadeiro se o usuário já avaliou a solicitação, caso contrário, retorna falso.
        return $query->getRow() != null;
    }
}
