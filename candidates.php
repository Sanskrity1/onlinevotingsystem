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

if (isset($_POST['add_candidate'])) {
    $fullname = $_POST['fullname'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $municipality = $_POST['municipality'];
    $ward_no = $_POST['ward_no'];
    $election_type = $_POST['election_type'];
    $election_post = $_POST['election_post'];
    $election_name = $_POST['election_name'];

    $target_dir = "uploads/"; 
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $photo_path = $target_file;
    } else {
        die("Error uploading photo. Check folder permissions.");
    }

    $candidate_username = $_SESSION['username']; 
    
    $query = "INSERT INTO candidates (fullname, province, district, municipality, ward_no, election_type, election_post, election_name, photo, username)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssssssss", $fullname, $province, $district, $municipality, $ward_no, $election_type, $election_post, $election_name, $photo_path, $candidate_username);
    if ($stmt->execute()) {
        echo "<script>alert('Candidate added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding candidate.');</script>";
    }
}

if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];
    $query = "UPDATE candidates SET status = 'approved' WHERE candidate_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $approve_id);
    if ($stmt->execute()) {
        echo "<script>alert('Candidate approved!');</script>";
    } else {
        echo "<script>alert('Error approving candidate.');</script>";
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM candidates WHERE candidate_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Candidate deleted!');</script>";
    } else {
        echo "<script>alert('Error deleting candidate.');</script>";
    }
}

$query = "SELECT * FROM candidates WHERE status = 'pending'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Candidates</title>
    <link rel="stylesheet" href="candidates.css">
</head>
<body>
    <h2>Add New Candidate</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="fullname">Full Name:</label>
        <input type="text" name="fullname" required><br>

        <label for="province">Province:</label>
        <input type="text" name="province" required><br>

        <label for="district">District:</label>
        <input type="text" name="district" required><br>

        <label for="municipality">Municipality:</label>
        <input type="text" name="municipality" required><br>

        <label for="ward_no">Ward No:</label>
        <input type="text" name="ward_no" required><br>

        <label for="election_type">Election Type:</label>
        <input type="text" name="election_type" required><br>

        <label for="election_post">Election Post:</label>
        <input type="text" name="election_post" required><br>

        <label for="election_name">Election Name:</label>
        <input type="text" name="election_name" required><br>

        <label for="photo">Candidate Photo:</label>
        <input type="file" name="photo" required><br><br>

        <button type="submit" name="add_candidate">Add Candidate</button>
    </form>

    <h2>List of Candidates to approve or delete</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Election Type</th>
                <th>Election Post</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['election_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['election_post']); ?></td>
                    <td><?php echo $row['status'] == 'approved' ? 'Approved' : 'Pending'; ?></td>
                    <td>
    <?php if ($row['status'] != 'approved'): ?>
        <a href="approve_candidate.php?approve_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to approve this candidate?');">Approve</a> |
    <?php endif; ?>
    
    <a href="delete_candidate.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this candidate?');">Delete</a>
</td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
</body>
</html>
