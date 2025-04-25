<?php
session_start();
include '../db.php';  

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM candidates WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Candidate deleted!'); window.location.href='candidates.php';</script>";
    } else {
        echo "<script>alert('Error deleting candidate.'); window.location.href='candidates.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='candidates.php';</script>";
}
?>
