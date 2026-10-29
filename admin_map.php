<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['country'])) {
    $selected_country = $_POST['country'];

    try {
        $reset_stmt = $pdo->prepare("UPDATE speaking_map SET status = 'inactive'");
        $reset_stmt->execute();

        $update_stmt = $pdo->prepare("UPDATE speaking_map SET status = 'speaking' WHERE country = :country");
        $update_stmt->execute(['country' => $selected_country]);

        $delete_request = $pdo->prepare("DELETE FROM requests WHERE country_id = (SELECT country_id FROM countries WHERE country_name = :country)");
        $delete_request->execute(['country' => $selected_country]);

        $message = "Country updated to speaking!";
    } catch (PDOException $e) {
        $message = "Error updating country: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT country FROM speaking_map WHERE status = 'speaking' LIMIT 1");
$stmt->execute();
$speaking_country = $stmt->fetchColumn();

$request_stmt = $pdo->query("SELECT c.country_name FROM requests r JOIN countries c ON r.country_id = c.country_id WHERE r.status = 'requested'");
$requested_countries = $request_stmt->fetchAll(PDO::FETCH_COLUMN);

$stmt = $pdo->query("SELECT country FROM speaking_map");
$countries = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Map Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; text-align: center; }
        h1 { margin-bottom: 20px; }
        .message { margin-bottom: 20px; font-size: 18px; color: green; }
        .current-speaking { font-size: 20px; font-weight: bold; color: #333; margin-top: 20px; }
        select, button { padding: 8px; margin: 10px; }
        button { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .requests { margin-top: 20px; padding: 10px; border: 1px solid #ddd; display: inline-block; }
    </style>
</head>
<body>
    <h1>Admin Map Management</h1>

    <?php if (isset($message)): ?>
        <div class="message"> <?php echo htmlspecialchars($message); ?> </div>
    <?php endif; ?>

    <form method="POST">
        <label for="country">Select Country Speaking:</label>
        <select name="country" id="country">
            <?php foreach ($countries as $country): ?>
                <option value="<?php echo htmlspecialchars($country); ?>">
                    <?php echo htmlspecialchars($country); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Set as Speaking</button>
    </form>

    <div class="current-speaking">
        <?php echo $speaking_country ? "The country that is currently speaking is: " . htmlspecialchars($speaking_country) : "No country is currently speaking."; ?>
    </div>

    <div class="requests">
        <h2>Countries Requesting to Speak</h2>
        <?php if (!empty($requested_countries)): ?>
            <ul>
                <?php foreach ($requested_countries as $country): ?>
                    <li><?php echo htmlspecialchars($country); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No countries have requested to speak.</p>
        <?php endif; ?>
<h2>Set Timer</h2>
<label for="minutes">Minutes:</label>
<input type="number" id="minutes" min="0" value="0">
<label for="seconds">Seconds:</label>
<input type="number" id="seconds" min="0" max="59" value="0">
<button onclick="startTimer()">Start</button>

<h3>Time Remaining: <span id="timer-display">00:00</span></h3>


    </div>
<script>
let timer;
let totalSeconds;

function startTimer() {
    let minutes = parseInt(document.getElementById("minutes").value);
    let seconds = parseInt(document.getElementById("seconds").value);

    totalSeconds = minutes * 60 + seconds;
    
    if (totalSeconds > 0) {
        clearInterval(timer);  
        updateDisplay();
        timer = setInterval(countdown, 1000);
    }
}

function countdown() {
    if (totalSeconds <= 0) {
        clearInterval(timer);
        alert("Time is up!");
        return;
    }

    totalSeconds--;
    updateDisplay();
}

function updateDisplay() {
    let mins = Math.floor(totalSeconds / 60);
    let secs = totalSeconds % 60;
    document.getElementById("timer-display").textContent = 
        String(mins).padStart(2, '0') + ":" + String(secs).padStart(2, '0');
}
</script>

</body>
</html>
