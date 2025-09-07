<?php
require_once 'config.php';
verificarLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    
    if ($id) {
        $pdo = conectarBanco();
        
        // Verificar se o usuário tem permissão para cancelar
        $stmt = $pdo->prepare("SELECT usuario_id FROM agendamentos WHERE id = ?");
        $stmt->execute([$id]);
        $agendamento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($agendamento && ($_SESSION['usuario_id'] == $agendamento['usuario_id'] || $_SESSION['usuario_tipo'] === 'admin')) {
            // Cancelar agendamento
            $stmt = $pdo->prepare("DELETE FROM agendamentos WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
}

header("Location: agenda.php");
exit();