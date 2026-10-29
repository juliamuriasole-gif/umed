<?php
session_start();
require 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $country_name = $_POST['country'];  

    try {
        $stmt = $pdo->prepare("SELECT country_id FROM countries WHERE country_name = :country_name");
        $stmt->execute(['country_name' => $country_name]);
        $countryRow = $stmt->fetch();

        if (!$countryRow) {
            die("Error: Selected country does not exist.");
        }

        $country_id = $countryRow['country_id'];

        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, country_id) VALUES (:username, :password, :role, :country_id)");
        $stmt->execute([
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'country_id' => $country_id
        ]);

        header("Location: login.php?registered=1");
        exit;
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT country_id, country_name FROM countries");
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UMed Webpage</title>
    <link rel="stylesheet" href="register.css">  <!-- Link the CSS -->
</head>
<body>
    <div class="container">  <!-- Form container for styling -->
        <h1>Register for UMed</h1>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <label for="role">Select Role:</label>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>

            <label for="country">Select Country:</label>
            <select name="country" required>
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo htmlspecialchars($country['country_name']); ?>">
                        <?php echo htmlspecialchars($country['country_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
