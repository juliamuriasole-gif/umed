<?php

session_start();
require 'db.php';

// Debugging: Check if session has the necessary values
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in.");
}

// Fetch country_id if it's not already in the session
if (!isset($_SESSION['country_id'])) {
    $stmt = $pdo->prepare("SELECT country_id FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !empty($user['country_id'])) {
        $_SESSION['country_id'] = $user['country_id'];
    } else {
        die("Error: You must be assigned a country.");
    }
}

$user_id = $_SESSION['user_id'];
$country_id = $_SESSION['country_id'];

// Check if the user has already requested to speak
$stmt = $pdo->prepare("SELECT * FROM requests WHERE user_id = :user_id AND status = 'requested'");
$stmt->execute(['user_id' => $user_id]);
$request = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$request) {
    try {
        $stmt = $pdo->prepare("INSERT INTO requests (user_id, country_id, status) VALUES (:user_id, :country_id, 'requested')");
        $stmt->execute(['user_id' => $user_id, 'country_id' => $country_id]);
        echo "Your request to speak has been submitted.";
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}

// Fetch all requests
$stmt = $pdo->query("SELECT users.username, countries.country_name, requests.status 
                     FROM requests 
                     JOIN users ON requests.user_id = users.user_id 
                     JOIN countries ON requests.country_id = countries.country_id 
                     ORDER BY requests.id DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request to Speak</title>
</head>
<body>
    <h1>Request to Speak</h1>

    <?php if ($request): ?>
        <p style="color: green;">You have already requested to speak.</p>
    <?php else: ?>
        <form action="request_speak.php" method="post">
            <button type="submit">Request to Speak</button>
        </form>
    <?php endif; ?>

    <h2>Current Requests</h2>
    <ul>
        <?php foreach ($requests as $req): ?>
            <li><?php echo htmlspecialchars($req['username']) . " (" . htmlspecialchars($req['country_name']) . ") - " . htmlspecialchars($req['status']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
