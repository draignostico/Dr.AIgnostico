<?php
    include 'Navbar.php';
?>
<link rel="stylesheet" href="css/suporte.css?v=<?php echo time(); ?>">
</head>
<body>
  <main class="container">
    <!-- Mascote -->
    <div class="mascote" id="mascote">
      <img src="img/doutor.png" alt="Mascote do Dr AIgnóstico" class="robot-img">
    </div>

    <!-- Formulário -->
    <div class="form-box">
      <h2>Suporte Técnico</h2>
      <form>
        <label>Seu nome:</label>
        <input type="text" placeholder="Digite seu nome">

        <label>E-mail:</label>
        <input type="email" placeholder="Digite seu e-mail">

        <div class="row">
          <div>
            <label>CRM:</label>
            <input type="text" placeholder="CRM">
          </div>
          <div>
            <label>Telefone:</label>
            <input type="tel" placeholder="(XX) XXXXX-XXXX">
          </div>
        </div>

        <label>Descrição do Problema:</label>
        <textarea placeholder="Digite aqui..."></textarea>

        <button type="submit" id="btn">Enviar Solicitação</button>
      </form>
    </div>
  </main>

  <!-- SCRIPT DE EMERGÊNCIA PARA ESCONDER O MASCOTE -->
  <script>
    // Verifica se a tela é menor que 768px
    function checkScreenSize() {
      if (window.innerWidth <= 768) {
        document.getElementById('mascote').style.display = 'none';
      } else {
        document.getElementById('mascote').style.display = 'flex';
      }
    }
    
    // Executa quando a página carrega e quando redimensiona
    window.addEventListener('load', checkScreenSize);
    window.addEventListener('resize', checkScreenSize);
  </script>
</body>
</html>