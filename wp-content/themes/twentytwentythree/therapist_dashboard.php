
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


	// Check if the form is submitted for adding a new report
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
        .upload {
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

  .input-row {
    display: flex;
    justify-content: space-between;
}

.input-half {
    flex-basis: 48%; /* Adjust the width of each input */
    margin-right: 2%; /* Adjust the margin between the inputs */
}


.input-half input[type="date"],
.input-half input[type="time"] {
    width: 100%; /* Set the width of the input fields to 100% of the parent container */
    padding: 12px; /* Increase the padding for a larger input field */
    font-size: 16px; /* Increase the font size for better visibility */
}
.popup-container {
        max-height: 70vh;
        overflow-y: auto;
    }
    #earlierRecordsPopupOverlay .input-row button {
        margin-left: 10px;
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

                    <label for="student_id">Student ID:</label>
                    <input type="text" name="student_id" required autocomplete="off">

                    <label for="short_term_goals">Short Term Goals:</label>
                    <textarea type="text" name="short_term_goals" required autocomplete="off"></textarea>

                    <label for="goals_achieved">Goals Achieved:</label>
                    <textarea type="text" name="goals_achieved" required autocomplete="off"></textarea>

                    <label for="remarks">Remarks:</label>
                    <textarea type="text" name="remarks" required autocomplete="off"></textarea>

                    <input type="submit" class="upload" name="upload" value="Upload">
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


<!-- View Earlier Records Button -->
<button onclick="showEarlierRecordsPopup()">View Earlier Records</button>

<!-- Earlier Records Popup -->
<div class="popup-overlay" id="earlierRecordsPopupOverlay">
    <div class="popup-container" style="width: 80%;"> <!-- Adjust the width as needed -->
        <h2 class="entry-title">Earlier Therapy Records</h2>
        <!-- Student ID Search -->
        <div>
            <label for="search_student_id">Student ID:</label>
            <input type="text" name="search_student_id" id="search_student_id" autocomplete="off">
            <button onclick="searchRecords()">Search</button>
        </div>
        <!-- Table to organize subsections -->
        <table>
            <thead>
                <tr>
                    <th>Subsection</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- General Information Section -->
                <tr>
                    <td>General Information</td>
                    <td><button onclick="viewGeneralInformationRecords()">View</button></td>
                </tr>

                <!-- Developmental Assessment Section -->
                <tr>
                    <td>Developmental Assessment</td>
                    <td><button onclick="viewDevelopmentAssessmentRecords()">View</button></td>
                </tr>
                <!-- Comprehensive Evaluation Section -->
                <tr>
                    <td>Comprehensive Evaluation</td>
                    <td><button onclick="viewComprehensiveEvaluationRecords()">View</button></td>
                </tr>

                <!-- Psychological Assessment Section -->
                <tr>
                    <td>Psychological Assessment</td>
                    <td><button onclick="viewPsychologicalAssessmentRecords()">View</button></td>
                </tr>

                <!-- Physiotherapy Section -->
                <tr>
                    <td>Physiotherapy</td>
                    <td><button onclick="viewPhysiotherapyRecords()">View</button></td>
                </tr>

                <!-- Occupational Therapy Section -->
                <tr>
                    <td>Occupational Therapy</td>
                    <td><button onclick="viewOccupationalTherapyRecords()">View</button></td>
                </tr>

                <!-- Sensory Assessment Section -->
                <tr>
                    <td>Sensory Assessment</td>
                    <td><button onclick="viewSensoryAssessmentRecords()">View</button></td>
                </tr>

                <!-- Speech and Language Section -->
                <tr>
                    <td>Speech and Language</td>
                    <td><button onclick="viewSpeechLanguageRecords()">View</button></td>
                </tr>

                <!-- Special Education Assessment Section -->
                <tr>
                    <td>Special Education Assessment</td>
                    <td><button onclick="viewSpecialEducationAssessmentRecords()">View</button></td>
                </tr>

                <!-- ADL Assessment Section -->
                <tr>
                    <td>ADL Assessment</td>
                    <td><button onclick="viewADLAssessmentRecords()">View</button></td>
                </tr>

                <!-- Add more rows for each subsection -->

            </tbody>
        </table>

        <div class="button-row" style="margin-top: 10px;">
            <button type="button" onclick="hideEarlierRecordsPopup()">Close</button>
        </div>
    </div>
</div>

             <!-- New Popup for General Information Records -->
            <div class="popup-overlay" id="generalInformationPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">General Information Records</h2>
                    
                    <!-- Display records for the General Information section here -->
                    <!-- Table with Checkboxes -->
                    <table>
                        <thead>
                            <tr>
                                <th>Cheif Complaints</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Descriptions</th>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Most Bothersome Complaint</th>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Family History</th>
                            </tr>
                            <tr>
                                <td>Type of Family</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Other Family Members having the same/other problems</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Parental History</th>
                            </tr>
                            <tr>
                                <td>Consanguinity</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Age of mother at bith</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>History of previous miscarriage</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Habits of Father/Mother</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Prenatal History</th>
                            </tr>
                            <tr>
                                <td>Prenatal Checkup</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Did your mother have any of issues during pregnency</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Foetal Movements</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Natal History</th>
                            </tr>
                            <tr>
                                <td>Preterm/Full Term/Post Mature/Not Known</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Gestational Age</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Place of Delivery</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Type of Delivery</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Delivered by</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Labour Hours</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Presentation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Was Cord around neck</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Excessive bleeding after delivery</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Resuscitative efforts needed</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Rh Factor</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Birth Weight</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Birth Cry</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Suck Reflex</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Colour of child at birth</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>APGAR SCORE</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>At Birth</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>At 5 mins</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Immunization</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Any history of neonatal ICU admission</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Post Natal History</th>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideGeneralInformationPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Developmental Assessment Records -->
            <div class="popup-overlay" id="developmentAssessmentPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Developmental Assessment Records</h2>
                    
                    <!-- Display records for the Developmental Assessment section here -->
                    <!-- Table with Checkboxes -->
                    <table>
                        <thead>
                            <tr>
                                <th>Developmental Milestones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Attained/Not Attained</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Achieved at the age/month of(specify year/month)</td>
                                <td></td>
                            </tr>                            
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <table>
                        <tbody>
                            <tr>
                                <td>Gross Motor</td>
                                <td>1</td>
                                <td>2</td>
                                <td>Fine Motor</td>
                                <td>1</td>
                                <td>2</td>
                                <td>Social</td>
                                <td>1</td>
                                <td>2</td>
                                <td>Language</td>
                                <td>1</td>
                                <td>2</td>
                                <td>Emotional</td>
                                <td>1</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>Neck Control (3 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Head gard (2 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Social Smile (1 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Alert to sound (1-2 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Stops crying when picked up (3 mon)</td>
                                <td></td>
                                <td></td>
                            </tr>  
                            <tr>
                                <td>Rolling (4-5 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Reach for object (4 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Recognizing mother (2 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Babbling (5-6 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Enjoys being played with and laughs (6 mon)</td>
                                <td></td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Sitting without support (6-7 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Hand Regard (6 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Mirror Play (6 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Responds to call of name (7-8 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Suffers body when annoyed and shows fear of strangers (9 mon)</td>
                                <td></td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Crawing (8-9 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Reach for objects (9 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Stranger anxiety (9 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Non Specific words-mama (10 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Egocentric and very dependent on familiar adults (12 mon)</td>
                                <td></td>
                                <td></td>
                            </tr>  
                            <tr>
                                <td>Standing without support (10 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Release objects (10-12 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Indicates desire by pointing (9 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Follow simple commands (1 yr)</td>
                                <td></td>
                                <td></td>
                                <td>Consistently demands attention and has tantrums when frustrated (2yrs)</td>
                                <td></td>
                                <td></td>
                            </tr>  
                            <tr>
                                <td>Walking without support (12 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Building blocks 2 blocks (15 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Imitates action comes when called  (12 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Identifies parts of body (17 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Becomes less egocentric ans shows feeling and concern for others (3yrs)</td>
                                <td></td>
                                <td></td>
                            </tr>  
                            <tr>
                                <td>Climping (15 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Turns 2-3 pages (18 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Plays with other children  (24 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Form sentences 2-3 words (24 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Very affectionate to people they see often (4yrs)</td>
                                <td></td>
                                <td></td>
                            </tr>         
                            <tr>
                                <td>Running (18 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Drink from cup (24 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Self feeding to tell names  (30 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Use 3 word sentences (3yrs)</td>
                                <td></td>
                                <td></td>
                                <td>Comforts playmates in distress and will respond to reasoning (5yrs)</td>
                                <td></td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Walking up and down steps (24 mon)</td>
                                <td></td>
                                <td></td>
                                <td>Dress and undress partially (3yrs)</td>
                                <td></td>
                                <td></td>
                                <td>Group play  (3yrs)</td>
                                <td></td>
                                <td></td>
                                <td>Known colours and sing from memory (4yrs)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Rides tricycle (3yrs)</td>
                                <td></td>
                                <td></td>
                                <td>Buttons fully catches (4yrs)</td>
                                <td></td>
                                <td></td>
                                <td>Plays competitive games and helps in household  (5yrs)</td>
                                <td></td>
                                <td></td>
                                <td>Asks what a word means (5yrs)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Hopes, Skips (4yrs)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>    
                            <tr>
                                <td>Jumps over obstacles (5yrs)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>         
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideDevelopmentAssessmentPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Comprehensive Evaluation Records -->
            <div class="popup-overlay" id="comprehensiveEvaluationPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Comprehensive Evaluation Records</h2>
                    
                    <!-- Display records for the Comprehensive Evaluation section here -->
                    <table>
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>D.O.E</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>D.O.B</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Age/Gender</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Provisional Diagnosis</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Remarks</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <th>Comprehensive Evaluation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Psychological Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Physiotherapy Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Occupational Therapy Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sensory Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Speech and Language Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Special Education Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>ADL Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Extra Curricular Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Medical History</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideComprehensiveEvaluationPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Psychological Assessment Records -->
            <div class="popup-overlay" id="psychologicalAssessmentPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Psychological Assessment Records</h2>
                    
                    <!-- Display records for the Psychological Assessment section here -->
                    <table>
                        <thead>
                            <tr>
                                <th>Family Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Informant</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Relation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Date of Assessment</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Presenting Complaints</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Educational History</th>
                            </tr>
                            <tr>
                                <td>Name of School</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Class</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attendence</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Medium</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Academic Skills</th>
                            </tr>
                            <tr>
                                <td>Reading</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Counting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Writing</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Academic Readiness</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Co-Curricular Activities</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Mental Status Examination</th>
                            </tr>
                            <tr>
                                <td>General Appearance</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Thought</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Perception</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Mood and Temperament</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Cognitive Functioning</th>
                            </tr>
                            <tr>
                                <td>Attention and Concentration</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Orientation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Memory</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Intelligence</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Comprehension</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Abstractability</th>
                            </tr>
                            <tr>
                                <td>Judgement</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Handiness</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Social Skills</th>
                            </tr>
                            <tr>
                                <td>Affection and Emotional control</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Conduct and Behavior Control</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Behavioral Observation</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Leisure Time Activity</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Test Administered</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Provisional Diagnosis</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Recommendations</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Plan</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hidePsychologicalAssessmentPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Physiotherapy Records -->
            <div class="popup-overlay" id="physiotherapyPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Physiotherapy Records</h2>
                    
                    <!-- Display records for the Physiotherapy section here -->
                    <table>
                        <tbody>
                            <tr>
                                <td>Date of Assessment</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Chief Complaints</td>
                                <td></td>
                            </tr>  
                            <tr>
                                <td>Previous Treatment</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <th>Observation</th>
                            </tr>
                            <tr>
                                <td>Interaction of child with parent</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Response to verbal commands</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Eye contact</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Visual abnormalities Squint/Nystagmus</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Abnormal patterns of upper limb and lower limb</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Involuntary movements</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attention Span</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Form of locomotion</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Posture</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Examination</th>
                            </tr>
                            <tr>
                                <td>1. Musculo Skeletal Examination</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Muscle tone</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Contracture and deformity</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>(a)Neck</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>(b)Trunk</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>(c)Upper Limb</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>(d)Lower Limb</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Muscle Wasting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Limb length discrepency</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2. Range of motion</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Upper Limb</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Lower Limb</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3. Pathological reflexes</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4. Balance and co-ordination</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5. Evaluation</td>
                            </tr>
                            <tr>
                                <td>Fine Motor</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Gross Motor</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>6. GMFM Score</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7. Modified Ashworth Score</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>8. Gail</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9. Sensory Evaluation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>10. Function ability of the child</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>11. Associated Problems</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>12. Investigations</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>13. Diagnosis</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Family Expectation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Brief Description of Child</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Treatment Plan</th>
                            </tr>
                            <tr>
                                <td>Goals</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Plans</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sessions/week</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hidePhysiotherapyPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Occupational Therapy Records -->
            <div class="popup-overlay" id="occupationalTherapyPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Occupational Therapy Records</h2>
                    <h3>Neuro Musculoskeletal Component</h3>
                    <!-- Display records for the Occupational Therapy section here -->
                    <table>
                        <thead>
                            <tr>
                                <th>On Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Eye Contact</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Responds to command</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Posture</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Gait</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>On Examination</th>
                            </tr>
                            <tr>
                                <td><u>MOTOR AREA</u></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Muscle Tone</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Muscle Posture</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Muscle Wasting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Limb Wasting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Clonus (Wristankle)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Joint range of motion</th>
                            </tr>
                            <tr>
                                <td>Upper limb</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Lower limb</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Sensory Area</th>
                            </tr>
                            <tr>
                                <td>Tactile</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Visual</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Auditory</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Olfactory</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Vestibualr</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Proprioception</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Oral Input</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Hand Functions</th>
                            </tr>
                            <tr>
                                <td><u>Dominance</u></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><u>Reach</u></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Upward</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Downward</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Horizontal</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Grasp</th>
                            </tr>
                            <tr>
                                <td><u>a. Gross Grasp</u></td>                                
                            </tr>
                            <tr>
                                <td>Spherical</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cylindrical</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Hook</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><u>b. Fine Grasp</u></td>                                
                            </tr>
                            <tr>
                                <td>Lateral</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Pincer</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Manipulation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Ransfer</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Release</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Grip Strength</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Co-ordination</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><u>Upper limb</u></td>
                            </tr>
                            <tr>
                                <td>Finger (to Nose test)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><u>Lower limb</u></td>
                            </tr>
                            <tr>
                                <td>Heel to skin test</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Bladder and Bowel control</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cognitine</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attention</td>
                                <td></td>
                            </tr>   
                            <tr>
                                <td>Concentration</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Size concept</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Shape concept</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Right/Left Descrimination</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Visual Perpectual</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Depth Perception</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Eye Hand Co-ordination</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Ball Throwing and catching</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Behaviour</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Speech</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Diagnosis</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Short Term Goal</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Long Term Goal</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Treatment Plan</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Follow Up</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>


                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideOccupationalTherapyPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Sensory Assessment Records -->
            <div class="popup-overlay" id="sensoryAssessmentPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Sensory Assessment Records</h2>
                    <h3>Sensory Assessment Checklist</h3>
                    <!-- Display records for the Sensory Assessment section here -->
                    <table>
                        <tbody>
                            <tr>
                                <th>Auditory</th>
                            </tr>                        
                            <tr>
                                <th>Over Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Dislikes noisy places</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoids sound of Mixer, Cooker, Grinder etc</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoids loud sounds</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Covers ears</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoids sounds of birds/animals</td>
                                <td></td>
                            </tr>                        
                            <tr>
                                <th>Under Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Always humming</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Singing for own sake</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Talks to self</td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Visual</th>
                            </tr>                        
                            <tr>
                                <th>Over Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Dislikes very bright light, sunshine, flash from camera etc.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Dislikes action packed colorful television , movies or computer</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Troubles with puzzles</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Faces difficulty in finding objects</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Under Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Dislikes dim light, shade or the dark</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Looking at shiny moving objects</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Squints eyelids to shut</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Flpa hands or objects in front of eyes</td>
                                <td></td>
                            </tr>                        

                            <tr>
                                <th>Tactile</th>
                            </tr>                        
                            <tr>
                                <th>Over Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Dislikes sticky items</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Dislikes stamping in wet places</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoiding hair cutting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Toe walking</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Trying new foods</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoids walking barefoot</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Dislikes washing face or hair</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Under Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Getting towel dry</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Standing close to other people</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Unaware of light touches</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Licks own skin or items</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Put things in mouth</td>
                                <td></td>
                            </tr>       
                            <tr>
                                <td>Writes with heavy pencil pressure</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Use tight gripping of pencils</td>
                                <td></td>
                            </tr>            
                            
                            <tr>
                                <th>Proprioception</th>
                            </tr>                        
                            <tr>
                                <th>Over Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Avoids jumping from heights</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoid climbing tall trees</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoid bicycle rides over gravel</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Use little pressure while writing or colouring</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Poor hand writing</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Frequent falling</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Uses extreme force during task</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Under Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Bumps into people or objects</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Grinds teeth</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Walks on toes</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Chews pencil, shirt, toys etc.</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Breaks pencils or crayons while writing or colouring</td>
                                <td></td>
                            </tr> 

                            <tr>
                                <th>Vestibular</th>
                            </tr>                        
                            <tr>
                                <th>Over Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Avoids being up high such as at top of slide or mountain</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Avoids less stable ground surfaces such as deep pile carpet, grass, sand, etc.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Unable to tolerate backward motion</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Fear or dislike of spinning motion</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Difficulty or fearful on stairs</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Fear or dislike of riding on elevators or escalators</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Under Resposiveness</th>
                            </tr>
                            <tr>
                                <td>Seeks challenges to balance such as skipping, skating</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Seeks climbing and descending stairs or slides</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Constantly fidgeting</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Loves to be upside down</td>
                                <td></td>
                            </tr>      

                            <tr>
                                <th>Gustatory</th>
                            </tr>
                            <tr>
                                <td>Smelling unfamiliar scents</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Smelling objects that are not soluble eg.Play dough, Garbage</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Eating new foods</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Eating familiar foods</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Eating strongly flavoured food (spicy, salty, sweety)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Picky Eating</td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Introception</th>
                            </tr>
                            <tr>
                                <td>High Pain Tolerance</td>
                                <td></td>
                            </tr> 
                            <tr>
                                <td>Always hungry or thirsty</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>High urine output</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Fearful vomiting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Does not drink or eat enough</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideSensoryAssessmentPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Speech and Language Records -->
            <div class="popup-overlay" id="speechLanguagePopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Speech and Language Records</h2>
                    
                    <!-- Display records for the Speech and Language section here -->
                    <table>
                        <tbody>
                            <tr>
                                <th>Presenting Complaints</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Prelinguistic Skills</th>
                            </tr>
                            <tr>
                                <td>Eye contact</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Name call response</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sitting behaviour</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Attention</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Speech Development</th>
                            </tr>
                            <tr>
                                <td>Cooing</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Babbling</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>First Word</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Phrase</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sentence</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Sensory Development</th>
                            </tr>
                            <tr>
                                <td>Vision</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Audition</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Language Skills</th>
                            </tr>
                            <tr>
                                <td>Receptive language skills</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Expressive language skills</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>
                    <h3>Oral Peripheral Mechanism Examination (OPME)</h3>

                    <table>                        
                        <tbody>
                            <tr>
                                <th>Aritulators</th>
                                <th>Structure</th>
                                <th>Function</th>
                            </tr>
                            <tr>
                                <td>Lip</td>
                                <td></td>
                                <td>Protusion P/A<br>Spreading P/A<br>Rounding P/A<br>Puckering P/A</td>
                            </tr>
                            <tr>
                                <td>Tongue</td>
                                <td></td>
                                <td>Spreading P/A<br>Elevation P/A<br>Lateral Movements P/A<br>Retraction P/A</td>
                            </tr>
                            <tr>
                                <td>Teeth</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Hard Palate</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Soft Palate</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Maxilla and Mandible</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Uvula</td>
                                <td></td>
                                <td></td>
                            </tr>
                            
                        </tbody>
                    </table>

                    <table>
                        <tbody>
                            <tr>
                                <th>Remarks</th>                             
                            </tr>
                            <tr>
                                <td></td>                                
                            </tr>
                            <tr>
                                <th>Vegetative Skills</th>
                            </tr>
                            <tr>
                                <td></td>                                
                            </tr>
                            <tr>
                                <th>Speech Imitation Skills</th>
                            </tr>
                            <tr>
                                <td></td>                                
                            </tr>
                            <tr>
                                <th>Speech Intelligibility</th>
                            </tr>
                            <tr>
                                <td></td>                                
                            </tr>
                            <tr>
                                <th>Play Skill</th>
                            </tr>
                            <tr>
                                <td>Group Play: </td>                                
                            </tr>
                            <tr>
                                <td>Solo Play: </td>                                
                            </tr>
                            <tr>
                                <th>Linguistic Skills</th>
                            </tr>
                            <tr>
                                <td>Native Language: </td>                                
                            </tr>
                            <tr>
                                <td>Stimulation at home: </td>                                
                            </tr>
                            <tr>
                                <th>Language test findings</th>
                            </tr>
                            <tr>
                                <td></td>                                
                            </tr>
                            <tr>
                                <th>Provisional Diagnosis</th>
                            </tr>
                            <tr>
                                <td></td>                                
                            </tr>
                            <tr>
                                <th>Recommendations</th>
                            </tr>
                            <tr>
                                <td></td>                                
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideSpeechLanguagePopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for Special Education Assessment Records -->
            <div class="popup-overlay" id="specialEducationAssessmentPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">Special Education Assessment Records</h2>
                    
                    <!-- Display records for the Special Education Assessment section here -->
                    <table>
                        <tbody>
                            <tr>
                                <th>Present Level</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Fine Motor Skills</th>
                            </tr>
                            <tr>
                                <td>Eye Hand Coordination</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Holding</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Functional Academics</th>
                            </tr>
                            <tr>
                                <td>Colour</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Shape</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Size</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Pre Skills (Time, Money and Like Skill)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Academics</th>
                            </tr>
                            <tr>
                                <td><b>(a)Reading Skills</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Identification</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Matching</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sorting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Differentiation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sight Word</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Word Level</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sentence Level</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Paragraph</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>(b)Writing Skills</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Scribbing</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tracing and Dots joining</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Formation of letters</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Copy Writing</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Punctuation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>(c)Arithmetic Skills</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Number Concept</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Counting</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Basic Operations</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Place Value</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>ADL</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Sensory</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Pre Vocational Skills</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideSpecialEducationAssessmentPopup()">Close</button>
                    </div>
                </div>
            </div>
            <!-- New Popup for ADL Assessment Records -->
            <div class="popup-overlay" id="adlAssessmentPopupOverlay">
                <div class="popup-container" style="width: 80%;">
                    <h2 class="entry-title">ADL Assessment Records</h2>
                    
                    <!-- Display records for the ADL Assessment section here -->
                    <table>
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>DOB & Age</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Diagnosis</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <h3>Present Level in ADL</h3>
                    <table>
                        <tbody>
                            <tr>
                                <td>Drinking</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Eating</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Toilet Indication</td>
                                <td>Yes</td>
                                <td></td>
                                <td>No</td>
                                <td></td>                                
                            </tr>
                            <tr>
                                <td>Brushing</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Unbottoning</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Toileting</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Washing</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Buttoning</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Dressing</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Bathing</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Grooming</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Combing</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Hairtieing</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Nail Cutting</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Shaving/Napkin</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Shoe Lave Teing</td>
                                <td>D</td>
                                <td></td>
                                <td>PD</td>
                                <td></td>
                                <td>ID</td>
                                <td>PP</td>
                                <td></td>
                                <td>VP</td>
                                <td></td>
                                <td>FI</td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <h3>Goals</h3>
                    <table>
                        <tbody>
                            <tr>
                                <td>Sl.No</td>
                                <td>Main Goal</td>
                                <td>Sub Goal</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <table>
                        <tbody>                            
                            <tr>
                                <th>Activities</th>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Goals Achieved</th>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <!-- Add more rows for other checkboxes as needed -->
                        </tbody>
                    </table>

                    <table>
                        <tbody>
                            <tr>
                                <td>D-Dependent</td>
                                <td>PD-Partially Dependent</td>
                                <td>ID-Independent</td>
                            </tr>
                            <tr>
                                <td>PP-Physical Prompt</td>
                                <td>VP-Verbal Prompt</td>
                                <td>FI-Fully Dependent</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="button-row" style="margin-top: 10px;">
                        <button type="button" onclick="hideADLAssessmentPopup()">Close</button>
                    </div>
                </div>
            </div>


        <!-- Logout Form -->
        <form method="post" action="">
            <?php wp_nonce_field('therapist_logout', 'therapist_logout_nonce'); ?>
            <button type="submit" name="therapist_logout" value="Logout">Logout</button>
        </form>
    </div>

    
    <script>
    function viewGeneralInformationRecords() {
        // Display the new popup
        document.getElementById("generalInformationPopupOverlay").style.display = "block";
    }

    function hideGeneralInformationPopup() {
        document.getElementById("generalInformationPopupOverlay").style.display = "none";
    }
    function viewDevelopmentAssessmentRecords() {
        // Display the new popup
        document.getElementById("developmentAssessmentPopupOverlay").style.display = "block";
    }

    function hideDevelopmentAssessmentPopup() {
        document.getElementById("developmentAssessmentPopupOverlay").style.display = "none";
    }
    function viewComprehensiveEvaluationRecords() {
        // Display the new popup
        document.getElementById("comprehensiveEvaluationPopupOverlay").style.display = "block";
    }

    function hideComprehensiveEvaluationPopup() {
        document.getElementById("comprehensiveEvaluationPopupOverlay").style.display = "none";
    }
    function viewPsychologicalAssessmentRecords() {
        // Display the new popup
        document.getElementById("psychologicalAssessmentPopupOverlay").style.display = "block";
    }

    function hidePsychologicalAssessmentPopup() {
        document.getElementById("psychologicalAssessmentPopupOverlay").style.display = "none";
    }
    function viewPhysiotherapyRecords() {
        // Display the new popup
        document.getElementById("physiotherapyPopupOverlay").style.display = "block";
    }

    function hidePhysiotherapyPopup() {
        document.getElementById("physiotherapyPopupOverlay").style.display = "none";
    }
    function viewOccupationalTherapyRecords() {
        // Display the new popup
        document.getElementById("occupationalTherapyPopupOverlay").style.display = "block";
    }

    function hideOccupationalTherapyPopup() {
        document.getElementById("occupationalTherapyPopupOverlay").style.display = "none";
    }
    function viewSensoryAssessmentRecords() {
        // Display the new popup
        document.getElementById("sensoryAssessmentPopupOverlay").style.display = "block";
    }

    function hideSensoryAssessmentPopup() {
        document.getElementById("sensoryAssessmentPopupOverlay").style.display = "none";
    }
    function viewSpeechLanguageRecords() {
        // Display the new popup
        document.getElementById("speechLanguagePopupOverlay").style.display = "block";
    }

    function hideSpeechLanguagePopup() {
        document.getElementById("speechLanguagePopupOverlay").style.display = "none";
    }
    function viewSpecialEducationAssessmentRecords() {
        // Display the new popup
        document.getElementById("specialEducationAssessmentPopupOverlay").style.display = "block";
    }

    function hideSpecialEducationAssessmentPopup() {
        document.getElementById("specialEducationAssessmentPopupOverlay").style.display = "none";
    }
    function viewADLAssessmentRecords() {
        // Display the new popup
        document.getElementById("adlAssessmentPopupOverlay").style.display = "block";
    }

    function hideADLAssessmentPopup() {
        document.getElementById("adlAssessmentPopupOverlay").style.display = "none";
    }
</script>
    


    <script>
        function showPopup() {
            document.getElementById("popupOverlay").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("popupOverlay").style.display = "none";
        }

        query_results = <?php echo json_encode($query_results); ?>;

        function editPopup(id) {
            console.log("Record ID:", id);
            console.log("Query Results:", query_results);
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

    function showEarlierRecordsPopup() {
        document.getElementById("earlierRecordsPopupOverlay").style.display = "block";
    }

    function hideEarlierRecordsPopup() {
        document.getElementById("earlierRecordsPopupOverlay").style.display = "none";
    }

    // Function to show and hide General Information Popup
    function showGeneralPopup() {
        document.getElementById("generalPopupOverlay").style.display = "block";
    }

    function hideGeneralPopup() {
        document.getElementById("generalPopupOverlay").style.display = "none";
    }

    // Function to show and hide Developmental Assessment Popup
    function showDevelopmentalPopup() {
        document.getElementById("developmentalPopupOverlay").style.display = "block";
    }

    function hideDevelopmentalPopup() {
        document.getElementById("developmentalPopupOverlay").style.display = "none";
    }

    // Function to show and hide Comprehensive Evaluation Popup
    function showComprehensivePopup() {
        document.getElementById("comprehensivePopupOverlay").style.display = "block";
    }

    function hideComprehensivePopup() {
        document.getElementById("comprehensivePopupOverlay").style.display = "none";
    }

    // Function to show and hide Psychological Assessment Popup
    function showPsychologicalPopup() {
        document.getElementById("psychologicalPopupOverlay").style.display = "block";
    }

    function hidePsychologicalPopup() {
        document.getElementById("psychologicalPopupOverlay").style.display = "none";
    }

    // Function to show and hide Physiotherapy Popup
    function showPhysiotherapyPopup() {
        document.getElementById("physiotherapyPopupOverlay").style.display = "block";
    }

    function hidePhysiotherapyPopup() {
        document.getElementById("physiotherapyPopupOverlay").style.display = "none";
    }

    // Function to show and hide Occupational Therapy Popup
    function showOccupationalPopup() {
        document.getElementById("occupationalPopupOverlay").style.display = "block";
    }

    function hideOccupationalPopup() {
        document.getElementById("occupationalPopupOverlay").style.display = "none";
    }

    // Function to show and hide Sensory Assessment Popup
    function showSensoryPopup() {
        document.getElementById("sensoryPopupOverlay").style.display = "block";
    }

    function hideSensoryPopup() {
        document.getElementById("sensoryPopupOverlay").style.display = "none";
    }

    // Function to show and hide Speech and Language Popup
    function showSpeechPopup() {
        document.getElementById("speechPopupOverlay").style.display = "block";
    }

    function hideSpeechPopup() {
        document.getElementById("speechPopupOverlay").style.display = "none";
    }

    // Function to show and hide Special Education Assessment Popup
    function showSpecialEducationPopup() {
        document.getElementById("specialEducationPopupOverlay").style.display = "block";
    }

    function hideSpecialEducationPopup() {
        document.getElementById("specialEducationPopupOverlay").style.display = "none";
    }

    // Function to show and hide ADL Assessment Popup
    function showAdlPopup() {
        document.getElementById("adlPopupOverlay").style.display = "block";
    }

    function hideAdlPopup() {
        document.getElementById("adlPopupOverlay").style.display = "none";
    }
    </script>

	<?php
} else {
	// Redirect to the login page if the user is not logged in
	wp_redirect(home_url('/therapist-login/'));
	exit;
}