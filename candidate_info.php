<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$query = $conn->prepare("SELECT fullname, username, contactno, email, citizenship_no, province, district, fathers_name, mothers_name, municipality FROM candidates WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$candidate = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Candidate Information</title>
    <link rel="stylesheet" href="candidates_info.css">
</head>
<body>

    <div class="container">
        <h2>Candidate Information</h2>
        <table border="1">
            <tr><th>Full Name</th><td><?php echo htmlspecialchars($candidate['fullname']); ?></td></tr>
            <tr><th>Username</th><td><?php echo htmlspecialchars($candidate['username']); ?></td></tr>
            <tr><th>Contact Number</th><td><?php echo htmlspecialchars($candidate['contactno']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($candidate['email']); ?></td></tr>
            <tr><th>Citizenship Number</th><td><?php echo htmlspecialchars($candidate['citizenship_no']); ?></td></tr>
            <tr><th>Province</th><td><?php echo htmlspecialchars($candidate['province']); ?></td></tr>
            <tr><th>District</th><td><?php echo htmlspecialchars($candidate['district']); ?></td></tr>
            <tr><th>Father's Name</th><td><?php echo htmlspecialchars($candidate['fathers_name']); ?></td></tr>
            <tr><th>Mother's Name</th><td><?php echo htmlspecialchars($candidate['mothers_name']); ?></td></tr>
            <tr><th>Municipality</th><td><?php echo htmlspecialchars($candidate['municipality']); ?></td></tr>
        </table>

        <div class="btn-container">
            <a href="edit_candidateinfo.php" class="btn">✏️ Edit Information</a>
            <a href="candidate_dashboard.php" class="btn">⬅️ Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
