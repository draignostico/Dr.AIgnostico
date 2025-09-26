<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações sobre Doença</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/informacoes.css">
</head>
<body>
    <a href="Diagnostico.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i>
    </a>
    
    <header>
        <h1 id="tituloDoenca">Carregando...</h1>
        <div class="info-box" id="descricao"></div>
    </header>
    
    <section>
        <h2>Sintomas:</h2>
        <ul id="sintomas"></ul>
    </section>
    
    <section>
        <h2>Tratamentos:</h2>
        <ul id="tratamentos"></ul>
    </section>

    <section>
        <p id="aviso" style="color:red; font-weight:bold;"></p>
    </section>

    <script>
        // pega o nome da doença pela URL (ex: informacoes.php?doenca=Dengue)
        const params = new URLSearchParams(window.location.search);
        const doenca = params.get("doenca") || "Dengue";

        // Atualiza título da página
        document.title = `Informações sobre ${doenca}`;
        document.getElementById("tituloDoenca").innerText = doenca;

        // Busca informações da API já estruturada
        fetch(`http://127.0.0.1:8000/doenca/${doenca}`)
        .then(res => res.json())
        .then(data => {
            // Preenche descrição
            document.getElementById("descricao").innerText = data.descricao || "Descrição não disponível.";

            // Preenche sintomas
            const sintomasList = document.getElementById("sintomas");
            sintomasList.innerHTML = "";
            if (data.sintomas && data.sintomas.length > 0) {
                data.sintomas.forEach(s => {
                    const li = document.createElement("li");
                    li.innerText = s;
                    sintomasList.appendChild(li);
                });
            } else {
                sintomasList.innerHTML = "<li>Não informado</li>";
            }

            // Preenche tratamentos
            const tratamentosList = document.getElementById("tratamentos");
            tratamentosList.innerHTML = "";
            if (data.tratamentos && data.tratamentos.length > 0) {
                data.tratamentos.forEach(t => {
                    const li = document.createElement("li");
                    li.innerText = t;
                    tratamentosList.appendChild(li);
                });
            } else {
                tratamentosList.innerHTML = "<li>Não informado</li>";
            }

            // Preenche aviso (se existir)
            if (data.aviso) {
                document.getElementById("aviso").innerText = data.aviso;
            }
        })
        .catch(err => {
            document.getElementById("descricao").innerText = "Erro ao carregar dados.";
            console.error(err);
        });
    </script>
</body>
</html>
