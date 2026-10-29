<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = require __DIR__ . '/config.php'; // see config.example.php
$host = $config['host'];
$db = $config['db'];
$user = $config['user'];
$pass = $config['pass'];
$charset = $config['charset'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
?>

