<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Início</title>
      <link rel="stylesheet" href="<?php echo base_url('assets/css/solicitacao_recebida_style.css'); ?>">
   </head>
   <body>
   <div class="container">
         <?= view('common/sidebar_view') ?>
      <div class="main-content">
      <?= view('common/topbar_view_nosearch') ?>
         </div>
      </div>
      <div class="section">
            <h2>Solicitações Recebidas</h2>
            <div class="products-container">

                <?php if (isset($solicitacoes) && !empty($solicitacoes)): ?>
                    <?php foreach($solicitacoes as $solicitacao): ?>
                        <div class="solicitacao">
                            <div class="solicitacao-info">
                                <div class="product-image">
                                    <!-- Item Solicitado Image -->
                                    <img src="<?php echo base_url('assets/images/items/' . ($solicitacao->directory_path ?? 'imagem_default.jpg')); ?>" alt="Produto <?= $solicitacao->nome; ?>">
                                    <span>Eu</span>
                                </div>
                                <!-- SVG Between Images -->
                                <div class="swap-icon">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
fill="#000000" stroke="none">
<path d="M3994 4442 c-121 -43 -193 -147 -194 -277 0 -108 23 -147 179 -300
72 -71 131 -132 131 -137 0 -4 -577 -9 -1282 -10 -1277 -3 -1284 -3 -1393 -25
-474 -95 -881 -316 -1228 -666 -53 -54 -115 -121 -137 -148 -101 -128 -88
-296 31 -403 63 -56 130 -80 213 -74 92 6 135 33 281 182 290 295 591 462 954
530 73 14 239 16 1318 16 l1235 0 -127 -132 c-145 -152 -176 -203 -177 -295
-1 -113 42 -198 130 -254 86 -54 172 -63 269 -28 54 20 83 47 453 421 217 220
405 414 418 432 56 76 65 202 21 290 -33 67 -807 835 -872 866 -66 31 -157 36
-223 12z"/>
<path d="M920 2698 c-50 -19 -90 -56 -450 -420 -217 -220 -405 -414 -418 -432
-56 -76 -65 -202 -21 -290 33 -68 807 -835 874 -867 67 -32 179 -33 245 -3
105 49 170 151 170 269 0 109 -23 148 -179 300 -72 71 -131 132 -131 137 0 4
579 9 1288 10 l1287 3 130 28 c369 79 734 255 1014 489 99 83 263 246 321 319
101 128 88 296 -31 403 -81 73 -178 93 -289 60 -49 -14 -69 -30 -195 -158
-259 -262 -506 -417 -798 -501 -198 -57 -145 -55 -1484 -55 l-1235 0 127 133
c145 151 176 202 177 294 1 113 -42 198 -130 254 -86 54 -176 63 -272 27z"/>
</g>
</svg>                                </div>
                                <div class="product-image">
                                    <!-- Item Oferecido Image -->
                                    <img src="<?php echo base_url('assets/images/items/' . ($solicitacao->directory_path_oferecido ?? 'imagem_default.jpg')); ?>" alt="Produto Oferecido">
                                    <span><?= $solicitacao->solicitante_name; ?></span>
                                </div>
                            </div>
                            <div class="product-actions">
                            <form action="<?= base_url('solicitacoes/aceitar/' . $solicitacao->solicitacao_id); ?>" method="post">
                            <?php echo csrf_field(); ?>
                                    <button type="submit" class="accept-button">Aceitar</button>
                                </form>
                                <form action="<?= base_url('solicitacoes/negar/' . $solicitacao->solicitacao_id); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                    <button type="submit" class="deny-button">Negar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Você não recebeu nenhuma solicitação no momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>