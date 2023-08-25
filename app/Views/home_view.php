<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/homepage_style.css'); ?>">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="categories-container" style="margin-top: 180px;">
                <div class="category">Início</div>
            </div>
        </div>
        
        <div class="main-content">
            <div class="top-bar center">
                <div class="hamburger-menu" onclick="toggleSidebar()">
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                </div>
                <div class="logo-container">
                    <img src="/assets/images/logo_home.png" alt="Logo do Site" class="logo">
                </div>
                <input type="text" placeholder="Buscar itens disponíveis..." class="search-bar" id="searchBar">
                <div class="user-buttons">
                    <a href="/login" class="login-text">Entrar</a>
                    <a href="register" class="register-text">Cadastrar-se</a>
                </div>
            </div>
            
            <!-- Seção de Trocas -->
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
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('active');
    }

    document.querySelectorAll('.product').forEach(product => {
        product.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            window.location.href = `<?= base_url('item/'); ?>${itemId}`;
        });
    });
</script>

   </body>
</html>