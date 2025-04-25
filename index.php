<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];
    $citizenship_no = trim($_POST['citizenship_no']);
    $province = $_POST['province'];

    if ($role == "candidate") {
        $query = "SELECT * FROM candidates WHERE username=?";
    } else {
        $query = "SELECT * FROM voters WHERE username=?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            if ($citizenship_no != $row['citizenship_no'] || $province != $row['province']) {
                $message = "❌ Citizenship number or province does not match the registered information!";
            } else {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['province'] = $row['province'];

                $_SESSION['message'] = "✅ Login successful!";

                if ($role == "candidate") {
                    header("Location: /online-voting/candidates/candidate_dashboard.php");
                } else {
                    header("Location: /online-voting/voter/voter_dashboard.php");
                }
                exit();
            }
        } else {
            $message = "❌ Invalid password!";
        }
    } else {
        $message = "❌ User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting System - Login</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="login-container">  
        <h2>Login</h2>

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

            <div class="form-group">
                <label for="role">Login as:</label>
                <select name="role">
                    <option value="voter">Voter</option>
                    <option value="candidate">Candidate</option>
                </select>
            </div>

            <div class="form-group">
                <label for="citizenship_no">Citizenship Number:</label>
                <input type="text" name="citizenship_no" pattern="\d{2}-\d{2}-\d{2}-\d{5}" placeholder="Format: XX-XX-XX-XXXXX" required>
            </div>

            <div class="form-group">
                <label for="province">Province:</label>
                <select name="province" id="province" required>
                    <option value="Koshi" <?php echo (isset($_SESSION['province']) && $_SESSION['province'] == 'Koshi') ? 'selected' : ''; ?>>Koshi</option>
                    <option value="Madhesh" <?php echo (isset($_SESSION['province']) && $_SESSION['province'] == 'Madhesh') ? 'selected' : ''; ?>>Madhesh</option>
                    <option value="Bagmati" <?php echo (isset($_SESSION['province']) && $_SESSION['province'] == 'Bagmati') ? 'selected' : ''; ?>>Bagmati</option>
                    <option value="Gandaki" <?php echo (isset($_SESSION['province']) && $_SESSION['province'] == 'Gandaki') ? 'selected' : ''; ?>>Gandaki</option>
                    <option value="Lumbini" <?php echo (isset($_SESSION['province']) && $_SESSION['province'] == 'Lumbini') ? 'selected' : ''; ?>>Lumbini</option>
                    <option value="Karnali" <?php echo (isset($_SESSION['province']) && $_SESSION['province'] == 'Karnali') ? 'selected' : ''; ?>>Karnali</option>
                    <option value="Sudurpashchim" <?php echo (isset($_SESSION['province']) && $_SESSION['province'] == 'Sudurpashchim') ? 'selected' : ''; ?>>Sudurpashchim</option>
                </select>
            </div>

            <button type="submit">Login</button>

            <p class="signup-text">Don't have an account? <a href="signup.php">Signup here</a></p>
            <p class="home-text"><a href="home.html">Go Back To Home Page⬅️</a></p>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
            if (message) {
                alert(message);
                <?php unset($_SESSION['message']); ?> 
            }
        });
    </script>

</body>
</html>
