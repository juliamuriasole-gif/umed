<?php
session_start();
require 'db.php';

$stmt = $pdo->prepare("
    SELECT votes.vote, users.username 
    FROM votes 
    JOIN users ON votes.user_id = users.user_id
    JOIN voting_sessions ON votes.session_id = voting_sessions.id
    WHERE voting_sessions.is_active = 1
");
$stmt->execute();
$votes = $stmt->fetchAll();

foreach ($votes as $vote) {
    $color = $vote['vote'] == 'yes' ? 'green' : 'red';
    echo "<p style='color: $color;'>{$vote['username']}: " . strtoupper($vote['vote']) . "</p>";
}
?>
