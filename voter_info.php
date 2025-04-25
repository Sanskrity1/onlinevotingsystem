<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'voter') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

$query = $conn->prepare("SELECT voter_id, fullname, username, contactno, email, citizenship_no, province, district, fathers_name, mothers_name, municipality, ward_no, voting_center FROM voters WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$voter = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Voter Information</title>
    <link rel="stylesheet" href="voters_info.css">
</head>
<body>

    <div class="container">
        <h2>Voter Information</h2>
        <table border="1">
            <tr><th>ID</th><td><?php echo htmlspecialchars($voter['voter_id']); ?></td></tr>
            <tr><th>Full Name</th><td><?php echo htmlspecialchars($voter['fullname']); ?></td></tr>
            <tr><th>Username</th><td><?php echo htmlspecialchars($voter['username']); ?></td></tr>
            <tr><th>Contact Number</th><td><?php echo htmlspecialchars($voter['contactno']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($voter['email']); ?></td></tr>
            <tr><th>Citizenship Number</th><td><?php echo htmlspecialchars($voter['citizenship_no']); ?></td></tr>
            <tr><th>Province</th><td><?php echo htmlspecialchars($voter['province']); ?></td></tr>
            <tr><th>District</th><td><?php echo htmlspecialchars($voter['district']); ?></td></tr>
            <tr><th>Father's Name</th><td><?php echo htmlspecialchars($voter['fathers_name']); ?></td></tr>
            <tr><th>Mother's Name</th><td><?php echo htmlspecialchars($voter['mothers_name']); ?></td></tr>
            <tr><th>Municipality</th><td><?php echo htmlspecialchars($voter['municipality']); ?></td></tr>
            <tr><th>Ward No</th><td><?php echo htmlspecialchars($voter['ward_no']); ?></td></tr>
            <tr><th>Voting Center</th><td><?php echo htmlspecialchars($voter['voting_center']); ?></td></tr>
        </table>

        <div class="btn-container">
            <a href="edit_voterinfo.php" class="btn">✏️ Edit Information</a>
            <a href="voter_dashboard.php" class="btn">⬅️ Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
