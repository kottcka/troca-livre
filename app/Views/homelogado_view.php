<!DOCTYPE html>
<html lang="pt-BR">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Início</title>
      <link rel="stylesheet" href="<?php echo base_url('assets/css/homepagelogado_style.css'); ?>">
   </head>
   <body>
      <div class="container">
         <?= view('common/sidebar_view') ?>
         <div class="main-content">
            <?= view('common/topbar_view') ?>
         </div>
      </div>
<!-- Para itens de troca -->
<div class="section">
    <h2> Trocas <a href="/lista-trocas" class="ver-mais">Ver mais</a></h2>
    <div class="products-container">
        <?php if (isset($trocas) && !empty($trocas)): ?>
         <?php foreach($trocas as $produto): ?>
            <div class="product" data-id="<?= $produto->id; ?>" style="cursor: pointer;">
                <img src="<?= base_url('assets/images/items/' . $produto->image_directory_path); ?>" alt="Produto <?= $produto->nome; ?>">
                <span><?= $produto->nome; ?></span>
                <p><?= $produto->descricao; ?></p>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <p>Não há itens de troca disponíveis no momento.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Para itens de doação -->
<div class="section">
    <h2> Doações <a href="/lista-doacoes" class="ver-mais">Ver mais</a></h2>
    <div class="products-container">
        <?php if (isset($doacoes) && !empty($doacoes)): ?>
         <?php foreach($doacoes as $produto): ?>
            <div class="product" data-id="<?= $produto->id; ?>" style="cursor: pointer;">
                <img src="<?= base_url('assets/images/items/' . $produto->image_directory_path); ?>" alt="Produto <?= $produto->nome; ?>">
                <span><?= $produto->nome; ?></span>
                <p><?= $produto->descricao; ?></p>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <p>Não há itens de doação disponíveis no momento.</p>
        <?php endif; ?>
    </div>
</div>
<script>
    document.querySelectorAll('.product').forEach(product => {
        product.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            window.location.href = `<?= base_url('item/'); ?>${itemId}`;
        });
    });
</script>
   </body>
</html>
