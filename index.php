<?php
require_once 'config.php';

// Redirecionar se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: agenda.php");
    exit();
}

// Processar login
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (!empty($email) && !empty($senha)) {
        $pdo = conectarBanco();
        
        // Buscar usuário no banco
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            
            header("Location: agenda.php");
            exit();
        } else {
            $erro = "E-mail ou senha incorretos!";
        }
    } else {
        $erro = "Por favor, preencha todos os campos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Agendamento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Login do Sistema</h2>
            
            <?php if (!empty($erro)): ?>
                <div class="erro"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required 
                           value="admin@escola.com">
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required 
                           value="123456">
                </div>
                
                <button type="submit">Entrar</button>
            </form>
            
            <div class="info-login">
                <p><strong>Credenciais para teste:</strong></p>
                <p>E-mail: admin@escola.com</p>
                <p>Senha: 123456</p>
            </div>
        </div>
    </div>
</body>
</html>