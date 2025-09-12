<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Página Protegida</title>
</head>
<body>
    <h2>Bem-vindo, $_SESSION['tipo'] <?php echo htmlspecialchars($_SESSION['usuario']); ?> 🎉</h2>
    <p>Você está logado com sucesso!</p>
    <a href="logout.php">Sair</a>
</body>
</html>
