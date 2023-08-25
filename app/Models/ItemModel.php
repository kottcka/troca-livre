<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'descricao', 'categoria_id', 'estado', 'tipo', 'image_id', 'user_id', 'status'];

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

    public function getAllItems()
    {
        return $this->findAll();
    }

    public function getItemWithImages($itemId)
    {
        return $this->db->table('items')
                        ->select('items.*, images.directory_path')
                        ->join('item_images', 'items.id = item_images.item_id')
                        ->join('images', 'item_images.image_id = images.id')
                        ->where('items.id', $itemId)
                        ->get()
                        ->getResult();
    }

    public function getItensUsuarioLogado($userId, $tipo='Troca')
    {
        return $this->select('items.*, (SELECT images.directory_path FROM images WHERE images.item_id = items.id LIMIT 1) AS directory_path')
                    ->where('user_id', $userId)
                    ->where('status', 'disponivel') // supondo que você esteja buscando apenas itens disponíveis
                    ->where('tipo', $tipo) // adicione esta linha para filtrar por tipo
                    ->findAll();
    }
    

    public function setItemAsTrocado($itemId)
    {
        return $this->update($itemId, ['status' => 'trocado']);
    }

    public function setItemAsDoado($itemId)
    {
        return $this->update($itemId, ['status' => 'doado']);
    }
}
