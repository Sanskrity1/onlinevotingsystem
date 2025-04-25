<?php
include '../db.php';

if (isset($_POST['candidate_id'])) {
    $candidate_id = $_POST['candidate_id'];

    $query = $conn->prepare("SELECT photo FROM candidates WHERE candidate_id = ?");
    $query->bind_param("i", $candidate_id);
    $query->execute();
    $result = $query->get_result();

    if ($row = $result->fetch_assoc()) {
        echo $row['photo']; 
    }
}
?>
