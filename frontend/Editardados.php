<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Editar Dados</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: #fff;
    }

    .page {
      display: flex;
      height: 100vh;
    }

    .panel-left {
      flex: 1;
      background: #f5f0ec;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .back-btn {
      position: absolute;
      top: 15px;
      left: 15px;
      font-size: 24px;
      cursor: pointer;
    }

    .form-wrap {
      width: 100%;
      max-width: 400px;
    }

    .form-wrap h1 {
      margin-bottom: 20px;
      color: #123047;
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .actions {
      margin-top: 20px;
      display: flex;
      gap: 10px;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-outline {
      background: #d9eaff;
    }

    .btn-primary {
      background: #0073e6;
      color: white;
    }

    /* Painel direito */
    .panel-right {
      width: 350px;
      background: #2bbef0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .mascot {
      width: 320px; 
    }

    /* Responsividade */
    @media (max-width: 768px) {
      .page {
        flex-direction: column;
      }
      .panel-right {
        width: 100%;
        height: 200px;
      }
    }
  </style>
</head>
<body>
  <div class="page">
    <div class="panel-left">
      <div class="back-btn">&#8592;</div>
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
