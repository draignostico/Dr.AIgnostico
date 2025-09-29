<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: Login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dr. AIgnóstico</title>
  <link rel="stylesheet" href="css/navbar.css">
  <style>
    .settings-icon {
      width: 16px !important;
      height: 16px !important;
      object-fit: contain;
    }
    
    .settings-btn {
      width: 28px !important;
      height: 28px !important;
    }
  </style>
</head>
<body>
  <!-- Topbar -->
  <div class="topbar">
    <button class="hamburger" id="menu-btn">&#9776;</button>
  </div>

  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <div class="sidebar-header">
      <button class="settings-btn" id="settings-btn" onclick="abrirSuporte()" style="width: 28px; height: 28px;">
        <img src="img/configuracao.png" alt="Configurações" class="settings-icon">
      </button>
      <strong id="user-name">
        <?php echo $_SESSION['usuario_nome']; ?>
      </strong>
      <br>
      <span class="crm" id="user-crm">
        CRM: <?php echo $_SESSION['usuario_crm']; ?>
      </span>
    </div>
    <!-- <a href="Home.php">HOME</a> -->
    <a href="Diagnostico.php">DIAGNÓSTICO</a>
    <!-- <a href="Historico.php">HISTÓRICO</a> -->
    <!-- <a href="Anotacoes.php">ANOTAÇÕES</a> -->
    <a href="Pesquisa.php">PESQUISA</a>
    <a href="Perfil.php">PERFIL</a>
  </div>

  <script>
    const btn = document.getElementById("menu-btn");
    const sidebar = document.getElementById("sidebar");

    btn.addEventListener("click", () => {
      sidebar.classList.toggle("open");

      // muda entre hambúrguer e X
      btn.innerHTML = sidebar.classList.contains("open") ? "&#10005;" : "&#9776;";
    });

    function abrirSuporte() {
      window.location.href = 'Suporte.php';
    }

    document.addEventListener('click', (event) => {
      const isClickInsideSidebar = sidebar.contains(event.target);
      const isClickOnHamburger = btn.contains(event.target);
      
      if (!isClickInsideSidebar && !isClickOnHamburger && sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
        btn.innerHTML = "&#9776;"; // volta para hambúrguer
      }
    });
  </script>
</body>
</html>