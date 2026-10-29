<?php
session_start();
require 'db.php'; // Database connection

// Verify if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle the creation of a new voting session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voting_title'])) {
    $voting_title = $_POST['voting_title'];

    // Insert a new voting session into the database
    $stmt = $pdo->prepare("INSERT INTO voting_sessions (title, status) VALUES (:title, 'open')");
    $stmt->execute(['title' => $voting_title]);

    echo "New voting session created: " . htmlspecialchars($voting_title);
}

// Fetch the current voting session and votes
$stmt = $pdo->prepare("SELECT * FROM voting_sessions WHERE status = 'open' ORDER BY id DESC LIMIT 1");
$stmt->execute();
$current_voting = $stmt->fetch();

$voting_id = $current_voting['id'] ?? null;

if ($voting_id) {
    // Fetch all votes for the current session
    $votes_stmt = $pdo->prepare("SELECT users.username, votes.vote 
                                 FROM votes 
                                 INNER JOIN users ON votes.user_id = users.user_id 
                                 WHERE votes.session_id = :session_id");
    $votes_stmt->execute(['session_id' => $voting_id]);
    $votes = $votes_stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Voting Page</title>
    <style>
        .yes { color: green; font-weight: bold; }
        .no { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Admin Voting Management</h1>

    <!-- Voting session creation form -->
    <form method="POST" action="admin_voting.php">
        <label for="voting_title">Enter Voting Title:</label>
        <input type="text" name="voting_title" id="voting_title" required>
        <button type="submit">Start New Vote</button>
    </form>

    <?php if ($current_voting): ?>
        <h2>Current Voting Session: <?php echo htmlspecialchars($current_voting['title']); ?></h2>
        
        <h3>Live Voting Status</h3>
        <ul>
            <?php foreach ($votes as $vote): ?>
                <li>
                    <?php echo htmlspecialchars($vote['username']); ?> voted: 
                    <span class="<?php echo $vote['vote'] === 'yes' ? 'yes' : 'no'; ?>">
                        <?php echo $vote['vote'] === 'yes' ? 'Yes' : 'No'; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No active voting session. Start a new one above.</p>
    <?php endif; ?>
</body>
</html>
