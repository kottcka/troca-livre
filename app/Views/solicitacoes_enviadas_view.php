<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Início</title>
      <link rel="stylesheet" href="<?php echo base_url('assets/css/solicitacao_enviada_style.css'); ?>">
   </head>
   <body>
   <div class="container">
         <?= view('common/sidebar_view') ?>
      <div class="main-content">
      <?= view('common/topbar_view_nosearch') ?>
         </div>
      </div>
      <div class="section">
         <h2>Solicitações Enviadas</h2>
         <div class="products-container">
            <?php if (isset($solicitacoes) && !empty($solicitacoes)): ?>
                <?php foreach($solicitacoes as $solicitacao): ?>
                    <div class="solicitacao">
                        <span class="solicitacao-info">
                        <img src="<?php echo isset($solicitacao->directory_path) ? base_url('assets/images/items/' . $solicitacao->directory_path) : base_url('assets/images/items/imagem_default.jpg'); ?>" alt="Produto <?= $solicitacao->nome; ?>">
                            <span><?= $solicitacao->nome; ?></span>
                        </span>
                        <button class="status-button <?= strtolower($solicitacao->status) ?>">
                            <?= ucfirst($solicitacao->status) ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Você não enviou nenhuma solicitação no momento.</p>
            <?php endif; ?>
         </div>
      </div>

      <style>
          .solicitacao {
              width: 100%;
              max-width: 800px; /* Defina o tamanho máximo desejado */
              margin-left: auto;
              margin-right: auto;
          }

          .status-button {
              padding: 5px 10px;
              border-radius: 5px;
              border: none;
              cursor: not-allowed; /* Desativar o cursor para indicar que o botão não é clicável */
          }

          .status-button.pendente {
              background-color: #FFD700;
              color: #333;
          }

          .status-button.aceito {
              background-color: #4CAF50;
              color: #fff;
          }

          .status-button.negado {
              background-color: #FF4F4F;
              color: #fff;
          }
      </style>

   </body>
</html>
