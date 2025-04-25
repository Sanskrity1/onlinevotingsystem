<!DOCTYPE html>
<html>
<head>
    <title>Vote for Candidate</title>
    <link rel="stylesheet" href="votes.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="vote-container">
        <h2>Vote for Your Candidate</h2>
        
        <div class="form-group">
            <label for="electionSelect">Select Election:</label>
            <select id="electionSelect" required>
                <option value="">--Select Election--</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="candidateSelect">Select Candidate:</label>
            <select id="candidateSelect" required>
                <option value="">--Select Candidate--</option>
            </select>
        </div>

        <div class="candidate-info" id="candidateDetails" style="display: none;">
            <h3>Candidate Selected:</h3>
            <p><strong>Full Name:</strong> <span id="fullName"></span></p>
        </div>

        <button id="submitVote">Submit Vote</button>
        <a href="voter_dashboard.php" class="back-btn">Go Back to Dashboard</a>
    </div>

    <script>
    $(document).ready(function () {
        $.get("fetch_elections.php", function (data) {
            if (Array.isArray(data)) {
                data.forEach(function (e) {
                    $("#electionSelect").append(`<option value="${e.election_id}">${e.election_name}</option>`);
                });
            }
        });

        $("#electionSelect").on("change", function () {
            const electionId = $(this).val();
            $("#candidateSelect").html('<option value="">--Select Candidate--</option>');
            $("#candidateDetails").hide();

            if (electionId) {
                $.post("fetch_candidates.php", { election_id: electionId }, function (candidates) {
                    if (Array.isArray(candidates)) {
                        candidates.forEach(function (c) {
                            $("#candidateSelect").append(`<option value="${c.id}" data-name="${c.fullname}">${c.fullname}</option>`);
                        });
                    }
                }, "json");
            }
        });

        $("#candidateSelect").on("change", function () {
            const candidateName = $(this).find("option:selected").data("name");
            if (candidateName) {
                $("#fullName").text(candidateName);
                $("#candidateDetails").show();
            } else {
                $("#candidateDetails").hide();
            }
        });

        $("#submitVote").click(function () {
            const election_id = $("#electionSelect").val();
            const candidate_id = $("#candidateSelect").val();

            if (!election_id || !candidate_id) {
                alert("Please select an election and candidate.");
                return;
            }

            $.post("submit_vote.php", { election_id: election_id, id: candidate_id }, function (response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = 'voter_dashboard.php'; 
                } else if (response.error) {
                    alert(response.error);
                }
            }, "json").fail(function () {
                alert("Error submitting vote. Try again.");
            });
        });
    });
    </script>
</body>
</html>
