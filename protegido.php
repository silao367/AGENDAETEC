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
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .menu {
            margin: 20px 0;
        }
        .menu a {
            display: inline-block;
            background-color: #ff0000;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .menu a:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="welcome">
        <h2>Bem-vindo, <?php echo $_SESSION['tipo'] . ' ' . htmlspecialchars($_SESSION['usuario']); ?> 🎉</h2>
        <p>Você está logado com sucesso!</p>
    </div>
    
    <div class="menu">
        <a href="cadastro.html">Cadastrar Novo Usuário</a>
        <a href="logout.php">Sair</a>
    </div>
</body>
</html>