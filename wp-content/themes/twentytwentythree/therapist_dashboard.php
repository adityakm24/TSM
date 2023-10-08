
<?php
/*
Template Name: Therapist Dashboard
*/
global $wpdb;
$table_name = 'tsm_termreport';

// Check if the user is logged in
if (is_user_logged_in()) {
	// Get the current user object
	$current_user = wp_get_current_user();
	$table_name = 'tsm_termreport';

	// Initialize form field variables
	$student_id = '';
    $school_id = '';
    $short_term_goals = '';
    $goals_achieved = '';
    $remarks = '';

	if (isset($_POST['therapist_logout'])) {
		// Verify the logout nonce
		if (wp_verify_nonce($_POST['therapist_logout_nonce'], 'therapist_logout_logout')) {
			// Perform the logout
			wp_logout();

			// Destroy the session
			session_destroy();

			// Redirect to the login page
			wp_redirect(home_url('/therapist-login/'));
			exit;
		}
	}


	// Check if the form is submitted for adding a new school
	if (isset($_POST['upload'])) {
		global $wpdb;

		// Sanitize and validate form inputs
        $student_id = isset($_POST['student_id']) ? sanitize_text_field($_POST['student_id']) : '';
        $school_id = isset($_POST['school_id']) ? sanitize_text_field($_POST['school_id']) : '';
        $short_term_goals = isset($_POST['short_term_goals']) ? sanitize_text_field($_POST['short_term_goals']) : '';
        $goals_achieved = isset($_POST['goals_achieved']) ? sanitize_text_field($_POST['goals_achieved']) : '';
        $remarks = isset($_POST['remarks']) ? sanitize_text_field($_POST['remarks']) : '';

		if (empty($student_id)) {
            echo '<div class="error-message">Student id is required.</div>';
        } elseif (empty($school_id)) {
            echo '<div class="error-message">School id is required.</div>';
        } elseif (empty($short_term_goals)) {
            echo '<div class="error-message">Short term goal is required.</div>';
        } elseif (empty($goals_achieved)) {
            echo '<div class="error-message">Goals achieved is required.</div>';
        } else {
            $data = array(
                'school_id' => $school_id,
                'student_id' => $student_id,
                'short_term_goals' => $short_term_goals,
                'goals_achieved' => $goals_achieved,
                'remarks' => $remarks,
            );

				$wpdb->insert($table_name, $data);

				// Display a success message or take appropriate action
				echo '<div class="success-message">Data has been uploaded successfully.</div>';

				// Clear the form fields after successful data upload
				$school_id = '';
                $student_id = '';
                $short_term_goals = '';
                $goals_achieved = '';
                $remarks = '';
			}
		
	} elseif (isset($_POST['update'])) {
		// Handle form submission for updating data
		$update_id = absint($_POST['update_id']);
        $update_school_id = isset($_POST['edit_school_id']) ? sanitize_text_field($_POST['edit_school_id']) : '';
        $update_student_id = isset($_POST['edit_student_id']) ? sanitize_text_field($_POST['edit_student_id']) : '';
        $update_short_term_goals = isset($_POST['edit_short_term_goals']) ? sanitize_text_field($_POST['edit_short_term_goals']) : '';
        $update_goals_achieved = isset($_POST['edit_goals_achieved']) ? sanitize_text_field($_POST['edit_goals_achieved']) : '';
        $update_remarks = isset($_POST['edit_remarks']) ? sanitize_text_field($_POST['edit_remarks']) : '';

		$student_id_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE student_id = %s AND id != %d", $update_student_id, $update_id));

		if ($student_id_exists) {
            echo '<div class="error-message">Student id already exists. Please choose a different one.</div>';
        } else {
            // Perform the update query
            $data = array(
                'school_id' => $update_school_id,
                'student_id' => $update_student_id,
                'short_term_goals' => $update_short_term_goals,
                'goals_achieved' => $update_goals_achieved,
                'remarks' => $update_remarks,
            );
			$wpdb->update($table_name, $data, array('id' => $update_id));

			// Display a success message or take appropriate action
			echo '<div class="success-message">Data has been updated successfully.</div>';
		}
    }
	elseif (isset($_POST['delete_id'])) {
		$delete_id = absint($_POST['delete_id']);

		// Check if the delete nonce is valid
		if (isset($_POST['delete_nonce']) && wp_verify_nonce($_POST['delete_nonce'], 'delete_nonce')) {
			// Perform the deletion
			$wpdb->delete($table_name, array('id' => $delete_id));

			// Display a success message or take appropriate action
			echo '<div class="success-message">Data has been deleted successfully.</div>';

			// Refresh the page after deletion
			echo '<meta http-equiv="refresh" content="0">';
		}
	}

	$query_results = $wpdb->get_results("SELECT * FROM $table_name");
	?>

    <style>
        /* Microsoft-themed styling */
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f1f1;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #0078D4;
            margin-bottom: 30px;
        }

        button {
            background-color: #0078D4;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup-container {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1000;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .submit {
            background-color: #0078D4;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        .logout {
            background-color: red;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        .submit:hover {
            background-color: #005a9e;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
        }

        p {
            margin-top: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Top navigation bar */
        .topnav {
            background-color: black;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }

        /* Left side of the top navigation bar */
        .left {
            display: flex;
            align-items: center;
        }

        /* Special School Management logo text */
        .logo {
            font-size: 20px;
            font-weight: bold;
        }

        /* Right side of the top navigation bar */
        .right {
            position: relative; /* Position the profile icon and dropdown */
            display: flex;
            align-items: center;
        }

        /* Profile icon */
        .profile-icon {
            cursor: pointer;
        }

        /* Dropdown menu content */
        .dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: none;
            padding: 10px;
        }

        /* Show the dropdown menu on hover */
        .profile:hover .dropdown-content {
            display: block;
        }

        /* Style the links inside the dropdown */
        .dropdown-content a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }

        /* Change the link color on hover */
        .dropdown-content a:hover {
            background-color: #0078D4;
            color: #fff;
        }

          /* The Modal (background) */
  .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }

  /* Modal Content/Box */
  .modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
  }

  /* Close Button */
  .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
  }


    </style>
    <!-- Make sure to include Font Awesome CSS in the <head> of your HTML file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <div class="container">

        <h1>Welcome, <?php echo esc_html($current_user->user_login); ?>!</h1>
        

        <!-- Add School Form -->
        <button onclick="showPopup()">Add Report</button>
        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">Term Report Upload</h2>
                <form method="post" action="">
                    <label for="school_id">School ID:</label>
                    <input type="text" name="school_id" required autocomplete="off">

                    <label for="student_id">Student ID:</label>
                    <input type="text" name="student_id" required autocomplete="off">

                    <label for="short_term_goals">Short Term Goals:</label>
                    <textarea type="text" name="short_term_goals" required autocomplete="off"></textarea>

                    <label for="goals_achieved">Goals Achieved:</label>
                    <textarea type="text" name="goals_achieved" required autocomplete="off"></textarea>

                    <label for="remarks">Remarks:</label>
                    <textarea type="text" name="remarks" required autocomplete="off"></textarea>

                    <input type="submit" name="upload" value="Upload">
                    <button type="button" onclick="hidePopup()">Cancel</button>
                </form>
            </div>
        </div>


        <!-- Edit School Data Popup -->
        <div class="popup-overlay" id="editPopupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">Edit Term Report</h2>
                <form method="post" action="">
                    <input type="hidden" id="edit_id" name="update_id" value="">
                    <label for="edit_school_id">School ID:</label>
                    <input type="text" name="edit_school_id" id="edit_school_id" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_student_id">Student ID:</label>
                    <input type="text" name="edit_student_id" id="edit_student_id" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_short_term_goals">Short Term Goals:</label>
                    <textarea type="text" name="edit_short_term_goals" id="edit_short_term_goals" required autocomplete="on" oninput="this.placeholder = this.value"></textarea>

                    <label for="edit_goals_achieved">Goals Achieved:</label>
                    <textarea type="text" name="edit_goals_achieved" id="edit_goals_achieved" required autocomplete="on" oninput="this.placeholder = this.value"></textarea>

                    <label for="edit_remarks">Remarks:</label>
                    <textarea type="text" name="edit_remarks" id="edit_remarks" required autocomplete="on" oninput="this.placeholder = this.value"></textarea>
                    
                    <input type="submit" name="update" value="Update">
                    <button type="button" onclick="hideEditPopup()">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Button to open the modal -->
        <button onclick="openReportModal()">View Report</button>

        <!-- The Modal -->
        <div id="reportModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeReportModal()">&times;</span>
            <label for="student_id">Student ID</label>
            <input type="text" id="studentIdInput" placeholder="Enter Student ID">
            <button onclick="filterReports()">View Report</button>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>School ID</th>
                        <th>Short Term Goals</th>
                        <th>Goals Achieved</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody"></tbody>
            </table>
        </div>
        </div>

        <button onclick="openProfileModal()">Profile</button>
        <!-- Profile Modal -->
        <div id="profileModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeProfileModal()">&times;</span>
                <h2>Edit Profile</h2>
                <form method="post" action="">
                    <label for="profile_school_id">School ID:</label>
                    <input type="text" id="profile_school_id" name="profile_school_id" required autocomplete="off">

                    <label for="profile_name">Name:</label>
                    <input type="text" id="profile_name" name="profile_name" required autocomplete="off">

                    <label for="profile_department">Department:</label>
                    <input type="text" id="profile_department" name="profile_department" required autocomplete="off">

                    <label for="profile_gender">Gender:</label>
                    <input type="text" id="profile_gender" name="profile_gender" required autocomplete="off">

                    <label for="profile_phone_number">Phone Number:</label>
                    <input type="text" id="profile_phone_number" name="profile_phone_number" required autocomplete="off">

                    <label for="profile_age">Age:</label>
                    <input type="text" id="profile_age" name="profile_age" required autocomplete="off">

                    <label for="profile_address">Address:</label>
                    <input type="text" id="profile_address" name="profile_address" required autocomplete="off">

                    <label for="profile_email">Email:</label>
                    <input type="email" id="profile_email" name="profile_email" required autocomplete="off">

                    <label for="profile_password">Password:</label>
                    <input type="password" id="profile_password" name="profile_password" required autocomplete="off">

                    <input type="submit" name="update_profile" value="Save Profile">
                </form>
            </div>
        </div>


        <!-- Logout Form -->
        <form method="post" action="">
            <?php wp_nonce_field('therapist_logout', 'therapist_logout_nonce'); ?>
            <button type="submit" name="therapist_logout" value="Logout">Logout</button>
        </form>
    </div>

    

    


    <script>
        function showPopup() {
            document.getElementById("popupOverlay").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("popupOverlay").style.display = "none";
        }

        query_results = <?php echo json_encode($query_results); ?>;

        function editPopup(id) {
            document.getElementById("editPopupOverlay").style.display = "block";

            // Find the record with the corresponding ID in the query_results array
            const selectedSchool = query_results.find((student) => parseInt(student.id) === id);

            if (selectedSchool) {
                console.log("Selected School:", selectedSchool); // Debugging line
                document.getElementById("edit_id").value = selectedSchool.id;
                document.getElementById("edit_student_id").value = selectedSchool.student_id;
                document.getElementById("edit_school_id").value = selectedSchool.school_id;
                document.getElementById("edit_short_term_goals").value = selectedSchool.short_term_goals;
                document.getElementById("edit_goals_achieved").value = selectedSchool.goals_achieved;
                document.getElementById("edit_remarks").value = selectedSchool.remarks;
            } else {
                console.error("Selected student not found!");
            }
        }


        function hideEditPopup() {
            document.getElementById("editPopupOverlay").style.display = "none";
        }

        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";
        }

        function openReportModal() {
            const modal = document.getElementById('reportModal');
            modal.style.display = 'block';
        }

        // Function to close the modal
        function closeReportModal() {
            const modal = document.getElementById('reportModal');
            modal.style.display = 'none';
        }

        function filterReports() {
        const studentIdInput = document.getElementById("studentIdInput").value;
        const modal = document.getElementById('reportModal');
        const tableBody = document.getElementById('reportTableBody');

        // Clear the table body
        tableBody.innerHTML = '';

        // Filter the records based on the entered student ID
        const filteredReports = query_results.filter((report) => report.student_id === studentIdInput);

        if (filteredReports.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6">No matching records found.</td></tr>';
        } else {
            // Display the matching records in the table body
            filteredReports.forEach((report) => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${report.student_id}</td>
                        <td>${report.school_id}</td>
                        <td>${report.short_term_goals}</td>
                        <td>${report.goals_achieved}</td>
                        <td>${report.remarks}</td>
                        <td>
                            <button onclick="editPopup(${report.id})">Edit</button>
                            <form method="post" action="">
                                <input type="hidden" name="delete_id" value="${report.id}">
                                <?php wp_nonce_field('delete_nonce', 'delete_nonce'); ?>
                                <button class="submit" type="submit" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                `;
            });
        }

        modal.style.display = 'block';
    }

    // Function to open the profile modal
    function openProfileModal() {
        const modal = document.getElementById('profileModal');
        modal.style.display = 'block';
    }

    // Function to close the profile modal
    function closeProfileModal() {
        const modal = document.getElementById('profileModal');
        modal.style.display = 'none';
    }

    </script>

	<?php
} else {
	// Redirect to the login page if the user is not logged in
	wp_redirect(home_url('/therapist-login/'));
	exit;
}