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
	$table_name = 'tsm_school';

	// Initialize form field variables
	$student_id = '';
	$short_term_goals = '';
	$goals_achieved = '';
	$remarks = '';

	// Check if the logout form is submitted
	if (isset($_POST['therapist_logout'])) {
		// Verify the logout nonce
		if (wp_verify_nonce($_POST['therapist_logout_nonce'], 'therapist_logout')) {
			// Perform the logout
			wp_logout();

			// Destroy the session
			session_destroy();

			// Redirect to the login page
			wp_redirect(home_url('/therapist-login/'));
			exit;
		}
	}
    	if (isset($_POST['upload'])) {
		global $wpdb;

		// Sanitize and validate form inputs
        $student_id = sanitize_text_field( $_POST['student_id'] );
		$short_term_goals = isset($_POST['shortTerm_Goals'])? sanitize_text_field($_POST['shortTerm_Goals']) : '';
		$goals_achieved = isset($_POST['goals_achieved']) ? sanitize_text_field($_POST['goals_achieved']) : '';
		$remarks = isset($_POST['remarks']) ? sanitize_text_field($_POST['remarks']) : '';

		// Check if the subdomain is empty, and if so, generate a unique one

		if (empty($short_term_goals)) {
			echo '<div class="error-message">This fiels is required.</div>';
		} elseif (empty($goals_achieved)) {
			echo '<div class="error-message">This fiels is required.</div>';
		} elseif (empty($remarks)) {
			echo '<div class="error-message">This fiels is required.</div>';
		} else {		
				$data = array(
					'student_id' => $student_id,
					'short_term_goals' => $short_term_goals,
					'goals_achieved' => $goals_achieved,
					'remarks' => $remarks,
				);

				$wpdb->insert($table_name, $data);

				// Display a success message or take appropriate action
				echo '<div class="success-message">Data has been uploaded successfully.</div>';

				// Clear the form fields after successful data upload
				$student_id = '';
				$short_term_goals = '';
				$goals_achieved = '';
				$remarks = '';
			}
		}
	} elseif (isset($_POST['update'])) {
		// Handle form submission for updating data
		$update_id = absint($_POST['update_id']);
		$update_student_id = isset($_POST['edit_student_id']) ? sanitize_text_field($_POST['edit_student_id']) : '';
		$update_short_term_goals = isset($_POST['edit_short_term_goals']) ? sanitize_text_field($_POST['edit_short_term_goals']) : '';
		$update_goals_achieved = isset($_POST['goals_achieved']) ? sanitize_text_field($_POST['goals_achieved']) : '';
		$update_remarks = isset($_POST['edit_remarks']) ? sanitize_text_field($_POST['edit_remarks']) : '';
		
			// Perform the update query
			$data = array(
				'student_id' => $update_student_id,
				'short_term_goals' => $update_short_term_goals,
				'goals_achieved' => $update_goals_achieved,
				'remarks' => $update_remarks,
			);
			$wpdb->update($table_name, $data, array('id' => $update_id));

			// Display a success message or take appropriate action
			echo '<div class="success-message">Data has been updated successfully.</div>';
		
	} elseif (isset($_POST['delete_id'])) {
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

    <!-- Add your dashboard content here -->

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
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #0078D4;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        input[type="submit"]:hover {
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
    </style>

    <div class="container">
         <?php echo esc_html($current_user->user_login); ?>!

        <!-- Add therapy details Form --><br>
        <button onclick="showPopup()"">Student Report</button>
        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">Student Report Upload</h2>
                <form method="post" action="">
                    <label for="student_id">Student ID:</label>
                    <input type="text" name="student_id" required autocomplete="off">

					<label for="shortTerm_Goal">Short Term Goal:</label>
                    <textarea name="shortTerm_Goal" required autocomplete="off"></textarea>

                    <label for="goals_achieved">Goals Achieved:</label>
                    <textarea name="goals_achieved" required autocomplete="off"></textarea>

                    <label for="remarks">Remarks:</label>
                    <textarea name="remarks" required autocomplete="off"></textarea>

                    <input type="submit" name="upload" value="Upload">
                    <button type="button" onclick="hidePopup()">Cancel</button>
                </form>
            </div>
        </div>


        <!-- Edit School Data Popup -->
        <div class="popup-overlay" id="editPopupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">Edit Student Report</h2>
                <form method="post" action="">
                    <input type="hidden" id="edit_id" name="update_id" value="">
                    <label for="edit_school_name">Name:</label>
                    <input type="text" name="edit_school_name" id="edit_school_name" required autocomplete="on" oninput="this.placeholder = this.value">
					
					<label for="edit_shortTerm_Goal">Short Term Goal:</label>
                    <input type="text" name="edit_shortTerm_Goal" id="edit_shortTerm_Goal" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_goals_achieved">Goals Achieved:</label>
                    <input type="text" name="edit_goals_achieved" id="edit_goals_achieved" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_remarks">Remarks:</label>
                    <input type="tel" name="edit_remarks" id="edit_remarks" required autocomplete="on" oninput="this.placeholder = this.value">

                    <input type="submit" name="update" value="Update">
                    <button type="button" onclick="hideEditPopup()">Cancel</button>
                </form>
            </div>
        </div>
        <!-- Display Table -->



		<form method="post" action="">
			<?php wp_nonce_field('therapist_logout', 'therapist_logout_nonce'); ?>
            <input type="submit" name="therapist_logout" value="Logout">
        </form>

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
            // Populate the edit form fields with data from the selected row
            const selectedSchool = query_results.find((school) => parseInt(school.id) === id);
            if (selectedSchool) {
                document.getElementById("edit_school_name").value = selectedSchool.school_name;
                document.getElementById("edit_subdomain").value = selectedSchool.subdomain;
                document.getElementById("edit_principal").value = selectedSchool.principal;
                document.getElementById("edit_ph_no").value = selectedSchool.ph_no;
                document.getElementById("edit_mail_id").value = selectedSchool.mail_id;
                document.getElementById("edit_address").value = selectedSchool.address;
            } else {
                console.error("Selected school data not found!");
            }
        }

        function hideEditPopup() {
            document.getElementById("editPopupOverlay").style.display = "none";
        }
    </script>
