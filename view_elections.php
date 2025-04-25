<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$query = "SELECT election_id, province, district, election_type, election_post, 
                 start_time, end_time, election_date 
          FROM elections";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No elections available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Elections</title>
    <link rel="stylesheet" href="view_election.css">
</head>
<body>

    <h2>👥 View Elections</h2>

    <table border="1">
        <tr>
            <th>Election ID</th>
            <th>Province</th>
            <th>District</th>
            <th>Election Type</th>
            <th>Election Post</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Election Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['election_id']); ?></td>
                <td><?php echo htmlspecialchars($row['province']); ?></td>
                <td><?php echo htmlspecialchars($row['district']); ?></td>
                <td><?php echo htmlspecialchars($row['election_type']); ?></td>
                <td><?php echo htmlspecialchars($row['election_post']); ?></td>
                <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                <td><?php echo htmlspecialchars($row['election_date']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <div class="back-button-container">
        <a href="candidate_dashboard.php" class="back-btn">Go Back to Dashboard</a>
    </div>

</body>
</html>
