<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sidebar_style.css'); ?>">
</head>
<body>
<div id="hamburger-icon">
    <div style="width: 25px; height: 3px; background: #000; margin: 4px 0;"></div>
    <div style="width: 25px; height: 3px; background: #000; margin: 4px 0;"></div>
    <div style="width: 25px; height: 3px; background: #000; margin: 4px 0;"></div>
</div>
<div class="container">
    <div class="sidebar">
        <div class="categories-container" style="margin-top: 180px;">
        <?php
// Verifica se o usuário está logado
$logged_in = session()->get('user') !== null;

// Se o usuário estiver logado, redireciona para "/inicio", caso contrário, redireciona para "/"
$inicio_url = $logged_in ? '/inicio' : '/';
echo '<a href="'.$inicio_url.'" class="category">Início</a>';

if ($logged_in) {
    echo '
    <a href="/perfil" class="category">Seu perfil</a>
    <div class="category dropdown">
        <span>Itens</span>
        <a href="/cadastrar-item" class="sub-option">Cadastrar novo Item</a>
        <a href="/visualizar-itens" class="sub-option">Visualizar itens</a>
    </div>
    <div class="category dropdown">
        <span>Solicitações</span>
        <a href="/solicitacoes/enviadas" class="sub-option">Solicitações Enviadas</a>
        <a href="/solicitacoes/recebidas" class="sub-option">Solicitações Recebidas</a>
        <a href="/solicitacoes/finalizadas" class="sub-option">Solicitações Finalizadas</a>
    </div>';
}
?>

        </div>
        <div class="footer">
            <center>Troca Livre | Todos os direitos reservados.</center>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.category').forEach(category => {
        category.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });

    document.getElementById('hamburger-icon').addEventListener('click', function() {
        var sidebar = document.querySelector('.sidebar');
        if (sidebar.style.left === '0px') {
            sidebar.style.left = '-350px';
        } else {
            sidebar.style.left = '0px';
        }
    });

    window.addEventListener('resize', function() {
        var sidebar = document.querySelector('.sidebar');
        var windowWidth = window.innerWidth;

        if (windowWidth > 768) {
            sidebar.style.left = '0px'; // Volta a barra lateral para a posição padrão
        }
    });
</script>
</body>
</html>
