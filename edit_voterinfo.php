<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'voter') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

$query = $conn->prepare("SELECT fullname, email, contactno, citizenship_no, province, district, fathers_name, mothers_name, municipality, ward_no, voting_center FROM voters WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$voter = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $contactno = trim($_POST['contactno']);
    $citizenship_no = trim($_POST['citizenship_no']);
    $province = trim($_POST['province']);
    $district = trim($_POST['district']);
    $fathers_name = trim($_POST['fathers_name']);
    $mothers_name = trim($_POST['mothers_name']);
    $municipality = trim($_POST['municipality']);
    $ward_no = trim($_POST['ward_no']);
    $voting_center = trim($_POST['voting_center']);

    $updateQuery = $conn->prepare("UPDATE voters SET fullname = ?, email = ?, contactno = ?, citizenship_no = ?, province = ?, district = ?, fathers_name = ?, mothers_name = ?, municipality = ?, ward_no = ?, voting_center = ? WHERE username = ?");
    $updateQuery->bind_param("ssssssssssss", $fullname, $email, $contactno, $citizenship_no, $province, $district, $fathers_name, $mothers_name, $municipality, $ward_no, $voting_center, $username);

    if ($updateQuery->execute()) {
        echo "<script>alert('Information updated successfully!'); window.location='voter_info.php';</script>";
    } else {
        echo "<script>alert('Error updating information!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Voter Information</title>
    <link rel="stylesheet" href="edit_voterinfo.css">
</head>
<body>
    <div class="container">
        <h2>Edit Voter Information</h2>
        <form method="post">
            <label>Full Name:</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($voter['fullname']); ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($voter['email']); ?>" required><br>

            <label>Contact Number:</label>
            <input type="text" name="contactno" value="<?php echo htmlspecialchars($voter['contactno']); ?>" required><br>

            <label>Citizenship Number:</label>
            <input type="text" name="citizenship_no" value="<?php echo htmlspecialchars($voter['citizenship_no']); ?>" required><br>

            <label>Province:</label>
            <input type="text" name="province" value="<?php echo htmlspecialchars($voter['province']); ?>" required><br>

            <label>District:</label>
            <input type="text" name="district" value="<?php echo htmlspecialchars($voter['district']); ?>" required><br>

            <label>Father's Name:</label>
            <input type="text" name="fathers_name" value="<?php echo htmlspecialchars($voter['fathers_name']); ?>" required><br>

            <label>Mother's Name:</label>
            <input type="text" name="mothers_name" value="<?php echo htmlspecialchars($voter['mothers_name']); ?>" required><br>

            <label>Municipality:</label>
            <input type="text" name="municipality" value="<?php echo htmlspecialchars($voter['municipality']); ?>" required><br>

            <label>Ward No:</label>
            <input type="text" name="ward_no" value="<?php echo htmlspecialchars($voter['ward_no']); ?>" required><br>

            <label>Voting Center:</label>
            <input type="text" name="voting_center" value="<?php echo htmlspecialchars($voter['voting_center']); ?>" required><br>

            <button type="submit">Save Changes</button>
        </form>

        <a href="voter_info.php" class="btn">⬅️ Back</a>
    </div>
</body>
</html>
