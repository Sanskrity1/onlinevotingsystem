<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$query = $conn->prepare("SELECT fullname FROM candidates WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

$fullname = "Candidate"; 
if ($row = $result->fetch_assoc()) {
    $fullname = $row['fullname'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard</title>
    <link rel="stylesheet" href="candidate_dashboard.css">

</head>
<body>

    <div class="sidebar">
        <h2>Welcome, <?php echo htmlspecialchars($fullname); ?>!</h2>
        <ul>
            <li><a href="candidate_dashboard.php">Home</a></li>
            <li><a href="candidate_info.php" class="sidebar-btn">Information ℹ️ </a></li>
            <li><a href="add_candidate.php" class="sidebar-btn">Add Candidate 👤</a></li>
            <li><a href="view_elections.php" class="sidebar-btn">View Elections 📊</a></li>
            <li><a href="../logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="card-container">
            <div class="card" onclick="window.location.href='candidate_info.php'">
                <h3>Information ℹ️</h3>
            </div>

            <div class="card" onclick="window.location.href='add_candidate.php'">
                <h3>Add Candidate 👤</h3>
            </div>

            <div class="card" onclick="window.location.href='view_elections.php'">
                <h3>View Elections 📊</h3>
            </div>
        </div>
    </div>

</body>
</html>
