<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpParser\Node\Expr\FuncCall;

class User extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['id', 'email', 'password', 'full_name', 'cpf', 'cep', 'phone', 'state', 'city', 'address'];

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
    protected $beforeInsert   = ['encryptPassword','beforeData'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['encryptPassword','beforeData'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function encryptPassword($args)
    {
        if (isset($args['data']['password']) && $args['data']['password'] !== '') {
            $args['data']['password'] = password_hash($args['data']['password'], PASSWORD_DEFAULT);
        }
        
        return $args;
    }    

    protected function beforeData(array $data)
    {
        if (isset($data['data']['cpf'])) {
            $data['data']['cpf'] = preg_replace('/\D/', '', $data['data']['cpf']);
        }
        
        if (isset($data['data']['cep'])) {
            $data['data']['cep'] = preg_replace('/\D/', '', $data['data']['cep']);
        }
        
        if (isset($data['data']['phone'])) {
            $data['data']['phone'] = preg_replace('/\D/', '', $data['data']['phone']);
        }
    
        return $data;
    }
    
}
