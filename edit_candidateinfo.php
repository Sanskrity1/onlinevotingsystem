<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'candidate') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

$query = $conn->prepare("SELECT fullname, username, contactno, email, citizenship_no, province, district, fathers_name, mothers_name, municipality FROM candidates WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$candidate = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $contactno = $_POST['contactno'];
    $email = $_POST['email'];
    $citizenship_no = $_POST['citizenship_no'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $fathers_name = $_POST['fathers_name'];
    $mothers_name = $_POST['mothers_name'];
    $municipality = $_POST['municipality'];

    $update_query = $conn->prepare("UPDATE candidates SET fullname=?, contactno=?, email=?, citizenship_no=?, province=?, district=?, fathers_name=?, mothers_name=?, municipality=? WHERE username=?");
    $update_query->bind_param("ssssssssss", $fullname, $contactno, $email, $citizenship_no, $province, $district, $fathers_name, $mothers_name, $municipality, $username);
    
    if ($update_query->execute()) {
        echo "<script>alert('Information updated successfully!'); window.location.href='candidate_info.php';</script>";
    } else {
        echo "<script>alert('Error updating information!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Candidate Information</title>
    <link rel="stylesheet" href="edit_candidateinfo.css">
</head>
<body>

    <div class="container">
        <h2>Edit Candidate Information</h2>
        <form method="POST">
            <label>Full Name:</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($candidate['fullname']); ?>" required>

            <label>Contact Number:</label>
            <input type="text" name="contactno" value="<?php echo htmlspecialchars($candidate['contactno']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($candidate['email']); ?>" required>

            <label>Citizenship Number:</label>
            <input type="text" name="citizenship_no" value="<?php echo htmlspecialchars($candidate['citizenship_no']); ?>" required>

            <label>Province:</label>
            <input type="text" name="province" value="<?php echo htmlspecialchars($candidate['province']); ?>" required>

            <label>District:</label>
            <input type="text" name="district" value="<?php echo htmlspecialchars($candidate['district']); ?>" required>

            <label>Father's Name:</label>
            <input type="text" name="fathers_name" value="<?php echo htmlspecialchars($candidate['fathers_name']); ?>" required>

            <label>Mother's Name:</label>
            <input type="text" name="mothers_name" value="<?php echo htmlspecialchars($candidate['mothers_name']); ?>" required>

            <label>Municipality:</label>
            <input type="text" name="municipality" value="<?php echo htmlspecialchars($candidate['municipality']); ?>" required>

            <button type="submit">Save Changes</button>
        </form>

        <div class="btn-container">
            <a href="candidate_info.php" class="btn">⬅️ Back to Information</a>
        </div>
    </div>

</body>
</html>
