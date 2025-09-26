<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    echo "Não autorizado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $doenca = $_POST['doenca'] ?? '';
    $sintomas = $_POST['sintomas'] ?? '';

    if ($doenca && $sintomas) {
        $stmt = $pdo->prepare("INSERT INTO historico (usuario_id, doenca, sintomas) VALUES (:usuario_id, :doenca, :sintomas)");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'doenca' => $doenca,
            'sintomas' => $sintomas
        ]);
        echo "OK";
    } else {
        http_response_code(400);
        echo "Dados inválidos";
    }
}
?>
