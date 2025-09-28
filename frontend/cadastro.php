<?php
session_start();
include 'conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome']);
  $crm = trim($_POST['crm']);
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];

  if (!$nome || !$crm || !$email || !$senha) {
    $erro = "Todos os campos são obrigatórios.";
  } else {
    // Verifica se CRM ou email já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE crm = :crm OR email = :email");
    $stmt->execute(['crm' => $crm, 'email' => $email]);
    if ($stmt->rowCount() > 0) {
      $erro = "CRM ou email já cadastrado.";
    } else {
      // Criptografa a senha
      $hash = password_hash($senha, PASSWORD_DEFAULT);

      // Insere no banco
      $stmt = $pdo->prepare("INSERT INTO usuarios (nome, crm, email, senha) VALUES (:nome, :crm, :email, :senha)");
      $stmt->execute([
        'nome' => $nome,
        'crm' => $crm,
        'email' => $email,
        'senha' => $hash
      ]);

      $sucesso = "Cadastro realizado com sucesso! Redirecionando para login...";
      header("refresh:2; url=Login.php");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/cadastro.css">
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#0066e6">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Dr IAgóstico">
  <link rel="apple-touch-icon" href="img/icon-192.png">
  <title>Cadastro</title>
</head>

<body>
  <div class="container">
    <h1>Cadastro</h1>
    <?php if ($erro)
      echo "<p style='color:red;'>$erro</p>"; ?>
    <?php if ($sucesso)
      echo "<p style='color:green;'>$sucesso</p>"; ?>
    <form method="POST">
      <input type="text" class="form-control" name="nome" placeholder="Nome" required>
      <input type="text" class="form-control" name="crm" placeholder="CRM" required>
      <input type="email" class="form-control" name="email" placeholder="Email" required>
      <input type="password" class="form-control" name="senha" placeholder="Senha" required>
      <button type="submit" class="btn-primary">Criar Conta</button>
      <p class="cadastro">Já tem uma conta? <a href="Login.php">Faça Login</a></p>
    </form>
  </div>
</body>

</html>