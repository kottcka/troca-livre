<?php

function formatPhoneNumber($phoneNumber) {
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber); // Remove caracteres não numéricos

    if (strlen($phoneNumber) == 11) {
        // Formato com 11 dígitos (incluindo DDD)
        $formattedNumber = '(' . substr($phoneNumber, 0, 2) . ') ' . substr($phoneNumber, 2, 5) . '-' . substr($phoneNumber, 7, 4);
    } elseif (strlen($phoneNumber) == 10) {
        // Formato com 10 dígitos (sem DDD)
        $formattedNumber = '(' . substr($phoneNumber, 0, 2) . ') ' . substr($phoneNumber, 2, 4) . '-' . substr($phoneNumber, 6, 4);
    } else {
        // Formato inválido (retorna o número original)
        $formattedNumber = $phoneNumber;
    }

    return $formattedNumber;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações Finalizadas</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/solicitacao_finalizadas_style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="container">
    <?= view('common/sidebar_view') ?>
    <div class="main-content">
        <?= view('common/topbar_view_nosearch') ?>
    </div>
</div>
<div class="section">
    <h2>Solicitações Finalizadas</h2>
    <div class="products-container">
        <?php if (!empty($solicitacoes)): ?>
            <?php foreach ($solicitacoes as $solicitacao): ?>
                <div class="solicitacao">
                    <div class="solicitacao-info">
                        <!-- Div para as imagens -->
                        <div class="item-images">
                            <!-- Imagem do Seu Item -->
                            <img src="<?= base_url('assets/images/items/' . $solicitacao->directory_path); ?>"
                                 alt="Produto <?= $solicitacao->nome; ?>">
                            <!-- Imagem do Item Oferecido -->
                            <?php if (isset($solicitacao->directory_path_oferecido)): ?>
                                <img class="item-image"
                                     src="<?= base_url('assets/images/items/' . $solicitacao->directory_path_oferecido); ?>"
                                     alt="Produto Oferecido <?= $solicitacao->nome_oferecido; ?>">
                            <?php endif; ?>
                        </div>
                        <!-- Div para o texto -->
                        <div class="item-info">
                            <p><strong>Seu item:</strong> <?= $solicitacao->nome ?? 'N/A'; ?></p>
                            <p><strong>Item Oferecido:</strong> <?= $solicitacao->nome_oferecido ?? 'N/A'; ?></p>
                        </div>
                    </div>
                    <?php if (strtolower($solicitacao->status) == 'negado'): ?>
                    <?php else: ?>
                        <div class="contato-info">
                            <p><i class="fas fa-phone-alt"></i> Número de Contato:</p>
                            <?php
                            // Verifique se o usuário logado é o solicitante na solicitação
                            $isSolicitante = ($solicitacao->solicitante_id == session()->get('user')->id);
                            $phone = $isSolicitante ? formatPhoneNumber($solicitacao->phone) : formatPhoneNumber($solicitacao->solicitante_phone);
                            $email = $isSolicitante ? $solicitacao->email : $solicitacao->solicitante_email;
                            echo '<p>' . $phone . '</p>';
                            echo '<p><i class="fas fa-envelope"></i> Email: ' . $email . '</p>';
                            ?>
                        </div>
                        <?php if (strtolower($solicitacao->status) == 'aceito' && $solicitacao->canReview): ?>
                            <div class="avaliacao-container">
                                <form id="avaliacao-form-<?= $solicitacao->solicitacao_id ?>"
                                      action="<?= base_url('/solicitacao/processaAvaliacao') ?>" method="post">
                                    <div class="star-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star clickable" data-value="<?= $i ?>"></span>
                                        <?php endfor; ?>
                                        <input type="hidden" name="avaliacao" class="avaliacao" required>
                                    </div>
                                    <div class="error-message" id="error-message-<?= $solicitacao->solicitacao_id ?>"
                                         style="color: red;"></div>
                                    <input type="hidden" name="solicitacao_id" value="<?= $solicitacao->solicitacao_id ?>">
                                    <input type="hidden" name="avaliado_id"
                                           value="<?= ($isSolicitante ? $solicitacao->destinatario_id : $solicitacao->solicitante_id) ?>">
                                    <button type="submit" class="btn"
                                            id="avaliacao-btn-<?= $solicitacao->solicitacao_id ?>">Enviar Avaliação
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <button class="status-button <?= strtolower($solicitacao->status) ?>">
                        <?= ucfirst($solicitacao->status) ?>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você não tem nenhuma solicitação finalizada no momento.</p>
        <?php endif; ?>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var solicitacoes = document.querySelectorAll('.solicitacao');
        solicitacoes.forEach(function (solicitacao) {
            var stars = solicitacao.querySelectorAll('.star.clickable');
            var form = solicitacao.querySelector('form');
            if (form) {
                var input = form.querySelector('.avaliacao');
                var errorMessage = form.querySelector('.error-message');
                var submitButton = form.querySelector('.btn');

                stars.forEach(function (star) {
                    star.addEventListener('click', function () {
                        var value = this.getAttribute('data-value');
                        input.value = value;
                        stars.forEach(function (s) {
                            if (s.getAttribute('data-value') <= value) {
                                s.classList.add('active');
                            } else {
                                s.classList.remove('active');
                            }
                        });
                    });
                });

                form.addEventListener('submit', function (e) {
                    var rating = input.value;
                    if (!rating) {
                        e.preventDefault();
                        errorMessage.textContent = 'Por favor, selecione uma avaliação antes de enviar.';
                    } else {
                        errorMessage.textContent = '';
                        submitButton.disabled = true;
                    }
                });
            }
        });
    });
</script>
</body>
</html>
