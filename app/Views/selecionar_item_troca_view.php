<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecione um Item</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/selecao_item_style.css'); ?>">
</head>

<body>
    <div class="container">
        <?= view('common/sidebar_view') ?>
        <div class="main-content">
            <?= view('common/topbar_view_nosearch') ?>
        </div>
    </div>
    <div class="section">
        <h2>Selecione um item para a troca</h2>
        <div class="products-container">
            <?php if (isset($items) && !empty($items)): ?>
            <?php foreach($items as $item): ?>
            <div class="product">
                <div class="product-image">
                    <img src="<?php echo base_url('assets/images/items/' . ($item->directory_path ?? 'imagem_default.jpg')); ?>" alt="Produto <?= $item->nome; ?>">
                </div>
                <div class="product-details">
                    <span class="product-title"><?= $item->nome; ?></span>
                </div>
                <div class="product-actions">
                    <form action="<?= base_url('solicitacao/processarTroca/' . $itemIdSolicitado); ?>" method="post">
                    <?php echo csrf_field(); ?>
                        <input type="hidden" name="item_oferecido_id" value="<?= $item->id ?>">
                        <button type="button" class="select-button" onclick="confirmSelection(this.form)">Selecionar</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>Você não tem nenhum item disponível para troca.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="confirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 10px; width: 300px;">
     <p>Tem certeza que deseja usar esse item na troca?</p>
        <button id="confirmButton" class="modal-button" onclick="submitForm()">Sim</button>
        <button id="cancel-delete" class="modal-button cancel" onclick="closeModal()">Cancelar</button>
    </div>
</div>


    <script>
        let formToSubmit;

        function confirmSelection(form) {
            formToSubmit = form;
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        function submitForm() {
            formToSubmit.submit();
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }
    </script>

</body>

</html>
