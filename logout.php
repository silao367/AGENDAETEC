<?php
require_once 'config.php';

// Limpar sessão
session_unset();
session_destroy();

// Redirecionar para login
header("Location: index.php");
exit();