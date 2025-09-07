<?php
require_once 'config.php';
verificarLogin();

$pdo = conectarBanco();
$erro = '';

// Buscar salas disponíveis
$stmt = $pdo->query("SELECT id, numero, descricao FROM salas WHERE disponivel = TRUE ORDER BY numero");
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processar agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sala_id = $_POST['sala_id'] ?? '';
    $data = $_POST['data'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fim = $_POST['hora_fim'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    
    // Validar dados
    if (empty($sala_id) || empty($data) || empty($hora_inicio) || empty($hora_fim) || empty($titulo)) {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        // Verificar conflito de horário
        $stmt = $pdo->prepare("
            SELECT * FROM agendamentos 
            WHERE sala_id = ? AND data = ? 
            AND (
                (hora_inicio <= ? AND hora_fim > ?) OR 
                (hora_inicio < ? AND hora_fim >= ?) OR
                (hora_inicio >= ? AND hora_fim <= ?)
            )
        ");
        $stmt->execute([$sala_id, $data, $hora_inicio, $hora_inicio, $hora_fim, $hora_fim, $hora_inicio, $hora_fim]);
        $conflitos = $stmt->fetchAll();
        
        if (count($conflitos) > 0) {
            $erro = "Conflito de horário! A sala já está reservada neste período.";
        } else {
            // Inserir agendamento
            $stmt = $pdo->prepare("
                INSERT INTO agendamentos (sala_id, usuario_id, titulo, descricao, data, hora_inicio, hora_fim) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            if ($stmt->execute([$sala_id, $_SESSION['usuario_id'], $titulo, $descricao, $data, $hora_inicio, $hora_fim])) {
                header("Location: agenda.php?success=1");
                exit();
            } else {
                $erro = "Erro ao agendar a sala. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Sala</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">Sistema de Agendamento</div>
        <ul class="navbar-menu">
            <li><span>Olá, <?php echo $_SESSION['usuario_nome']; ?></span></li>
            <li><a href="agenda.php">Agenda</a></li>
            <li><a href="agendar.php">Agendar Sala</a></li>
            <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
                <li><a href="admin.php">Administração</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <div class="agenda-box">
            <h2>Agendar Nova Sala</h2>
            
            <?php if (!empty($erro)): ?>
                <div class="erro"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="sala_id">Sala *</label>
                    <select id="sala_id" name="sala_id" required>
                        <option value="">Selecione uma sala</option>
                        <?php foreach ($salas as $sala): ?>
                            <option value="<?php echo $sala['id']; ?>" <?php echo isset($_POST['sala_id']) && $_POST['sala_id'] == $sala['id'] ? 'selected' : ''; ?>>
                                Sala <?php echo $sala['numero']; ?> - <?php echo $sala['descricao']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="data">Data *</label>
                    <input type="date" id="data" name="data" required 
                           value="<?php echo $_POST['data'] ?? ''; ?>"
                           min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="hora_inicio">Hora Início *</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required
                           value="<?php echo $_POST['hora_inicio'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="hora_fim">Hora Fim *</label>
                    <input type="time" id="hora_fim" name="hora_fim" required
                           value="<?php echo $_POST['hora_fim'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="titulo">Título do Evento *</label>
                    <input type="text" id="titulo" name="titulo" required
                           value="<?php echo $_POST['titulo'] ?? ''; ?>"
                           placeholder="Ex: Reunião de Planejamento">
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="3"
                              placeholder="Descrição do evento"><?php echo $_POST['descricao'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn">Agendar</button>
                    <a href="agenda.php" class="btn btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>