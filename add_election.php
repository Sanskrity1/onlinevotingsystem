<?php
session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election_type = $_POST['election_type'];
    $election_post = $_POST['election_post'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $election_name = $_POST['election_name'];
    $election_center = $_POST['election_center'];
    $ward_no = $_POST['ward_no'];
    $election_date = $_POST['election_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $conn->prepare("INSERT INTO elections (election_type, election_post, province, district, election_name, election_center, ward_no, election_date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $election_type, $election_post, $province, $district, $election_name, $election_center, $ward_no, $election_date, $start_time, $end_time);
    $stmt->execute();
    $stmt->close();
    
    header("Location: manage_elections.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Election</title>
    <link rel="stylesheet" href="add_election.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form method="POST" action="add_election.php">
        <label for="election_type">Election Type:</label>
        <select id="election_type" name="election_type" required>
            <option value="">Select Election Type</option>
            <option value="VDC Election">VDC Election</option>
            <option value="Municipality Election">Municipality Election</option>
            <option value="Provincial Election">Provincial Election</option>
            <option value="Local Level Election">Local Level Election</option>
        </select>

        <label for="election_post">Election Post:</label>
        <select id="election_post" name="election_post" required>
            <option value="">Select Election Post</option>
        </select>

        <label for="province">Province:</label>
        <select id="province" name="province" required>
            <option value="">Select Province</option>
            <option value="Koshi">Koshi</option>
            <option value="Madhesh">Madhesh</option>
            <option value="Bagmati">Bagmati</option>
            <option value="Gandaki">Gandaki</option>
            <option value="Lumbini">Lumbini</option>
            <option value="Karnali">Karnali</option>
            <option value="Sudurpashchim">Sudurpashchim</option>
        </select>

        <label for="district">District:</label>
        <select id="district" name="district" required>
            <option value="">Select District</option>
        </select>

        <label for="election_name">Election Name:</label>
        <input type="text" id="election_name" name="election_name" required placeholder="Enter Election Name">

        <label for="election_date">Election Date:</label>
        <input type="date" id="election_date" name="election_date" required>

        <label for="start_time">Start Time:</label>
        <input type="time" id="start_time" name="start_time" required>

        <label for="end_time">End Time:</label>
        <input type="time" id="end_time" name="end_time" required>

        <input type="submit" value="Add Election">
    </form>
    <a href="elections.php" class="back-button">Back</a>

    <script>
        $(document).ready(function() {
            var districts = {
                "Koshi": ["Bhojpur", "Dhankuta", "Ilam", "Jhapa", "Khotang", "Morang", "Okhaldhunga", "Panchthar", "Sankhuwasabha", "Solukhumbu", "Sunsari", "Taplejung", "Udayapur"],
                "Madhesh": ["Barishal", "Dhanusha", "Mahottari", "Parsa", "Rautahat", "Sarlahi", "Siraha", "Saptari", "Sunsari", "Bara"],
                "Bagmati": ["Bhaktapur", "Chitwan", "Dolakha", "Kathmandu", "Lalitpur", "Makwanpur", "Nuwakot", "Rasuwa", "Sindhuli", "Sindhupalchok"],
                "Gandaki": ["Baglung", "Gorkha", "Kaski", "Lamjung", "Manang", "Mustang", "Myagdi", "Nawalpur", "Parbat", "Syangja", "Tanahun"],
                "Lumbini": ["Arghakhanchi", "Banke", "Bardiya", "Dang", "Gulmi", "Kapilvastu", "Kailali", "Nawalparasi", "Palpa", "Pyuthan", "Rupandehi", "Rolpa", "Salyan"],
                "Karnali": ["Dailekh", "Dolpa", "Humla", "Jajarkot", "Jumla", "Kalikot", "Mugu", "Rukum", "Salyan"],
                "Sudurpashchim": ["Achham", "Baitadi", "Bajhang", "Bajura", "Darchula", "Dadeldhura", "Doti", "Kailali", "Kanchanpur"]
            };

            var electionPosts = {
                "VDC Election": ["VDC Chairperson", "VDC Vice-Chairperson", "Ward Chairpersons", "Ward Members (including women & Dalit women)"],
                "Municipality Election": ["Mayor", "Deputy Mayor", "Ward Chairperson", "Ward Members (including women & Dalit women)"],
                "Provincial Election": ["Chief Minister", "Provincial Assembly Members (FPTP & PR)"],
                "Local Level Election": ["Mayor/Chairperson", "Deputy Mayor/Vice-Chairperson", "Ward Chairperson", "Ward Members (including women & Dalit women)"]
            };

            $('#province').change(function() {
                var selectedProvince = $(this).val();
                var districtDropdown = $('#district');

                districtDropdown.empty();

                districtDropdown.append('<option value="">Select District</option>');

                if (districts[selectedProvince]) {
                    $.each(districts[selectedProvince], function(index, district) {
                        districtDropdown.append('<option value="' + district + '">' + district + '</option>');
                    });
                }
            });

            $('#election_type').change(function() {
                var selectedElectionType = $(this).val();
                var electionPostDropdown = $('#election_post');

                electionPostDropdown.empty();

                electionPostDropdown.append('<option value="">Select Election Post</option>');

                if (electionPosts[selectedElectionType]) {
                    $.each(electionPosts[selectedElectionType], function(index, post) {
                        electionPostDropdown.append('<option value="' + post + '">' + post + '</option>');
                    });
                }
            });
        });
    </script>

</body>
</html>
