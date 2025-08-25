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
$username   = trim($_POST['username']);
$password   = trim($_POST['password']);
$admin_code = trim($_POST['admin_code']);
$admin      = 0;

if (empty($username) || empty($password)) {
    die("Please fill in all required fields.");
}

// is user already made
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Username already taken. Please choose another.";
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();

// hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, Admin) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $username, $hashed_password, $admin);

if ($stmt->execute()) {
    $newUserId = $conn->insert_id;
    $stmt->close();
    $description = "Welcome to Bloxorld!";
    $online      = 0;
    $lastSeen    = date("Y-m-d H:i:s");
    $status      = ""; 
    $stmt = $conn->prepare("INSERT INTO profiles (Description, Online, LastSeen, Status, user) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $description, $online, $lastSeen, $status, $newUserId);
    if($stmt->execute()) {
        echo "Account created successfully!<br>";
        if ($admin === 1) {
            echo "You are registered as an <b>Admin</b>.<br>";
        }
        echo "<a href='login.html'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
