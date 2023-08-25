<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Dados</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/editarperfil_style.css'); ?>">
</head>

<body>
    <div class="background-image"></div>
    <div class="register-container">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="logo">
        <?php if(session()->has('error')): ?>
            <span class="error"><?php echo session()->getFlashdata('error'); ?></span>
        <?php endif ?>

        <?php if(session()->has('success')): ?>
            <span class="success"><?php echo session()->getFlashdata('success'); ?></span>
        <?php endif ?>

        <form action="<?= route_to('register.update'); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
            <div class="input-row">
                <div class="input-col">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $userData->email; ?>">
                    <span id="emailError" class="error"><?php echo session()->getFlashdata('errors')['email'] ?? ''; ?></span>
                </div>

                <div class="input-col">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password">
                    <span id="passwordError" class="error"><?php echo session()->getFlashdata('errors')['password'] ?? ''; ?></span>
                </div>
            </div>

            <div class="input-row">
                <div class="input-col">
                    <label for="full_name">Nome Completo</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo $userData->full_name; ?>">
                    <span id="nameError" class="error"><?php echo session()->getFlashdata('errors')['full_name'] ?? ''; ?></span>
                </div>

                <div class="input-col">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo $userData->cpf; ?>">
                    <span id="cpfError" class="error"><?php echo session()->getFlashdata('errors')['cpf'] ?? ''; ?></span>
                </div>
            </div>

            <div class="input-row">
                <div class="input-col">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" value="<?php echo $userData->cep; ?>">
                    <span id="cepError" class="error"><?php echo session()->getFlashdata('errors')['cep'] ?? ''; ?></span>
                </div>

                <div class="input-col">
                    <label for="phone">Telefone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $userData->phone; ?>">
                    <span id="phoneError" class="error"><?php echo session()->getFlashdata('errors')['phone'] ?? ''; ?></span>
                </div>
            </div>

            <div class="input-row">
                <div class="input-col">
                    <label for="state">Estado</label>
                    <select name="state" id="state">
    <option value="" <?php echo empty($userData->state) ? 'selected' : ''; ?>></option>
    <option value="AC" <?php echo $userData->state == 'AC' ? 'selected' : ''; ?>>AC - Acre</option>
    <option value="AL" <?php echo $userData->state == 'AL' ? 'selected' : ''; ?>>AL - Alagoas</option>
    <option value="AP" <?php echo $userData->state == 'AP' ? 'selected' : ''; ?>>AP - Amapá</option>
    <option value="AM" <?php echo $userData->state == 'AM' ? 'selected' : ''; ?>>AM - Amazonas</option>
    <option value="BA" <?php echo $userData->state == 'BA' ? 'selected' : ''; ?>>BA - Bahia</option>
    <option value="CE" <?php echo $userData->state == 'CE' ? 'selected' : ''; ?>>CE - Ceará</option>
    <option value="DF" <?php echo $userData->state == 'DF' ? 'selected' : ''; ?>>DF - Distrito Federal</option>
    <option value="ES" <?php echo $userData->state == 'ES' ? 'selected' : ''; ?>>ES - Espírito Santo</option>
    <option value="GO" <?php echo $userData->state == 'GO' ? 'selected' : ''; ?>>GO - Goiás</option>
    <option value="MA" <?php echo $userData->state == 'MA' ? 'selected' : ''; ?>>MA - Maranhão</option>
    <option value="MT" <?php echo $userData->state == 'MT' ? 'selected' : ''; ?>>MT - Mato Grosso</option>
    <option value="MS" <?php echo $userData->state == 'MS' ? 'selected' : ''; ?>>MS - Mato Grosso do Sul</option>
    <option value="MG" <?php echo $userData->state == 'MG' ? 'selected' : ''; ?>>MG - Minas Gerais</option>
    <option value="PA" <?php echo $userData->state == 'PA' ? 'selected' : ''; ?>>PA - Pará</option>
    <option value="PB" <?php echo $userData->state == 'PB' ? 'selected' : ''; ?>>PB - Paraíba</option>
    <option value="PR" <?php echo $userData->state == 'PR' ? 'selected' : ''; ?>>PR - Paraná</option>
    <option value="PE" <?php echo $userData->state == 'PE' ? 'selected' : ''; ?>>PE - Pernambuco</option>
    <option value="PI" <?php echo $userData->state == 'PI' ? 'selected' : ''; ?>>PI - Piauí</option>
    <option value="RJ" <?php echo $userData->state == 'RJ' ? 'selected' : ''; ?>>RJ - Rio de Janeiro</option>
    <option value="RN" <?php echo $userData->state == 'RN' ? 'selected' : ''; ?>>RN - Rio Grande do Norte</option>
    <option value="RS" <?php echo $userData->state == 'RS' ? 'selected' : ''; ?>>RS - Rio Grande do Sul</option>
    <option value="RO" <?php echo $userData->state == 'RO' ? 'selected' : ''; ?>>RO - Rondônia</option>
    <option value="RR" <?php echo $userData->state == 'RR' ? 'selected' : ''; ?>>RR - Roraima</option>
    <option value="SC" <?php echo $userData->state == 'SC' ? 'selected' : ''; ?>>SC - Santa Catarina</option>
    <option value="SP" <?php echo $userData->state == 'SP' ? 'selected' : ''; ?>>SP - São Paulo</option>
    <option value="SE" <?php echo $userData->state == 'SE' ? 'selected' : ''; ?>>SE - Sergipe</option>
    <option value="TO" <?php echo $userData->state == 'TO' ? 'selected' : ''; ?>>TO - Tocantins</option>
</select>
                    <span id="stateError" class="error"><?php echo session()->getFlashdata('errors')['state'] ?? ''; ?></span>                   
                </div>

                <div class="input-col">
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" value="<?php echo $userData->city; ?>">
                    <span id="cityError" class="error"><?php echo session()->getFlashdata('errors')['city'] ?? ''; ?></span>
                </div>
            </div>

            <div class="input-col">
                <label for="address">Endereço</label>
                <input type="text" id="address" name="address" value="<?php echo $userData->address; ?>">
                <span id="addressError" class="error"><?php echo session()->getFlashdata('errors')['address'] ?? ''; ?></span>
            </div>

            <button type="submit" class="register-button">Atualizar</button>
            <button type="button" class="delete-button" onclick="confirmSelection()">Deletar Perfil</button>
            <a href="/inicio" class="already-account">Voltar</a>
        </form>
    </div>

    <!-- Código do modal de confirmação -->
    <div id="confirmationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 10px; width: 300px;">
            <p>Tem certeza de que deseja deletar seu perfil? Esta ação não pode ser desfeita.</p>
            <button onclick="submitForm()">Sim</button>
            <button onclick="closeModal()">Cancelar</button>
        </div>
    </div>

    <script>
        function confirmSelection() {
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        function submitForm() {
            // Redirecionar para a URL de exclusão do perfil
            window.location.href = "/deletar-perfil";
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }
    </script>
</body>

</html>
