<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM voting_sessions WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1");
$stmt->execute();
$session = $stmt->fetch();

if (!$session) {
    echo "No hay votaciones activas.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    $vote = $_POST['vote'];
    $stmt = $pdo->prepare("INSERT INTO votes (session_id, user_id, vote) VALUES (:session_id, :user_id, :vote)
                           ON DUPLICATE KEY UPDATE vote = :vote");
    $stmt->execute(['session_id' => $session['id'], 'user_id' => $_SESSION['user_id'], 'vote' => $vote]);
    echo "Voto registrado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votación</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($session['title']); ?></h1>
        <form method="POST" class="vote-buttons">
            <button type="submit" name="vote" value="yes" class="yes-button">Yes</button>
            <button type="submit" name="vote" value="no" class="no-button">No</button>
        </form>
    </div>
</body>
</html>
