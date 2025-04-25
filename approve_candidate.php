<?php
session_start();
include '../db.php';  

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];

    $query = "UPDATE candidates SET status = 'approved' WHERE id = ?"; 
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $approve_id);
    if ($stmt->execute()) {
        echo "<script>alert('Candidate approved!'); window.location='candidates.php';</script>";
    } else {
        echo "<script>alert('Error approving candidate.'); window.location='candidates.php';</script>";
    }
}
?>
