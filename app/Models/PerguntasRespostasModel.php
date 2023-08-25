<?php

namespace App\Models;

use CodeIgniter\Model;

class PerguntasRespostasModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'perguntas_respostas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true; // Atualizado para usar exclusões suaves
    protected $protectFields    = true;
    protected $allowedFields    = ['item_id', 'user_id', 'parent_id', 'texto', 'data_hora', 'tipo', 'created_at', 'updated_at', 'deleted_at']; // Atualizado para incluir campos de data

    // Dates
    protected $useTimestamps    = true; // Atualizado para usar carimbos de data/hora
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

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
}
