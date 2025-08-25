<?php
$host = "sql210.infinityfree.com"; 
$db   = "if0_39718043_bloxorld_users";
$user = "if0_39718043";
$pass = "q39cqh64KXHNt";

// connect to db
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// params
$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (empty($username) || empty($password)) {
    die("Please fill in all required fields.");
}

// check user
$stmt = $conn->prepare("SELECT id, password, Admin FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "Invalid username or password.";
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->bind_result($id, $hashed_password, $is_admin);
$stmt->fetch();

if (password_verify($password, $hashed_password)) {
    // login success
    session_start();
    $_SESSION['user_id']  = $id;
    $_SESSION['username'] = $username;
    $_SESSION['admin']    = $is_admin;

    echo "Login successful!<br>";
    if ($is_admin == 1) {
        echo "Welcome, <b>Admin</b> $username!<br>";
    } else {
        echo "Welcome, $username!<br>";
    }
} else {
    echo "Invalid username or password.";
}

$stmt->close();
$conn->close();
?>
