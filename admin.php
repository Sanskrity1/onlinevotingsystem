<?php
session_start();
include '../db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM admins WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (!password_verify($password, $row['password'])) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE admins SET password=? WHERE username=?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $hashed_password, $username);
            $updateStmt->execute();
            $row['password'] = $hashed_password;  
        }

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['contactno'] = $row['contactno'];

            $_SESSION['message'] = "✅ Login successful!";
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "❌ Invalid password!";
        }
    } else {
        $message = "❌ Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>

        <?php if (!empty($message)) { ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php } ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <p class="home-text"><a href="../home.html">Go Back To Home Page⬅️</a></p>
    </div>

</body>
</html>
