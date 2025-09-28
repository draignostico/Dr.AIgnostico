<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <title>Editar Dados</title>
  <link rel="stylesheet" href="css/editardados.css">
</head>

<body>
  <div class="page">
    <div class="panel-left">
      <a href="perfil.php" class="back-btn">&#8592;</a>
      <div class="form-wrap">
        <h1>Edite seus dados:</h1>

        <label for="nome">Nome:</label>
        <input id="nome" type="text" placeholder="Digite seu nome">

        <label for="email">Email:</label>
        <input id="email" type="email" placeholder="seu@email.com">

        <label for="crm">CRM:</label>
        <input id="crm" type="text" placeholder="Número do CRM">

        <div class="actions">
          <button class="btn btn-outline" onclick="Redefinir()">Redefinir senha</button>
          <button class="btn btn-primary">Salvar</button>
        </div>
      </div>
    </div>

    <div class="panel-right">
      <img class="mascot" src="img/doutor.png" alt="Mascote médico">
    </div>

    <script>
      function Redefinir() {
        alert("Enviamos as instruções para o seu email.");
      }

    </script>

</body>

</html>