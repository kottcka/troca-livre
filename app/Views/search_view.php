<!DOCTYPE html>
<html lang="pt-BR">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Busca</title>
      <link rel="stylesheet" href="<?php echo base_url('assets/css/homepagelogado_style.css'); ?>">
   </head>
   <body>
      <div class="container">
         <?= view('common/sidebar_view') ?>
         <div class="main-content">
            <?= view('common/topbar_view') ?>
         </div>
      </div>
      <div class="section">
          <h2>Resultados da Pesquisa</h2>
          <div class="products-container">
              <?php if (isset($items) && !empty($items)): ?>
                  <?php foreach($items as $produto): ?>
                      <div class="product" data-id="<?= $produto->id; ?>" style="cursor: pointer;">
                      <div class="product-tag"><?= strtoupper($produto->tipo); ?></div>
                          <img src="<?= base_url('assets/images/items/' . $produto->image_directory_path); ?>" alt="Produto <?= $produto->nome; ?>">
                          <span><?= $produto->nome; ?></span>
                          <p><?= $produto->descricao; ?></p>
                      </div>
                  <?php endforeach; ?>
              <?php else: ?>
                  <p>Não foram encontrados resultados para sua pesquisa.</p>
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
