<?php
    include 'Navbar.php';
?>
<link rel="stylesheet" href="css/perfil.css">
</head>
<body>
  </header>

  <section class="profile-header">
    <img src="img/perfil.png" alt="Usuário" class="user-photo">
    <h2>Evellyn Furtado</h2>
    <p class="member-since">Membro desde<br>28 de jul. de 2024</p>
  </section>

  <section class="profile-options">
    <div class="option" onclick="EditarDados()"> Editar Perfil</div>
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
        window.location.href = 'index.html';
      }
    }
    
    function confirmarExclusao() {
      if(confirm("Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
        alert("Conta marcada para exclusão...");
        // window.location.href = 'delete-account.php';
      }
    }
  </script>
</body>
</html>
