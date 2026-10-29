<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home - UMed Webpage</title>
    <link rel="stylesheet" href="main.css"> 
</head>
<body>
    <div class="logout-container">
        <a href="logout.php" class="logout-button">Logout</a>
    </div>

    <div class="container">
        <h1>Welcome to UMed</h1>
        <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
        <p>You are logged in as <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>.</p>

        <div class="nav-buttons">
            <a href="upload.php" class="button">Upload Documents</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_voting.php" class="button">Manage Voting</a>
                <a href="admin_map.php" class="button">Manage Speakers</a>
          	<a href="generate_report.php" class="button">Download Report</a>
            <?php else: ?>
                <a href="user_votes.php" class="button">Vote on Proposals</a>
                <a href="request_speak.php" class="button">Request to Speak</a>
            <?php endif; ?>

            <a href="map.php" class="button">View Speaking Map</a>
        </div>
    </div>
</body>
</html>
