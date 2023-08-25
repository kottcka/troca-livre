<?php

namespace App\Controllers;

use App\Models\ItemModel;

class HomeLogado extends BaseController
{
    protected $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function index()
    {
        // Buscando até 3 itens aleatórios para troca com status "disponivel"
        $data['trocas'] = $this->itemModel
            ->join('images', 'items.id = images.item_id', 'left')
            ->select('items.*, images.directory_path as image_directory_path')
            ->where('tipo', 'troca')
            ->where('status', 'disponivel') // Adicionada cláusula where para status "disponivel"
            ->orderBy('RAND()')
            ->groupBy('items.id')
            ->findAll(3); // Limite de 3 itens
    
        // Buscando até 3 itens aleatórios para doação com status "disponivel"
        $data['doacoes'] = $this->itemModel
            ->join('images', 'items.id = images.item_id', 'left')
            ->select('items.*, images.directory_path as image_directory_path')
            ->where('tipo', 'doacao')
            ->where('status', 'disponivel') // Adicionada cláusula where para status "disponivel"
            ->orderBy('RAND()')
            ->groupBy('items.id')
            ->findAll(3); // Limite de 3 itens
    
        if (!session()->has('user')) {
            return redirect()->route('login');
        }
    
        return view('homelogado_view', $data);
    }
    
}