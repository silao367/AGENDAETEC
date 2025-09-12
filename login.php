<?php
session_start();

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

// Buscar usuário
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $senharecebida=password_hash($senha, PASSWORD_DEFAULT);
    echo "$senharecebida $senha";
    // Verificar senha 
    if (password_verify($senha, $senharecebida) ) {
        // Criar sessão
        $_SESSION['usuario'] = $user['username'];
        $_SESSION['logado'] = true;
        $_SESSION['tipo'] =$user['tipo'];

        // Redirecionar para página protegida
        header("Location: protegido.php");
        exit();
    } else {
        echo "<h2>Senha incorreta</h2>";
    }
} else {
    echo "<h2>Usuário não encontrado </h2>";
}

$stmt->close();
$conn->close();
?>
