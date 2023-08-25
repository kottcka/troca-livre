<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['directory_path', 'item_id'];



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

    public function deleteImage($imageId)
    {
        $image = $this->find($imageId);
        if ($image) {
            // Remova o arquivo físico
            if (file_exists(ROOTPATH . 'public/assets/images/items/' . $image->directory_path)) {
                unlink(ROOTPATH . 'public/assets/images/items/' . $image->directory_path);
            }
        }
        return $this->where('id', $imageId)->delete();
    }
    
    

}
