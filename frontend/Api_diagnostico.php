<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $sintomas = $dados['sintomas'] ?? [];
    
    // Aqui você integrará com sua LLM + RAG
    $diagnosticos = obterDiagnosticosLLM($sintomas);
    
    echo json_encode([
        'success' => true,
        'diagnosticos' => $diagnosticos
    ]);
}

function obterDiagnosticosLLM($sintomas) {
    // Implemente a lógica de chamada para sua LLM aqui
    // Retorne um array ordenado por probabilidade
    
    // Exemplo de retorno (substitua pela sua integração real):
    return [
        ['doenca' => 'Dengue', 'probabilidade' => 85],
        ['doenca' => 'Coronavírus (COVID-19)', 'probabilidade' => 70],
        ['doenca' => 'Gripe', 'probabilidade' => 65],
        ['doenca' => 'Resfriado', 'probabilidade' => 45],
        ['doenca' => 'Febre recorrente', 'probabilidade' => 30]
    ];
}
?>