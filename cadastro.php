<?php
// Conexão
$servername = "localhost:3306";
$username_db = "root";
$password_db = "";
$dbname = "aula_login";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$username = $_POST['username'];
$senha = $_POST['senha'];
$tipo = $_POST['tipo'];

// Verificar se o usuário já existe
$sql_check = "SELECT * FROM usuarios WHERE username = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $username);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Usuário já existe
    echo "<!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>Erro no Cadastro</title>
        <link rel='stylesheet' href='cadastro.html'>
    </head>
    <body>
        <div class='header'>
            <h1>Agenda de Salas ETEC</h1>
        </div>
        
        <div class='cadastro-container'>
            <div class='msg erro'>Erro: Usuário já existe!</div>
            <a href='cadastro.html' class='voltar'>Tentar novamente</a>
            <a href='login.html' class='voltar'>Voltar para Login</a>
        </div>
    </body>
    </html>";
} else {
    // Criptografar a senha
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir novo usuário
    $sql_insert = "INSERT INTO usuarios (username, senha, tipo) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sss", $username, $senha_criptografada, $tipo);
    
    if ($stmt_insert->execute()) {
        echo "<!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <title>Cadastro Realizado</title>
            <link rel='stylesheet' href='cadastro.html'>
        </head>
        <body>
            <div class='header'>
                <h1>Agenda de Salas ETEC</h1>
            </div>
            
            <div class='cadastro-container'>
                <div class='msg sucesso'>Cadastro realizado com sucesso!</div>
                <a href='login.html' class='voltar'>Fazer Login</a>
            </div>
        </body>
        </html>";
    } else {
        echo "<!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <title>Erro no Cadastro</title>
            <link rel='stylesheet' href='cadastro.html'>
        </head>
        <body>
            <div class='header'>
                <h1>Agenda de Salas ETEC</h1>
            </div>
            
            <div class='cadastro-container'>
                <div class='msg erro'>Erro ao cadastrar usuário: " . $conn->error . "</div>
                <a href='cadastro.html' class='voltar'>Tentar novamente</a>
                <a href='login.html' class='voltar'>Voltar para Login</a>
            </div>
        </body>
        </html>";
    }
    
    $stmt_insert->close();
}

$stmt_check->close();
$conn->close();
?>