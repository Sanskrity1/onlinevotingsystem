<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$query = "SELECT election_id, election_name, election_type, election_post, province, district, ward_no, election_center, election_date, start_time, end_time FROM elections";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Elections</title>
    <link rel="stylesheet" href="manage_election.css">
</head>
<body>

    <div class="container">
        <h3>List of Elections</h3>

        <table>
            <thead>
                <tr>
                    <th>Election Name</th>
                    <th>Election Type</th>
                    <th>Election Post</th>
                    <th>Province</th>
                    <th>District</th>
                    <th>Election Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['election_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['election_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['election_post']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['province']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['district']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['election_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['start_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['end_time']) . "</td>";
                        echo "<td>
                                <a class='edit-btn' href='edit_election.php?election_id=" . $row['election_id'] . "'>Edit</a> 
                                <a class='delete-btn' href='delete_election.php?election_id=" . $row['election_id'] . "'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No elections found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="elections.php" class="back-btn">Back</a>
    </div>

</body>
</html>
