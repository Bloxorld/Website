<?php
$host = "sql210.infinityfree.com";
$db = "if0_39718043_bloxorld_users";
$user = "if0_39718043";
$pass = "q39cqh64KXHNt";

// Connect to db
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

header("Content-Type: application/json");

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "loggedIn" => true,
        "username" => $_SESSION['username'],
        "admin" => $_SESSION['admin']
    ]);
} else {
    echo json_encode([
        "loggedIn" => false
    ]);
}
?>
