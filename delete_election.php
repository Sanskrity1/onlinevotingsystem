<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['election_id'])) {
    $election_id = $_GET['election_id'];

    $query = "DELETE FROM elections WHERE election_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $election_id);

    if ($stmt->execute()) {
        echo "Election deleted successfully!";
        header("Location: manage_elections.php"); 
        exit();
    } else {
        echo "Error deleting election: " . $stmt->error;
    }
} else {
    echo "Election ID is missing.";
    exit();
}
?>
