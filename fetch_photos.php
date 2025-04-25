<?php
include '../db.php';
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(["error" => "Candidate ID not provided"]);
    exit;
}

$candidate_id = $_POST['id'];  

$query = $conn->prepare("SELECT photo FROM candidates WHERE id = ?");
$query->bind_param("i", $candidate_id);
$query->execute();
$result = $query->get_result();

if ($row = $result->fetch_assoc()) {
    $photo_filename = $row['photo'];
    $photo_url = "http://localhost/online-voting/candidates/candidates/uploads/" . $photo_filename;
    $photo_path = "../candidates/candidates/uploads/" . $photo_filename;

    if (file_exists($photo_path)) {
        echo json_encode(["photo_url" => $photo_url]);
    } else {
        echo json_encode(["error" => "Photo file not found"]);
    }
} else {
    echo json_encode(["error" => "Candidate not found"]);
}