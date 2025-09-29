<?php
include 'Navbar.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: Login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/perfil.css">
  <title>Perfil</title>
</head>
<body>
  <header></header>

  <section class="profile-header">
    <img src="img/perfil.png" alt="Usuário" class="user-photo">
    <h2><?php echo $_SESSION['usuario_nome']; ?></h2>
    <p class="member-since">Membro desde<br>28 de jul. de 2024</p>
  </section>

  <section class="profile-options">
    <div class="option" onclick="EditarDados()">Editar Perfil</div>
    <div class="option" onclick="confirmarSaida()">Sair da Conta</div>
    <div class="option" onclick="confirmarExclusao()">Deletar Perfil</div>
  </section>

  <script>
    function EditarDados() {
      window.location.href = 'Editardados.php';
    }
    
    function confirmarSaida() {
      if(confirm("Tem certeza que deseja sair da sua conta?")) {
        alert("Saindo da conta...");
        window.location.href = 'index.php';
      }
    }
    
    function confirmarExclusao() {
      if(confirm("Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
        alert("Conta marcada para exclusão...");
        window.location.href = 'index.php';
      }
    }
  </script>
</body>
</html>