<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $contactno = $_POST['contactno'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $citizenship_no = trim($_POST['citizenship_no']);
    $province = $_POST['province'];
    $district = $_POST['district'];
    $fathers_name = $_POST['fathers_name'];
    $mothers_name = $_POST['mothers_name'];
    $municipality = $_POST['municipality'];
    $ward_no = $_POST['ward_no'];
    $voting_center = $_POST['voting_center'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^\d{2}-\d{2}-\d{2}-\d{5}$/", $citizenship_no)) {
        echo "<script>alert('Citizenship number must be in the format XX-XX-XX-XXXXX'); window.history.back();</script>";
        exit();
    }

    $photo_name = $_FILES['citizenship_photo']['name'];
    $photo_tmp_name = $_FILES['citizenship_photo']['tmp_name'];
    $photo_size = $_FILES['citizenship_photo']['size'];
    $photo_error = $_FILES['citizenship_photo']['error'];

    $valid_extensions = ['jpg', 'jpeg', 'png'];
    $photo_ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));

    if (!in_array($photo_ext, $valid_extensions)) {
        echo "<script>alert('Please upload a valid image (JPG, JPEG, PNG).'); window.history.back();</script>";
        exit();
    }
    if ($photo_error !== 0) {
        echo "<script>alert('Error uploading the file.'); window.history.back();</script>";
        exit();
    }

    $upload_dir = 'uploads/citizenship_photos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); 
    }

    $photo_new_name = $citizenship_no . '.' . $photo_ext;
    $photo_path = $upload_dir . $photo_new_name;

    if (!move_uploaded_file($photo_tmp_name, $photo_path)) {
        echo "<script>alert('Failed to upload the photo.'); window.history.back();</script>";
        exit();
    }

    $checkQuery = $conn->prepare("SELECT * FROM " . ($role == "voter" ? "voters" : "candidates") . " WHERE username=? OR citizenship_no=?");
    $checkQuery->bind_param("ss", $username, $citizenship_no);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Citizenship Number already exists!');</script>";
    } else {
        if ($role == "voter") {
            $query = $conn->prepare("INSERT INTO voters (fullname, username, contactno, email, password, citizenship_no, province, district, fathers_name, mothers_name, municipality, ward_no, voting_center, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("ssssssssssssss", $fullname, $username, $contactno, $email, $password, $citizenship_no, $province, $district, $fathers_name, $mothers_name, $municipality, $ward_no, $voting_center, $photo_path);
        } else {
            $query = $conn->prepare("INSERT INTO candidates (fullname, username, contactno, email, password, citizenship_no, province, district, fathers_name, mothers_name, municipality, ward_no, voting_center, photo, election_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)");
            $query->bind_param("ssssssssssssss", $fullname, $username, $contactno, $email, $password, $citizenship_no, $province, $district, $fathers_name, $mothers_name, $municipality, $ward_no, $voting_center, $photo_path);
        }

        if ($query->execute()) {
            echo "<script>alert('Signup successful! You can now log in.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Registration failed!');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-container">
        <h2>SIGN UP</h2>
        <form id="signupForm" action="" method="post" enctype="multipart/form-data">
    <label for="fullname">Full Name:</label>
    <input type="text" name="fullname" required><br>

    <label for="username">Username:</label>
    <input type="text" name="username" required><br>

    <label for="role">Register as:</label>
    <select name="role" id="role" required>
        <option value="voter">Voter</option>
        <option value="candidate">Candidate</option>
    </select><br>

    <label for="contactno">Contact No:</label>
    <input type="text" name="contactno" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <label for="citizenship_no">Citizenship Number:</label>
    <input type="text" name="citizenship_no" required pattern="\d{2}-\d{2}-\d{2}-\d{5}" placeholder="Format: XX-XX-XX-XXXXX"><br>

    <label for="citizenship_photo">Citizenship Photo:</label>
    <input type="file" name="citizenship_photo" accept="image/*" required><br>

    <label for="fathers_name">Father's Name:</label>
    <input type="text" name="fathers_name" required><br>

    <label for="mothers_name">Mother's Name:</label>
    <input type="text" name="mothers_name" required><br>

    <label for="province">Province:</label>
            <select name="province" id="province" required>
                <option value="Koshi">Koshi</option>
                <option value="Madhesh">Madhesh</option>
                <option value="Bagmati">Bagmati</option>
                <option value="Gandaki">Gandaki</option>
                <option value="Lumbini">Lumbini</option>
                <option value="Karnali">Karnali</option>
                <option value="Sudurpashchim">Sudurpashchim</option>
            </select><br>

    <label for="district">District:</label>
    <select name="district" id="district" required>
    </select><br>
    <label for="municipality">Municipality/VDC:</label>
    <input type="text" name="municipality" required><br>

    <label for="ward_no">Ward No:</label>
    <input type="number" name="ward_no" required><br>

    <label for="voting_center">Voting Center:</label>
    <input type="text" name="voting_center" required><br>

    <button type="submit">Sign Up</button>
</form>

        <p>Already have an account? <a href="index.php">Login here</a></p>
        <p class="home.text"><a href="home.html">Go Back To Home Page⬅️</a></p>
    </div>

    <script>
        document.getElementById("province").addEventListener("change", function () {
            var province = this.value;
            var districtSelect = document.getElementById("district");
            var districts = {
                "Koshi": ["Bhojpur", "Dhankuta", "Ilam", "Jhapa", "Khotang", "Morang", "Okhaldhunga", "Panchthar", "Sankhuwasabha", "Solukhumbu", "Sunsari", "Taplejung", "Terhathum", "Udayapur"],
                "Madhesh": ["Bara", "Dhanusha", "Mahottari", "Parsa", "Rautahat", "Saptari", "Sarlahi", "Siraha"],
                "Bagmati": ["Bhaktapur", "Chitwan", "Dhading", "Dolakha", "Kavrepalanchok", "Kathmandu", "Lalitpur", "Makwanpur", "Nuwakot", "Ramechhap", "Rasuwa", "Sindhuli", "Sindhupalchok"],
                "Gandaki": ["Baglung", "Gorkha", "Kaski", "Lamjung", "Manang", "Mustang", "Myagdi", "Nawalpur", "Parbat", "Syangja", "Tanahun"],
                "Lumbini": ["Arghakhanchi", "Banke", "Bardiya", "Dang", "Gulmi", "Kapilvastu", "Nawalparasi (West)", "Palpa", "Pyuthan", "Rolpa", "Rupandehi", "Rukum (East)"],
                "Karnali": ["Dolpa", "Humla", "Jumla", "Kalikot", "Mugu", "Salyan", "Surkhet", "Dailekh", "Jajarkot", "Rukum (West)"],
                "Sudurpashchim": ["Achham", "Baitadi", "Bajhang", "Bajura", "Dadeldhura", "Darchula", "Doti", "Kailali", "Kanchanpur"]
            };

            districtSelect.innerHTML = "";
            districts[province].forEach(function (district) {
                var option = document.createElement("option");
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        });

        document.getElementById("province").dispatchEvent(new Event("change"));
    </script>
</body>
</html>
