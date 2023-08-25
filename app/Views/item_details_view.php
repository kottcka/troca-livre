<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Item</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/item_details_style.css'); ?>">
</head>
<body>
    
    <div class="container">
        <?= view('common/sidebar_view') ?>
        <div class="main-content">
            <?= view('common/topbar_view') ?>
        </div>
    </div>

    <div class="section">
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger">
        <?= session('error') ?>
    </div>
    <?php endif; ?>
    <?php if (isset($item)): ?>



    <div class="item-detail-container">
        <div class="item-detail">
            <div class="image-container">
                <div class="item-images">
                    <div class="main-image-wrapper">
                        <img id="mainImage" src="<?php echo base_url('assets/images/items/' . ($item->directory_path ?? 'imagem_default.jpg')); ?>" alt="Produto <?= $item->nome; ?>">
                    </div>
                    <div class="sub-images">
                        <img src="<?php echo base_url('assets/images/items/' . ($item->directory_path ?? 'imagem_default.jpg')); ?>" class="sub-image" alt="Produto <?= $item->nome; ?>">
                        <?php if (isset($item->sub_images) && !empty($item->sub_images)): ?>
                        <?php foreach ($item->sub_images as $subImage): ?>
                            <img src="<?php echo base_url('assets/images/items/' . $subImage->directory_path); ?>" class="sub-image" alt="Sub-imagem do produto <?= $item->nome; ?>">
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="description-container">
                <div class="item-info">
                    <div class="item-state-category">
                        <?= $item->estado; ?> | <?= $item->categoria; ?>
                    </div>
                    <h2><?= $item->nome; ?></h2>
                    <p><?= $item->descricao; ?></p>
                </div>
            </div>
        <div class="action-container">
            
        <div class="user-info-title">
        <h3>Informações do proprietário:</h3>
        <div class="user-infos">
            <p class="user-name"><?= ucwords(strtolower(explode(" ", $item->user->full_name)[0] . " " . explode(" ", $item->user->full_name)[1])); ?></p>
            <div class="user-location">
                <span>Estado: <?= $item->user->state; ?></span>
                <span>Cidade: <?= $item->user->city; ?></span>
            </div>
            <div class="user-reputation">
                <span>Reputação: </span>
                <div class="star-rating" style="display: inline-block;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?= ($i <= $mediaAvaliacao) ? 'active' : '' ?>" data-value="<?= $i ?>"></span>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
    <?php if (session()->get('user')): ?>
        <?php if ($item->user_id !== session()->get('user')->id): ?>
            <?php if ($item->status == 'disponivel'): ?>
                <?php if ($item->tipo === 'troca'): ?>
                    <form action="<?= base_url('solicitacao/solicitarTroca/' . $item->id) ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="action-button">Quero Trocar</button>
                    </form>
                <?php else: ?>
                    <form action="<?= base_url('solicitacao/solicitarDoacao/' . $item->id) ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="action-button">Receber Doação</button>
                    </form>
                <?php endif; ?>
            <?php elseif ($item->status == 'trocado'): ?>
                <div class="warning-message">
                    Esse item já foi trocado.
                </div>
            <?php elseif ($item->status == 'doado'): ?>
                <div class="warning-message">
                    Esse item já foi doado.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="warning-message">
                Você não pode trocar ou doar o próprio item.
            </p>
        <?php endif; ?>
    <?php else: ?>
        <p>Para solicitar uma troca ou doação, você precisa estar logado.</p>
    <?php endif; ?>
</div>
     </div>
        </div>
    </div>
    <?php else: ?>
    <p>Item não encontrado.</p>
    <?php endif; ?>
</div>


<div class="questions-section">
    <h3>Dúvidas</h3>
    <?php if (isset($perguntas) && !empty($perguntas)): ?>
        <?php foreach ($perguntas as $pergunta): ?>
            <div class="question">
            <div class="question-header">
    <p class="question-text" data-id="<?= $pergunta['id']; ?>"><?= $pergunta['texto']; ?></p>
    <button onclick="showOptions(<?= $pergunta['id']; ?>)" class="options-button" data-id="<?= $pergunta['id']; ?>">&#8942;</button>
    <div class="options-menu" data-id="<?= $pergunta['id']; ?>" style="display: none;">
        <?php if ($item->user_id === session()->get('user')->id): ?>
            <button class="delete-button">Excluir</button>
        <?php endif; ?>
        <button class="report-button">Denunciar</button>
    </div>
</div>
                <?php
                $hasResponse = false;
                if (isset($respostas) && !empty($respostas)): ?>
                    <?php foreach ($respostas as $resposta): ?>
                        <?php if ($resposta['parent_id'] == $pergunta['id']): ?>
                            <div class="response">
                                <p class="response-text" data-id="<?= $resposta['id']; ?>"><?= $resposta['texto']; ?></p>
                                <?php if ($item->user_id === session()->get('user')->id): ?>
                                    <button onclick="editResposta(<?= $resposta['id']; ?>, <?= $item->id; ?>)" class="edit-button" data-id="<?= $resposta['id']; ?>">Editar</button>
                                <?php endif; ?>
                                <?php $hasResponse = true; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!$hasResponse && $item->user_id === session()->get('user')->id): ?>
                    <button onclick="responderPergunta(<?= $pergunta['id']; ?>, <?= $item->id; ?>)" class="answer-button" data-id="<?= $pergunta['id']; ?>">Responder</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhuma dúvida foi feita ainda.</p>
    <?php endif; ?>

    <form class="question-form" action="<?= base_url('item/postPerguntaResposta'); ?>" method="post">
        <?= csrf_field(); ?>
        <input type="hidden" name="item_id" value="<?= $item->id; ?>">
        <textarea name="texto" rows="4" placeholder="Faça sua pergunta aqui..." class="question-input"></textarea>
        <button type="submit" class="ask-button">Perguntar</button>
    </form>
</div>

<script>
let currentOptionsMenu = null; // Vamos manter o menu de opções atualmente exibido aqui.

function showOptions(perguntaId) {
    const optionsMenu = document.querySelector('.options-menu[data-id="' + perguntaId + '"]');
    
    if (currentOptionsMenu && currentOptionsMenu !== optionsMenu) {
        currentOptionsMenu.style.display = 'none';
    }
    
    if (optionsMenu.style.display === 'block') {
        optionsMenu.style.display = 'none';
        document.removeEventListener('click', hideOptionsMenu);
    } else {
        optionsMenu.style.display = 'block';
        currentOptionsMenu = optionsMenu;
        document.addEventListener('click', hideOptionsMenu);
    }
}

function hideOptionsMenu(event) {
    if (!event.target.classList.contains('options-button') && !event.target.closest('.options-menu')) {
        if (currentOptionsMenu) {
            currentOptionsMenu.style.display = 'none';
            currentOptionsMenu = null;
            document.removeEventListener('click', hideOptionsMenu);
        }
    }
}
function editResposta(respostaId, itemId) {
    const respostaElement = document.querySelector('.response-text[data-id="' + respostaId + '"]');
    const editButton = document.querySelector('.edit-button[data-id="' + respostaId + '"]');

    const input = document.createElement('textarea');
    input.value = respostaElement.innerText;
    input.setAttribute('rows', '4');

    const saveButton = document.createElement('button');
    saveButton.innerText = 'Salvar';
    saveButton.classList.add('save-button');

    // MUDANÇA: Adicionado botão de cancelamento
    const cancelButton = document.createElement('button');
    cancelButton.innerText = 'Cancelar';
    cancelButton.classList.add('cancel-button');

    const container = document.createElement('div');
    container.classList.add('response-edit-container');
    container.appendChild(input);
    container.appendChild(saveButton);
    container.appendChild(cancelButton); // MUDANÇA: Adicionado botão de cancelamento ao container

    saveButton.addEventListener('click', function() {
        const formData = new URLSearchParams();
        formData.append('respostaId', respostaId);
        formData.append('texto', input.value);
        formData.append('item_id', itemId);

        fetch('<?= base_url('item/editResposta'); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.text())
        .then(() => {
            respostaElement.innerText = input.value;
            respostaElement.parentNode.removeChild(container);
            editButton.style.display = 'inline-block';
        })
        .catch(error => {
            console.error('Erro ao editar a resposta:', error);
        });
    });

    cancelButton.addEventListener('click', function() {
        respostaElement.parentNode.removeChild(container);
        editButton.style.display = 'inline-block';
    });

    respostaElement.parentNode.appendChild(container);
    editButton.style.display = 'none';
}

// MUDANÇA: Adicionada função para responder a uma pergunta
function responderPergunta(perguntaId, itemId) {
    const perguntaElement = document.querySelector('.question-text[data-id="' + perguntaId + '"]');
    const answerButton = document.querySelector('.answer-button[data-id="' + perguntaId + '"]');

    const input = document.createElement('textarea');
    input.setAttribute('rows', '4');
    input.setAttribute('name', 'resposta');
    input.setAttribute('placeholder', 'Digite sua resposta aqui...');

    const submitButton = document.createElement('button');
    submitButton.innerText = 'Enviar resposta';
    submitButton.classList.add('save-button');

    const cancelButton = document.createElement('button');
    cancelButton.innerText = 'Cancelar';
    cancelButton.classList.add('cancel-button');

    const container = document.createElement('div');
    container.classList.add('response-edit-container');
    container.appendChild(input);
    container.appendChild(submitButton);
    container.appendChild(cancelButton);

    submitButton.addEventListener('click', function() {
        const formData = new URLSearchParams();
        formData.append('perguntaId', perguntaId);
        formData.append('texto', input.value);
        formData.append('item_id', itemId);

        fetch('<?= base_url('item/postPerguntaResposta'); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(response => response.text())
        .then(() => {
            perguntaElement.parentNode.appendChild(container);
            window.location.reload();
        })
        .catch(error => {
            console.error('Erro ao responder a pergunta:', error);
        });
    });

    cancelButton.addEventListener('click', function() {
        perguntaElement.parentNode.removeChild(container);
        answerButton.style.display = 'inline-block';
    });

    perguntaElement.parentNode.appendChild(container);
    answerButton.style.display = 'none';
}

    </script>



<script>
        document.querySelectorAll('.sub-image').forEach(function(img) {
            img.addEventListener('click', function() {
                var newSrc = img.src;
                document.getElementById('mainImage').src = newSrc;
            });
        });
    </script>

    
</body>
</html>