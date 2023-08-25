<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Início</title>
      <link rel="stylesheet" href="<?php echo base_url('assets/css/visualizar_style.css'); ?>">
   </head>
   <body>
   <div class="container">
         <?= view('common/sidebar_view') ?>
      <div class="main-content">
      <?= view('common/topbar_view_nosearch') ?>
         </div>
      </div>
      <div class="section">
      <h2>Itens Cadastrados</h2>
            <div class="products-container">
                <?php if (isset($items) && !empty($items)): ?>
                    <?php foreach($items as $item): ?>
                        <div class="product">
                            <div class="product-image">
                            <img src="<?php echo isset($item->imagens[0]->directory_path) ? base_url('assets/images/items/' . $item->imagens[0]->directory_path) : 'URL_IMAGEM_PADRÃO'; ?>" alt="Produto <?= $item->nome; ?>">
                            </div>
                            <div class="product-details">
                                <span class="product-title"><?= $item->nome; ?></span>
                                <span class="product-type"><?= $item->tipo === 'troca' ? 'TROCAS' : 'DOAÇÕES'; ?></span>
                            </div>
                            <div class="product-actions">
                                <a href="<?= base_url('items/edit/' . $item->id); ?>" class="edit-button">Editar</a>
                                <form action="<?= base_url('items/delete'); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                    <input type="hidden" name="item_id" value="<?= $item->id ?>">
                                    <button type="button" class="delete-button">Excluir</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Não há itens de doação disponíveis no momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="confirmation-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div style="background-color: white; padding: 20px; border-radius: 10px; width: 300px;">
        <p>Tem certeza de que deseja excluir este item?</p>
        <button id="confirm-delete" class="modal-button">Sim</button>
        <button id="cancel-delete" class="modal-button cancel">Não</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.delete-button');
        var modal = document.getElementById('confirmation-modal');
        var cancelBtn = document.getElementById('cancel-delete');
        var formToSubmit;

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                formToSubmit = button.parentElement;
                modal.style.display = 'flex';
            });
        });

        document.getElementById('confirm-delete').addEventListener('click', function() {
            formToSubmit.submit();
        });

        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    });
</script>
</body>
</html>