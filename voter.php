<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin.php");
    exit();
}

$sql = "SELECT * FROM voters";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Information</title>
    <link rel="stylesheet" href="voter.css">
</head>
<body>

    <h2>Voter Information</h2>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Father's Name</th>
                <th>Mother's Name</th>
                <th>Citizenship No</th>
                <th>Province</th>
                <th>District</th>
                <th>Municipality/VDC</th>
                <th>Ward No</th>
                <th>Voting Center</th>
                <th>Contact</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['voter_id']}</td>
                        <td>{$row['fullname']}</td>
                        <td>{$row['fathers_name']}</td>
                        <td>{$row['mothers_name']}</td>
                        <td>{$row['citizenship_no']}</td>
                        <td>{$row['province']}</td>
                        <td>{$row['district']}</td>
                        <td>{$row['municipality']}</td>
                        <td>{$row['ward_no']}</td>
                        <td>{$row['voting_center']}</td>
                        <td>{$row['contactno']}</td>
                        <td>{$row['email']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='13'>No voters found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <button onclick="window.location.href='admin_dashboard.php'">⬅️ Back to Dashboard</button>
</body>
</html>
