<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'voter') {
    header("Location: index.php");
    exit();
}

$pollsQuery = "SELECT c.fullname AS candidate, 
                      c.election_post AS election_post, 
                      e.election_type AS election_type, 
                      c.province AS candidate_province,  
                      c.district AS candidate_district,  
                      COUNT(v.id) AS votes 
               FROM candidates c 
               JOIN elections e ON c.election_id = e.election_id
               LEFT JOIN votes v ON c.id = v.id 
               GROUP BY c.id, e.election_id 
               ORDER BY votes DESC";

$pollsResult = $conn->query($pollsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Voting Polls</title>
    <link rel="stylesheet" href="voter_livepoll.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>📊 Live Voting Polls</h2>

    <table id="livePollsTable">
        <tr>
            <th>Candidate</th>
            <th>Election Type</th>
            <th>Election Post</th>
            <th>Votes</th>
            <th>Province</th>  
            <th>District</th>  
        </tr>
        <?php while ($poll = $pollsResult->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($poll['candidate']); ?></td>
                <td><?php echo htmlspecialchars($poll['election_type']); ?></td>
                <td><?php echo htmlspecialchars($poll['election_post']); ?></td>
                <td><?php echo $poll['votes']; ?></td>
                <td><?php echo htmlspecialchars($poll['candidate_province']); ?></td>  
                <td><?php echo htmlspecialchars($poll['candidate_district']); ?></td>  
            </tr>
        <?php } ?>
    </table>

    <div class="back-button-container">
        <a href="voter_dashboard.php" class="back-btn">Go Back to Dashboard</a>
    </div>

    <script>
    function updatePolls() {
        $.ajax({
            url: 'livepolls.php',
            method: 'GET',
            success: function(response) {
                $('#livePollsTable').html($(response).find('#livePollsTable').html());
            }
        });
    }

    $(document).ready(function() {
        $('#voteForm').submit(function(event) {
            event.preventDefault();
            
            var election_id = $('#election_id').val();
            var candidate_id = $('#id').val();

            $.ajax({
                url: 'submit_vote.php',
                method: 'POST',
                data: {
                    election_id: election_id,
                    id:id
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    alert(data.message);
                    if (data.status === 'success') {
                        updatePolls();  
                    }
                }
            });
        });
    });
    </script>

</body>
</html>
