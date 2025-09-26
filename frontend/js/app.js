// ==========================
// Verificar se está online/offline
// ==========================
window.addEventListener('online', function() {
  document.body.classList.remove('offline');
  showMessage('Conectado à internet', 'success');
});

window.addEventListener('offline', function() {
  document.body.classList.add('offline');
  showMessage('Você está offline', 'warning');
});

// ==========================
// Função para mostrar mensagens
// ==========================
function showMessage(message, type = 'info') {
  const existingMessage = document.getElementById('app-message');
  if (existingMessage) existingMessage.remove();

  const messageEl = document.createElement('div');
  messageEl.id = 'app-message';
  messageEl.textContent = message;
  messageEl.className = `message message-${type}`;

  messageEl.style.cssText = `
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 20px;
    border-radius: 4px;
    color: white;
    font-weight: bold;
    z-index: 1000;
    animation: fadeIn 0.3s ease-in;
  `;

  if (type === 'success') messageEl.style.backgroundColor = '#4CAF50';
  else if (type === 'warning') messageEl.style.backgroundColor = '#FF9800';
  else if (type === 'error') messageEl.style.backgroundColor = '#F44336';
  else messageEl.style.backgroundColor = '#2196F3';

  document.body.appendChild(messageEl);

  setTimeout(() => {
    if (messageEl.parentNode) messageEl.parentNode.removeChild(messageEl);
  }, 3000);
}

// ==========================
// Estilos adicionais
// ==========================
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeIn {
    from { opacity: 0; top: 0; }
    to { opacity: 1; top: 20px; }
  }
  .offline { filter: grayscale(1); }
`;
document.head.appendChild(style);

// ==========================
// Função para adicionar sintomas
// ==========================
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
  removerBtn.addEventListener('click', () => sintomasContainer.removeChild(sintomaTag));

  sintomaTag.appendChild(sintomaTexto);
  sintomaTag.appendChild(removerBtn);
  sintomasContainer.appendChild(sintomaTag);
}

// Captura tecla espaço no input
document.getElementById('sintomaInput').addEventListener('keyup', function(e) {
  if (e.key === ' ' || e.key === 'Spacebar') {
    const texto = this.value.trim();
    if (texto) {
      adicionarSintoma(texto);
      this.value = '';
    }
  }
});

// ==========================
// Função para chamar a LLM e mostrar diagnósticos
// ==========================
async function mostrarDiagnosticos() {
  const sintomas = Array.from(document.querySelectorAll('.sintoma-tag span'))
                        .map(span => span.textContent);

  if (sintomas.length === 0) {
    alert("Digite pelo menos um sintoma!");
    return;
  }

  document.getElementById('doctorImage').style.display = 'none';
  document.getElementById('mainEnviarBtn').style.display = 'none';
  document.getElementById('carregando').style.display = 'block';

  try {
    // Aqui é o prompt seguro para a LLM
    const prompt = `
      Baseado nos seguintes sintomas: ${sintomas.join(", ")}.
      Liste apenas doenças médicas relevantes, sem outros tópicos ou informações irrelevantes.
      Retorne cada doença em uma linha separada, de forma clara e objetiva.
    `;

    const response = await fetch("http://127.0.0.1:5000/respostas-llm", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ pergunta: prompt })
    });

    const data = await response.json();
    console.log("Resposta LLM:", data);

    const lista = document.getElementById("diagnosticosLista");
    lista.innerHTML = "";

    const doencas = data.resposta
                        .split("\n")
                        .filter(linha => linha.trim() && !linha.includes("Doenças relacionadas"));

    doencas.forEach((doenca, index) => {
      const btn = document.createElement("button");
      btn.className = index === 0 ? "diagnostico-btn principal" : "diagnostico-btn";
      btn.innerHTML = `<span class="doenca-nome">${doenca.replace("*", "").trim()}</span>`;
      btn.onclick = () => selecionarDiagnostico(doenca.trim());
      lista.appendChild(btn);
    });

    document.getElementById('diagnosticosContainer').style.display = 'block';

  } catch (error) {
    console.error("Erro ao buscar diagnósticos:", error);
    alert("Erro ao conectar com o servidor.");
  } finally {
    document.getElementById('carregando').style.display = 'none';
  }
}

// ==========================
// Redirecionar para página de informações
// ==========================
function selecionarDiagnostico(doenca) {
  const sintomas = Array.from(document.querySelectorAll('.sintoma-tag span'))
                        .map(span => encodeURIComponent(span.textContent)).join(',');
  window.location.href = `Informacoes.php?doenca=${encodeURIComponent(doenca)}&sintomas=${sintomas}`;
}

// ==========================
// Configurar botões de envio
// ==========================
document.querySelector('.enviar-btn').addEventListener('click', mostrarDiagnosticos);
document.querySelector('.enviar-btn-mobile').addEventListener('click', mostrarDiagnosticos);
