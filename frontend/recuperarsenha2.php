<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/recuperarsenha.css">
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#0066e6">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Dr IAgóstico">
  <link rel="apple-touch-icon" href="img/icon-192.png">
  <title>Tela Recuperar Senha</title>
</head>
<body>
  <div class="container">
    <h1>Recuperar Senha</h1>
    <p>Digite o seu CRM e Email para redefinir a senha</p>
    <form>
      <input type="text" class="form-control" placeholder="CRM">
      <input type="email" class="form-control" placeholder="Email">
      <div class="button-container">
        <button type="submit" class="btn btn-primary" onclick="Redefinir()">Redefinir</button>
        <a href="EditarDados.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>
  <script>
    function Redefinir() {
        alert("Enviamos as instruções para o seu email.");
    }
  
  </script>
  <script src="js/app.js"></script>
</body>
</html>