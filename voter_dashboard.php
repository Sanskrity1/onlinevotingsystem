<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'voter') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

$fullnameQuery = $conn->prepare("SELECT fullname FROM voters WHERE username = ?");
$fullnameQuery->bind_param("s", $username);
$fullnameQuery->execute();
$fullnameResult = $fullnameQuery->get_result();
$fullname = $fullnameResult->fetch_assoc()['fullname'] ?? "User";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Voter Dashboard</title>
    <link rel="stylesheet" href="voters_dashboard.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Voter Panel</h2>
            <ul>
            <li><a href="voter_dashboard.php">🏠 Home</a></li>
            <li><a href="voter_info.php">ℹ️ Voter Information</a></li>
            <li><a href="votes.php">🗳️ Vote for Candidate</a></li>
           <li> <a href="voter_livepolls.php">📊 Live Voting Polls</a></li>
           </ul>
           <a href="../logout.php" class="logout-btn">🚪 Logout</a>
        </div>

        <div class="main-content">
            <h2>Welcome, <?php echo htmlspecialchars($fullname); ?>!</h2>

            <div class="card-container">
            <div class="card" onclick="window.location.href='voter_info.php'">
                <h3>ℹ️ Voter Information</h3>
            </div>

            <div class="card" onclick="window.location.href='votes.php'">
                <h3>🗳️ Vote for Candidate</h3>
            </div>

            <div class="card" onclick="window.location.href='voter_livepolls.php'">
                <h3>📊 Live Voting Polls</h3>
            </div>
        </div>
    </div>
</body>
</html>

