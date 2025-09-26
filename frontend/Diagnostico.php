<?php
include 'Navbar.php';
?>
<link rel="stylesheet" href="css/diagnostico.css">
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <h1>Digite os Sintomas:</h1>

            <div class="input-container">
                <textarea id="sintomaInput" placeholder="Digite os sintomas aqui"></textarea>
                <div class="sintomas-container" id="sintomasContainer"></div>
            </div>

            <p class="instrucoes">Digite os sintomas e pressione **Enter** para adicionar cada um</p>
            <button class="enviar-btn-mobile" onclick="mostrarDiagnosticos()">Enviar</button>

            <div id="resultadoDiagnostico" class="resultado-container" style="display: none;">
                <h2>Resultado do Diagnóstico</h2>
                <div id="diagnosticoConteudo"></div>
                <button id="novoDiagnosticoBtn" class="enviar-btn" onclick="voltarParaSintomas()">Novo
                    Diagnóstico</button>
            </div>

            <div id="carregando" style="display: none;">
                <p>Analisando sintomas...</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="doctor-image" id="doctorImage">
                <img src="img/doutor.png" alt="Imagem do Doutor" class="doutor-img">
            </div>
            <button class="enviar-btn" id="mainEnviarBtn" onclick="mostrarDiagnosticos()">Enviar</button>

            <div id="diagnosticosContainer" style="display: none;">
                <h2>Possíveis Diagnósticos:</h2>
                <div class="diagnosticos-lista" id="diagnosticosLista"></div>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
    <script>
        function adicionarSintoma(texto) {
            if (!texto.trim()) return;
            const sintomasContainer = document.getElementById('sintomasContainer');
            const sintomaTag = document.createElement('div');
            sintomaTag.className = 'sintoma-tag';
            const sintomaTexto = document.createElement('span');
            sintomaTexto.textContent = texto;
            const removerBtn = document.createElement('button');
            removerBtn.className = 'remover';
            removerBtn.innerHTML = '&times;';
            removerBtn.addEventListener('click', function () {
                sintomasContainer.removeChild(sintomaTag);
            });
            sintomaTag.appendChild(sintomaTexto);
            sintomaTag.appendChild(removerBtn);
            sintomasContainer.appendChild(sintomaTag);
        }

        document.getElementById('sintomaInput').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const texto = this.value.trim();
                if (texto) {
                    adicionarSintoma(texto);
                    this.value = '';
                }
            }
        });

        async function mostrarDiagnosticos() {
            const sintomas = Array.from(document.querySelectorAll('.sintoma-tag span')).map(span => span.textContent);
            if (sintomas.length === 0) {
                alert("Digite pelo menos um sintoma!");
                return;
            }
            document.getElementById('doctorImage').style.display = 'none';
            document.getElementById('mainEnviarBtn').style.display = 'none';
            document.getElementById('carregando').style.display = 'block';
            document.getElementById('diagnosticosContainer').style.display = 'block';

            try {
                const response = await fetch("http://127.0.0.1:8000/respostas-llm", {
                    method: "POST",
                    headers: { "Content-Type": "application/json; charset=utf-8" },
                    body: JSON.stringify({ pergunta: sintomas.join(", ") })
                });

                if (!response.ok) throw new Error(`Erro HTTP ${response.status}`);
                const data = await response.json();
                if (!data.resposta) {
                    alert("O servidor não retornou nenhuma resposta.");
                    return;
                }

                const lista = document.getElementById("diagnosticosLista");
                lista.innerHTML = "";
                const doencas = data.resposta.split("\n").filter(linha => linha.trim() && !linha.includes("Doenças relacionadas"));

                doencas.forEach((doenca, index) => {
                    const btn = document.createElement("button");
                    btn.className = index === 0 ? "diagnostico-btn principal" : "diagnostico-btn";
                    btn.innerHTML = `<span class="doenca-nome">${doenca.replace("*", "").trim()}</span>`;
                    btn.onclick = () => selecionarDiagnostico(doenca.trim());
                    lista.appendChild(btn);
                });
            } catch (error) {
                console.error("Erro ao buscar diagnósticos:", error);
                alert("Erro ao conectar com o servidor. Verifique se o Flask está rodando.");
            } finally {
                document.getElementById('carregando').style.display = 'none';
            }
        }

        function selecionarDiagnostico(doenca) {
            const sintomas = Array.from(document.querySelectorAll('.sintoma-tag span'))
                .map(span => encodeURIComponent(span.textContent))
                .join(',');
            window.location.href = `Informacoes.php?doenca=${encodeURIComponent(doenca)}&sintomas=${sintomas}`;
        }

        document.querySelectorAll('.enviar-btn').forEach(btn => {
            btn.addEventListener('click', mostrarDiagnosticos);
        });
        document.querySelectorAll('.enviar-btn-mobile').forEach(btn => {
            btn.addEventListener('click', mostrarDiagnosticos);
        });
    </script>
</body>

</html>
