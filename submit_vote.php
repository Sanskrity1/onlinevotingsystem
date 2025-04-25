<?php
session_start();
include '../db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

if (!isset($_POST['election_id']) || !isset($_POST['id'])) {
    echo json_encode(["error" => "Election or Candidate not selected"]);
    exit;
}

$username = $_SESSION['username'];
$election_id = $_POST['election_id'];
$id = $_POST['id'];

$checkVote = $conn->prepare("SELECT * FROM votes WHERE username = ? AND election_id = ?");
$checkVote->bind_param("si", $username, $election_id);
$checkVote->execute();
$voteResult = $checkVote->get_result();

if ($voteResult->num_rows > 0) {
    echo json_encode(["error" => "You have already voted in this election"]);
    exit;
}

$insertVote = $conn->prepare("INSERT INTO votes (election_id, id, username) VALUES (?, ?, ?)");
$insertVote->bind_param("iis", $election_id, $id, $username);

if ($insertVote->execute()) {
    echo json_encode(["message" => "Vote cast successfully"]);
} else {
    echo json_encode(["error" => "Failed to cast vote"]);
}
?>
