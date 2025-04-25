<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['election_id'])) {
    $election_id = $_POST['election_id'];

    $sql = "SELECT id as candidate_id, fullname FROM candidates WHERE election_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $election_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $candidates = [];
    while ($row = $result->fetch_assoc()) {
        $candidates[] = $row;
    }

    echo json_encode($candidates);
} else {
    echo json_encode([]);
}
?>
