<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'candidate') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $municipality = $_POST['municipality'];
    $ward_no = $_POST['ward_no'];
    $election_type = $_POST['election_type'];
    $election_post = $_POST['election_post'];
    $election_id = $_POST['election_name']; 
    $username = $_SESSION['username']; 

    $target_dir = "candidates/uploads/"; 
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); 
    }
    
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $photo_path = $target_file;
    } else {
        die("Error uploading photo. Check folder permissions.");
    }

    $query = "INSERT INTO candidates (fullname, province, district, municipality, ward_no, election_type, election_post, election_name, photo, username)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssss", $fullname, $province, $district, $municipality, $ward_no, $election_type, $election_post, $election_id, $photo_path, $username);

    if ($stmt->execute()) {
        echo "<script>alert('Candidate added successfully!'); window.location.href='candidate_dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
