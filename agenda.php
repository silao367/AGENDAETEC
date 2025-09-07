<?php
require_once 'config.php';
verificarLogin();

$pdo = conectarBanco();

// Filtrar por sala se solicitado
$salaFiltro = $_GET['sala'] ?? 'todas';

// Buscar agendamentos
if ($salaFiltro === 'todas') {
    $stmt = $pdo->prepare("
        SELECT a.*, s.numero as sala_numero, u.nome as usuario_nome 
        FROM agendamentos a 
        INNER JOIN salas s ON a.sala_id = s.id 
        INNER JOIN usuarios u ON a.usuario_id = u.id 
        ORDER BY a.data, a.hora_inicio
    ");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("
        SELECT a.*, s.numero as sala_numero, u.nome as usuario_nome 
        FROM agendamentos a 
        INNER JOIN salas s ON a.sala_id = s.id 
        INNER JOIN usuarios u ON a.usuario_id = u.id 
        WHERE s.numero = ? 
        ORDER BY a.data, a.hora_inicio
    ");
    $stmt->execute([$salaFiltro]);
}

$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar salas para o filtro
$stmt = $pdo->query("SELECT numero FROM salas ORDER BY numero");
$salas = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Salas</title>
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
            <div class="agenda-header">
                <h2>Agenda de Salas</h2>
                <div class="agenda-date"><?php echo date('d/m/Y'); ?></div>
            </div>
            
            <!-- Filtro de salas -->
            <div class="filtro-salas">
                <form method="GET">
                    <label for="sala">Filtrar por sala:</label>
                    <select id="sala" name="sala" onchange="this.form.submit()">
                        <option value="todas" <?php echo $salaFiltro === 'todas' ? 'selected' : ''; ?>>Todas as salas</option>
                        <?php foreach ($salas as $sala): ?>
                            <option value="<?php echo $sala; ?>" <?php echo $salaFiltro === $sala ? 'selected' : ''; ?>>
                                Sala <?php echo $sala; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            
            <div class="agenda-controls">
                <a href="agendar.php" class="btn">Agendar Sala</a>
            </div>
            
            <div class="eventos-lista">
                <?php if (count($agendamentos) > 0): ?>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <div class="evento">
                            <div class="evento-titulo"><?php echo htmlspecialchars($agendamento['titulo']); ?></div>
                            <div class="evento-info">
                                <span class="evento-sala">Sala <?php echo $agendamento['sala_numero']; ?></span>
                                <span class="evento-data"><?php echo date('d/m/Y', strtotime($agendamento['data'])); ?></span>
                                <span class="evento-horario"><?php echo $agendamento['hora_inicio']; ?> - <?php echo $agendamento['hora_fim']; ?></span>
                            </div>
                            <div class="evento-descricao"><?php echo htmlspecialchars($agendamento['descricao']); ?></div>
                            <div class="evento-responsavel">Responsável: <?php echo htmlspecialchars($agendamento['usuario_nome']); ?></div>
                            
                            <?php if ($_SESSION['usuario_id'] == $agendamento['usuario_id'] || $_SESSION['usuario_tipo'] === 'admin'): ?>
                                <form action="cancelar.php" method="POST" class="evento-actions">
                                    <input type="hidden" name="id" value="<?php echo $agendamento['id']; ?>">
                                    <button type="submit" class="btn btn-remover">Cancelar Reserva</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="evento">
                        <div class="evento-titulo">Nenhum agendamento encontrado</div>
                        <div class="evento-descricao">Não há reservas para a sala selecionada.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>