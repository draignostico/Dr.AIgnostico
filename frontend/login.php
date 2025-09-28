<?php
session_start();
include 'conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $crm = trim($_POST['crm']);
  $senha = $_POST['senha'];

  if (!$crm || !$senha) {
    $erro = "Preencha todos os campos.";
  } else {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE crm = :crm");
    $stmt->execute(['crm' => $crm]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
      // Login ok
      $_SESSION['usuario_id'] = $usuario['id'];
      $_SESSION['usuario_nome'] = $usuario['nome'];
      header("Location: Home.php");
      exit;
    } else {
      $erro = "CRM ou senha inválidos.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/login.css">
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#0066e6">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Dr IAgóstico">
  <link rel="apple-touch-icon" href="img/icon-192.png">
  <title>Login</title>
</head>

<body>
  <div class="container">
    <h1>Login</h1>
    <?php if ($erro)
      echo "<p style='color:red;'>$erro</p>"; ?>
    <form method="POST">
      <input type="text" name="crm" placeholder="CRM" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit" class="btn-primary">ENTRAR</button>
      <div class="link-container">
        <a href="Cadastro.php">Criar conta</a>
        <a href="recuperarsenha.php">Esqueci minha senha</a>
      </div>
    </form>
  </div>
</body>

</html>