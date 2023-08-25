<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/register_style.css'); ?>">
    
</head>

<body>
    <div class="background-image"></div>
    <div class="register-container">
        <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="logo">
    <?php if(session()->has('error')): ?>
    <span class="error"><?php echo session()->getFlashdata('error') ;?> </span>
    <?php endif ?>

    <?php if(session()->has('success')): ?>
    <span class="success"><?php echo session()->getFlashdata('success') ;?> </span>
    <?php endif ?>

        <form action="<?php echo url_to('register.store') ?>" method="post" id="registerForm">
        <?php echo csrf_field(); ?>
            <div class="input-row">
                <div class="input-col">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
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
                    <input type="text" id="full_name" name="full_name">
                    <span id="nameError" class="error"><?php echo session()->getFlashdata('errors')['full_name'] ?? ''; ?></span>
                </div>

                <div class="input-col">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf">
                    <span id="cpfError" class="error"><?php echo session()->getFlashdata('errors')['cpf'] ?? ''; ?></span>
                </div>
            </div>

            <div class="input-row">
                <div class="input-col">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep">
                    <span id="cepError" class="error"><?php echo session()->getFlashdata('errors')['cep'] ?? ''; ?></span>
                </div>

                <div class="input-col">
                    <label for="phone">Telefone</label>
                    <input type="tel" id="phone" name="phone">
                    <span id="phoneError" class="error"><?php echo session()->getFlashdata('errors')['phone'] ?? ''; ?></span>
                </div>
            </div>

            <div class="input-row">
                <div class="input-col">
                    <label for="state">Estado</label>
                    <select name="state" id="state">
                    <option value="" selected disabled hidden></option>
    <option value="AC">AC - Acre</option>
    <option value="AL">AL - Alagoas</option>
    <option value="AP">AP - Amapá</option>
    <option value="AM">AM - Amazonas</option>
    <option value="BA">BA - Bahia</option>
    <option value="CE">CE - Ceará</option>
    <option value="DF">DF - Distrito Federal</option>
    <option value="ES">ES - Espírito Santo</option>
    <option value="GO">GO - Goiás</option>
    <option value="MA">MA - Maranhão</option>
    <option value="MT">MT - Mato Grosso</option>
    <option value="MS">MS - Mato Grosso do Sul</option>
    <option value="MG">MG - Minas Gerais</option>
    <option value="PA">PA - Pará</option>
    <option value="PB">PB - Paraíba</option>
    <option value="PR">PR - Paraná</option>
    <option value="PE">PE - Pernambuco</option>
    <option value="PI">PI - Piauí</option>
    <option value="RJ">RJ - Rio de Janeiro</option>
    <option value="RN">RN - Rio Grande do Norte</option>
    <option value="RS">RS - Rio Grande do Sul</option>
    <option value="RO">RO - Rondônia</option>
    <option value="RR">RR - Roraima</option>
    <option value="SC">SC - Santa Catarina</option>
    <option value="SP">SP - São Paulo</option>
    <option value="SE">SE - Sergipe</option>
    <option value="TO">TO - Tocantins</option>
</select>
                    <span id="stateError" class="error"><?php echo session()->getFlashdata('errors')['state'] ?? ''; ?></span>                   
                </div>

                <div class="input-col">
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city">
                    <span id="cityError" class="error"><?php echo session()->getFlashdata('errors')['city'] ?? ''; ?></span>
                </div>
            </div>

                <div class="input-col">
                    <label for="address">Endereço</label>
                    <input type="text" id="address" name="address">
                    <span id="addressError" class="error"><?php echo session()->getFlashdata('errors')['address'] ?? ''; ?></span>
                </div>

            <button type="submit" class="register-button">Cadastrar</button>
            <a href="/login" class="already-account">Já tem uma conta? Faça login</a>
        </form>
    </div>
</body>

</html>
