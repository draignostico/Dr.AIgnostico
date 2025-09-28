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
        <img src="img/configuracao.png" alt="Configurações" class="settings-icon" style="width: 16px; height: 16px;">
      </button>
      <strong id="user-name">Evellyn Furtado</strong>
      <span class="crm" id="user-crm">CRM/SP 123456</span>
    </div>
    <a href="Home.php">HOME</a>
    <a href="Diagnostico.php">DIAGNÓSTICO</a>
    <!-- <a href="Historico.php">HISTÓRICO</a> -->
    <!-- <a href="Anotacoes.php">ANOTAÇÕES</a> -->
    <a href="Pesquisa.php">PESQUISA</a>
    <a href="Perfil.php">PERFIL</a>
  </div>

  <script>
    // Variáveis que virão do banco de dados 
    const userName = "Evellyn Furtado"; 
    const userCrm = "CRM/SP 123456"; 
    
    // Atualiza os elementos com os dados do usuário
    document.getElementById("user-name").textContent = userName;
    document.getElementById("user-crm").textContent = userCrm;

    const btn = document.getElementById("menu-btn");
    const sidebar = document.getElementById("sidebar");

    btn.addEventListener("click", () => {
      sidebar.classList.toggle("open");

      // muda entre hambúrguer e X
      if (sidebar.classList.contains("open")) {
        btn.innerHTML = "&#10005;";
      } else {
        btn.innerHTML = "&#9776;"; 
      }
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