<?php
    include 'Navbar.php';
?>
    <link rel="stylesheet" href="css/pesquisa.css">
    <style>
        .sugestoes-container {
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            display: none;
        }
        .sugestao {
            padding: 8px;
            cursor: pointer;
        }
        .sugestao:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pesquisa-container">
            <h1>Pesquisar Doença</h1>
            
            <div class="pesquisa-input-container" style="position:relative;">
                <input type="text" id="doencaInput" placeholder="Digite o nome da doença...">
                <div class="sugestoes-container" id="sugestoesContainer"></div>
            </div>
            
            <div class="botoes-container">
                <button class="btn-pesquisar" onclick="pesquisarDoenca()" href="Informacoes.php?id=<?php echo $id; ?>&origem=pesquisa">Pesquisar</button>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById("doencaInput");
        const sugestoesContainer = document.getElementById("sugestoesContainer");

        // Mostra sugestões enquanto digita
        input.addEventListener("input", () => {
            const valor = input.value.toLowerCase();
            sugestoesContainer.innerHTML = "";

            if (!valor) {
                sugestoesContainer.style.display = "none";
                return;
            }

            const filtradas = sugestoes.filter(s =>
                s.toLowerCase().includes(valor)
            );

            if (filtradas.length > 0) {
                filtradas.forEach(s => {
                    const div = document.createElement("div");
                    div.classList.add("sugestao");
                    div.innerText = s;
                    div.onclick = () => {
                        input.value = s;
                        sugestoesContainer.style.display = "none";
                    };
                    sugestoesContainer.appendChild(div);
                });
                sugestoesContainer.style.display = "block";
            } else {
                sugestoesContainer.style.display = "none";
            }
        });

        // Função para pesquisar doença
        function pesquisarDoenca() {
            const doenca = input.value.trim();
            
            if (!doenca) {
                alert('Por favor, digite o nome de uma doença para pesquisar.');
                input.focus();
                return;
            }
            
            // Redireciona para a página de informações
            window.location.href = `Informacoes.php?doenca=${encodeURIComponent(doenca)}&origem=pesquisa`;
        }
    </script>
</body>
</html>
