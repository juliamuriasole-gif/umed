<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM voting_sessions WHERE is_active = 1");
$stmt->execute();
$voting = $stmt->fetch();

if (!$voting) {
    echo "There's not active voting session at the moment.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM votes WHERE session_id = :session_id AND user_id = :user_id");
$stmt->execute(['session_id' => $voting['id'], 'user_id' => $_SESSION['user_id']]);
$userVote = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    $vote = $_POST['vote'];

    if (!$userVote) {
        $stmt = $pdo->prepare("INSERT INTO votes (session_id, user_id, vote) VALUES (:session_id, :user_id, :vote)");
        $stmt->execute(['session_id' => $voting['id'], 'user_id' => $_SESSION['user_id'], 'vote' => $vote]);
        echo "Vote registered successfully.";
    } else {
        echo "You have already voted in this session.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Votación</title>
</head>
<body>
    <h1>Votación: <?php echo htmlspecialchars($voting['title']); ?></h1>

    <form method="POST">
        <label>
            <input type="radio" name="vote" value="Sí" <?php echo ($userVote['vote'] == 'Sí' ? 'checked' : ''); ?>> Sí
        </label>
        <br>
        <label>
            <input type="radio" name="vote" value="No" <?php echo ($userVote['vote'] == 'No' ? 'checked' : ''); ?>> No
        </label>
        <br>
        <button type="submit">Votar</button>
    </form>

    <p><a href="upload.php">Volver al panel de documentos</a></p>
</body>
</html>
