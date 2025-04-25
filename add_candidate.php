<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'candidate') {
    header("Location: login.php");
    exit();
}

$election_query = $conn->query("SELECT election_id, election_name FROM elections");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Candidate</title>
    <link rel="stylesheet" href="add_candidate.css">
    <script>
        const provinceDistricts = {
            "Koshi": ["Bhojpur", "Dhankuta", "Ilam", "Jhapa", "Khotang", "Morang", "Okhaldhunga", "Panchthar", "Sankhuwasabha", "Solukhumbu", "Sunsari", "Taplejung", "Udayapur"],
            "Madhesh": ["Barishal", "Dhanusha", "Mahottari", "Parsa", "Rautahat", "Sarlahi", "Siraha", "Saptari", "Sunsari", "Bara"],
            "Bagmati": ["Bhaktapur", "Chitwan", "Dolakha", "Kathmandu", "Lalitpur", "Makwanpur", "Nuwakot", "Rasuwa", "Sindhuli", "Sindhupalchok"],
            "Gandaki": ["Baglung", "Gorkha", "Kaski", "Lamjung", "Manang", "Mustang", "Myagdi", "Nawalpur", "Parbat", "Syangja", "Tanahun"],
            "Lumbini": ["Arghakhanchi", "Banke", "Bardiya", "Dang", "Gulmi", "Kapilvastu", "Kailali", "Nawalparasi", "Palpa", "Pyuthan", "Rupandehi", "Rolpa", "Salyan"],
            "Karnali": ["Dailekh", "Dolpa", "Humla", "Jajarkot", "Jumla", "Kalikot", "Mugu", "Rukum", "Salyan"],
            "Sudurpashchim": ["Achham", "Baitadi", "Bajhang", "Bajura", "Darchula", "Dadeldhura", "Doti", "Kailali", "Kanchanpur"]
        };

        const electionPosts = {
            "VDC Election": ["VDC Chairperson", "VDC Vice-Chairperson", "Ward Chairpersons", "Ward Members (including women & Dalit women)"],
            "Municipality Election": ["Mayor", "Deputy Mayor", "Ward Chairperson", "Ward Members (including women & Dalit women)"],
            "Provincial Election": ["Chief Minister", "Provincial Assembly Members (FPTP & PR)"],
            "Local Level Election": ["Mayor/Chairperson", "Deputy Mayor/Vice-Chairperson", "Ward Chairperson", "Ward Members (including women & Dalit women)"]
        };

        function updateDistricts() {
            const province = document.getElementById("province").value;
            const districtDropdown = document.getElementById("district");
            districtDropdown.innerHTML = ""; 

            if (provinceDistricts[province]) {
                provinceDistricts[province].forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    districtDropdown.appendChild(option);
                });
            }
        }

        function updateElectionPosts() {
            const electionType = document.getElementById("election_type").value;
            const electionPostDropdown = document.getElementById("election_post");
            electionPostDropdown.innerHTML = ""; 

            if (electionPosts[electionType]) {
                electionPosts[electionType].forEach(post => {
                    const option = document.createElement("option");
                    option.value = post;
                    option.textContent = post;
                    electionPostDropdown.appendChild(option);
                });
            }
        }
    </script>
</head>
<body>

    <div class="container">
        <h2>Add Candidate</h2>
        <form action="add_candidate_process.php" method="POST" enctype="multipart/form-data">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="province">Province:</label>
            <select id="province" name="province" onchange="updateDistricts()" required>
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

            <label for="municipality">Municipality/VDC:</label>
            <input type="text" id="municipality" name="municipality" required>

            <label for="ward_no">Ward No:</label>
            <input type="text" id="ward_no" name="ward_no" required>

            <label for="election_type">Election Type:</label>
            <select id="election_type" name="election_type" onchange="updateElectionPosts()" required>
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

            <label for="election_name">Election Name:</label>
            <select id="election_name" name="election_name" required>
                <option value="">Select Election</option>
                <?php
                while ($row = $election_query->fetch_assoc()) {
                    echo "<option value='" . $row['election_id'] . "'>" . $row['election_name'] . "</option>";
                }
                ?>
            </select>

            <label for="photo">Candidate Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>

            <button type="submit" name="submit">Add Candidate</button>
        </form>
        <a href="candidate_dashboard.php" class="btn">Go Back to Dashboard</a>
    </div>

</body>
</html>
