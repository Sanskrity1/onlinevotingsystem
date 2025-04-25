<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <div class="navbar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_dashboard.php">Home</a></li>
            <li><a href="../elections/elections.php">Elections</a></li>
            <li><a href="candidates.php">Candidates</a></li>
            <li><a href="voter.php">Voters</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </div>

    <h3>Welcome, Admin!</h3>
    <div class="main-content">
        <div class="card-container">
            <div class="card" onclick="window.location.href='../elections/elections.php'">
                <h3>Election 🗳️</h3>
            </div>

            <div class="card" onclick="window.location.href='candidates.php'">
                <h3>Candidate 👤</h3>
            </div>

            <div class="card" onclick="window.location.href='voter.php'">
                <h3>Voters 📊</h3>
            </div>
        </div>
    </div>

</body>
</html>
