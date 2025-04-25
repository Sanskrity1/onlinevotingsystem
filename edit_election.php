<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['election_id'])) {
    $election_id = $_GET['election_id'];

    $query = "SELECT * FROM elections WHERE election_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $election_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $election = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Election not found!";
        header("Location: manage_elections.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "No election selected!";
    header("Location: manage_elections.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $election_name = $_POST['election_name'];
    $election_type = $_POST['election_type'];
    $election_post = $_POST['election_post'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $election_date = $_POST['election_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $update_query = "UPDATE elections SET election_name = ?, election_type = ?, election_post = ?, province = ?, district = ?, election_date = ?, start_time = ?, end_time = ? WHERE election_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssssi", $election_name, $election_type, $election_post, $province, $district, $election_date, $start_time, $end_time, $election_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Election updated successfully!";
        header("Location: manage_elections.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating election: " . $stmt->error;
        header("Location: edit_election.php?election_id=$election_id");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Election</title>
    <link rel="stylesheet" href="edit_election.css">
</head>
<body>

    <h2>Edit Election Details</h2>
    
    <form action="edit_election.php?election_id=<?php echo $election_id; ?>" method="POST">
        <label for="election_name">Election Name</label>
        <input type="text" id="election_name" name="election_name" value="<?php echo htmlspecialchars($election['election_name']); ?>" required>

        <label for="election_type">Election Type</label>
        <input type="text" id="election_type" name="election_type" value="<?php echo htmlspecialchars($election['election_type']); ?>" required>

        <label for="election_post">Election Post</label>
        <input type="text" id="election_post" name="election_post" value="<?php echo htmlspecialchars($election['election_post']); ?>" required>

        <label for="province">Province</label>
        <input type="text" id="province" name="province" value="<?php echo htmlspecialchars($election['province']); ?>" required>

        <label for="district">District</label>
        <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($election['district']); ?>" required>

        <label for="election_date">Election Date</label>
        <input type="date" id="election_date" name="election_date" value="<?php echo htmlspecialchars($election['election_date']); ?>" required>

        <label for="start_time">Start Time</label>
        <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($election['start_time']); ?>" required>

        <label for="end_time">End Time</label>
        <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($election['end_time']); ?>" required>

        <button type="submit">Update Election</button>
    </form>
    <a href="manage_elections.php" class="btn">Back to Manage Elections</a>
</body>
</html>
