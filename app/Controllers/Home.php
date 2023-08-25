<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Home extends BaseController
{
    protected $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }
    
    public function index()
    {
        // Buscando até 3 itens aleatórios para troca
        $data['trocas'] = $this->itemModel
            ->join('images', 'items.id = images.item_id', 'left')
            ->select('items.*, images.directory_path as image_directory_path')
            ->where('tipo', 'troca')
            ->orderBy('RAND()')
            ->groupBy('items.id')
            ->findAll(3); // Limite de 3 itens

        // Buscando até 3 itens aleatórios para doação
        $data['doacoes'] = $this->itemModel
            ->join('images', 'items.id = images.item_id', 'left')
            ->select('items.*, images.directory_path as image_directory_path')
            ->where('tipo', 'doacao')
            ->orderBy('RAND()')
            ->groupBy('items.id')
            ->findAll(3); // Limite de 3 itens
       
        return view('home_view', $data);
    }
}
