<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin.php");
    exit();
}

$elections_query = "SELECT * FROM elections";
$elections_result = $conn->query($elections_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Elections</title>
    <link rel="stylesheet" href="election.css"> 

</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="add_election.php">Add Election</a></li>
            <li><a href="manage_elections.php">Manage Elections</a></li>
            <li><a href="../admin/admin_dashboard.php">Dashboard</a></li>
        </ul>
    </div>

    <div class="container">
        <h3>Existing Elections</h3>
        <table>
            <thead>
                <tr>
                    <th>Election ID</th>
                    <th>Election Type</th>
                    <th>Election Name</th>
                    <th>Election Post</th>
                    <th>Province</th>
                    <th>District</th>
                    <th>Election Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while($election = $elections_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $election['election_id']; ?></td>
                        <td><?php echo $election['election_type']; ?></td>
                        <td><?php echo $election['election_name']; ?></td>
                        <td><?php echo $election['election_post']; ?></td>
                        <td><?php echo $election['province']; ?></td>
                        <td><?php echo $election['district']; ?></td>
                        <td><?php echo $election['election_date']; ?></td>
                        <td><?php echo $election['start_time']; ?></td>
                        <td><?php echo $election['end_time']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="../admin/admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

</body>
</html>
