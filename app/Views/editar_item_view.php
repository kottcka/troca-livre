<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/edititem_style.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <?= view('common/sidebar_view') ?>
        <div class="main-content">
            <?= view('common/topbar_view_nosearch') ?>
        </div>
    </div>
    <div class="section">
    <h2>Editar Item</h2>
    <form action="<?= route_to('items.update', $item->id); ?>" method="post" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" class="input-style" value="<?= $item->nome; ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" class="input-style" required><?= $item->descricao; ?></textarea>
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoria:</label>
                <select id="categoria_id" name="categoria_id" class="input-style" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria->id; ?>" <?= $categoria->id == $item->categoria_id ? 'selected' : ''; ?>>
                            <?= $categoria->nome; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
        <label>Estado:</label>
        <ul class="custom-option-group">
            <li class="custom-option" data-value="novo" <?= $item->estado == 'novo' ? 'data-selected="true"' : ''; ?>>
                <span class="custom-option-text">Novo</span>
            </li>
            <li class="custom-option" data-value="usado" <?= $item->estado == 'usado' ? 'data-selected="true"' : ''; ?>>
                <span class="custom-option-text">Usado</span>
            </li>
        </ul>
        <input type="hidden" id="estado" name="estado" value="<?= $item->estado; ?>" required>
    </div>

    <div class="form-group">
    <label>Tipo:</label>
    <ul class="custom-option-group">
        <li class="custom-option" data-value="troca" <?= ($item->tipo == 'troca') ? 'data-selected="true"' : ''; ?>>
            <span class="custom-option-text">Troca</span>
        </li>
        <li class="custom-option" data-value="doacao" <?= ($item->tipo == 'doacao') ? 'data-selected="true"' : ''; ?>>
            <span class="custom-option-text">Doação</span>
        </li>
    </ul>
    <input type="hidden" id="tipo" name="tipo" value="<?= $item->tipo; ?>" required>
</div>

            <div class="img-upload-container">
                <div class="img-upload-box">
                    <label for="imageUpload" class="img-uploader">
                        <div class="img-uploader-btn" role="button" tabindex="0">
                            <svg class="ui-icon sc-ui-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 31 24" style="display: block; margin: 0 auto;">
                                 <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <path d="M26.7704335,2.83988084 L21.8837953,2.83988084 L20.1805259,0 L10.6766169,0 L8.97334755,2.83988084 L4.08670931,2.83988084 C1.83333333,2.83988084 0,4.56352145 0,6.68207256 L0,20.1578083 C0,22.2763594 1.83333333,24 4.08670931,24 L26.7704335,24 C29.0238095,24 30.8571429,22.2763594 30.8571429,20.1578083 L30.8571429,6.68207256 C30.8571429,4.56352145 29.0238095,2.83988084 26.7704335,2.83988084 Z M15.4285714,20.8695652 C11.3324111,20.8695652 8,17.5924657 8,13.5650482 C8,9.53796907 11.3324111,6.26086957 15.4285714,6.26086957 C19.5247318,6.26086957 22.8571429,9.53796907 22.8571429,13.5650482 C22.8571429,17.5924657 19.5247318,20.8695652 15.4285714,20.8695652 Z M16.6699507,9.39130435 L14.1871921,9.39130435 L14.1871921,12.9535232 L10.2857143,12.9535232 L10.2857143,15.2203898 L14.1871921,15.2203898 L14.1871921,18.7826087 L16.6699507,18.7826087 L16.6699507,15.2203898 L20.5714286,15.2203898 L20.5714286,12.9535232 L16.6699507,12.9535232 L16.6699507,9.39130435 Z" id="Shape" fill="#3483FA" fill-rule="nonzero"></path>
                                </g>
                                </svg>
                            <a class="img-uploader-link" style="display: block; text-align: center;">Adicione ou arraste suas fotos aqui</a>
                        </div>
                        <input type="file" id="imageUpload" name="imagens[]" accept="image/jpg, image/jpeg, image/png, image/webp" multiple style="display: none;">
                        </label>
        </div>

        <div class="item-images">
                    <?php foreach ($item->imagens as $imagem): ?>
                        <div class="item-image">
                            <img src="<?= base_url('assets/images/items/' . $imagem->directory_path); ?>" alt="Imagem do Item">
                            <a href="#" class="remove-image" data-image-id="<?= $imagem->id; ?>">×</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Campo oculto para IDs de imagens removidas -->
            <input type="hidden" id="removedImagesInput" name="removedImages" value="">

            <!-- Botões do formulário -->
            <div class="form-group buttons">
                <button type="submit">Salvar</button>
                <a href="/visualizar-itens" class="cancel-button">Cancelar</a>
            </div>
        </form>
    </div>
    <script>
$(document).ready(function() {

// Função para gerenciar as opções customizadas
$(".custom-option").click(function() {
    var selectedValue = $(this).data("value");
    var targetInput = $(this).closest(".form-group").find("input[type=hidden]");
    
    // Atualizar visualmente a opção selecionada
    $(this).siblings().attr("data-selected", "false");
    $(this).attr("data-selected", "true");
    
    // Atualizar o valor no input oculto
    targetInput.val(selectedValue);
});


$(".remove-image").click(function(e) {
    e.preventDefault();
    
    var imageId = $(this).data("image-id");
    $(this).closest(".item-image").hide();

    // Adicionar o ID à lista de imagens a serem removidas
    var removedImages = $("#removedImagesInput").val();
    if (removedImages === "") {
        removedImages = [];
    } else {
        removedImages = JSON.parse(removedImages);
    }
    removedImages.push(imageId);

    // Atualizar o campo oculto com os IDs das imagens removidas
    $("#removedImagesInput").val(JSON.stringify(removedImages));
});
});


</script>
</body>

</html>