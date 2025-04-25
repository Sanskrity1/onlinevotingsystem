<?php
session_start();
include '../db.php'; 

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$query = "SELECT election_id, election_name FROM elections ORDER BY election_date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . mysqli_error($conn)]);
    exit;
}

$elections = [];

while ($row = mysqli_fetch_assoc($result)) {
    $elections[] = $row;
}

echo json_encode($elections);
?>
