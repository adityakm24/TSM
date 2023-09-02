<?php
/*
Template Name: Therapist1 Dashboard
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
    $short_term_goals = '';
    $goals_achieved = '';
    $remarks = '';

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

    // Check if the form is submitted for adding a new school
    if (isset($_POST['upload'])) {
        global $wpdb;

        // Sanitize and validate form inputs
        $student_id = isset($_POST['student_id']) ? sanitize_text_field($_POST['student_id']) : '';
        $school_id = isset($_POST['school_id']) ? sanitize_text_field($_POST['school_id']) : '';
        $short_term_goals = isset($_POST['short_term_goals']) ? sanitize_text_field($_POST['short_term_goals']) : '';
        $goals_achieved = isset($_POST['goals_achieved']) ? sanitize_text_field($_POST['goals_achieved']) : '';
        $remarks = isset($_POST['remarks']) ? sanitize_text_field($_POST['remarks']) : '';

        // Check if the subdomain is empty, and if so, generate a unique one
        if (empty($student_id)) {
            echo '<div class="error-message">Student id is required.</div>';
        } elseif (empty($school_id)) {
            echo '<div class="error-message">School id is required.</div>';
        } elseif (empty($short_term_goals)) {
            echo '<div class="error-message">Short term goal is required.</div>';
        } elseif (empty($goals_achieved)) {
            echo '<div class="error-message">Goals achieved is required.</div>';
        } else {
            // Insert the sanitized data into the database
            $data = array(
                'school_id' => $school_id,
                'student_id' => $student_id,
                'short_term_goals' => $short_term_goals,
                'goals_achieved' => $goals_achieved,
                'remarks' => $remarks,
            );

            $result = $wpdb->insert($table_name, $data);

            if ($result === false) {
                echo '<div class="error-message">Data insertion failed. Error: ' . $wpdb->last_error . '</div>';
            } else {
                echo '<div class="success-message">Data has been uploaded successfully.</div>';
            }

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

        // Check if the student id already exists in the database for other records
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
        <h1>Welcome, <?php echo esc_html($current_user->user_login); ?>!</h1>

        <!-- Add School Form -->
        <button onclick="showPopup()">Student Report</button>
        <!-- View Report Button -->
        <button onclick="showViewPopup()">View Report</button>

        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">School Report Upload</h2>
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
                <h2 class="entry-title">Edit Student Report</h2>
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

        
<!-- View Report Popup -->
<div class="popup-overlay" id="viewPopupOverlay">
    <div class="popup-container">
        <h2 class="entry-title">View Student Report</h2>
        <form method="post" action="">
            <label for="view_student_id">Student ID:</label>
            <input type="text" name="view_student_id" id="view_student_id" required autocomplete="on" oninput="this.placeholder = this.value">
            <button type="button" onclick="fetchStudentReport()">Show</button>
            <button type="button" onclick="hideViewPopup()">Close</button>
        </form>
        <div id="viewPopupContent">
        <?php if (!empty($query_results)) : ?>
            <table id="studentReportTable">
                <thead>
                    <tr>
                        <th>School ID</th>
                        <th>Student ID</th>
                        <th>Short Term Goals</th>
                        <th>Goals Achieved</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($query_results as $school_data) : ?>
                        <tr>
                            <td><?php echo esc_html($school_data->school_id); ?></td>
                            <td><?php echo esc_html($school_data->student_id); ?></td>
                            <td><?php echo esc_html($school_data->short_term_goals); ?></td>
                            <td><?php echo esc_html($school_data->goals_achieved); ?></td>
                            <td><?php echo esc_html($school_data->remarks); ?></td>
                            <td>
                                <button onclick="editPopup(<?php echo $school_data->id; ?>, <?php $query_results ?>)">Edit</button>
                                <form method="post" action="">
                                    <input type="hidden" name="delete_id" value="<?php echo $school_data->id; ?>">
                                    <?php wp_nonce_field('delete_nonce', 'delete_nonce'); ?>
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No student report found.</p>
        <?php endif; ?>
        </div>
    </div>
</div>

        

        <!-- Logout Form -->
        <form method="post" action="">
            <?php wp_nonce_field('therapist_logout', 'therapist_logout_nonce'); ?>
            <input type="submit" name="therapist_logout" value="Logout">
        </form>
    </div>

    <script>
    function showPopup() {
        document.getElementById("popupOverlay").style.display = "block";
    }

    function hidePopup() {
        document.getElementById("popupOverlay").style.display = "none";
    }

    function editPopup(id) {
        document.getElementById("editPopupOverlay").style.display = "block";
        // Populate the edit form fields with data from the selected row
        const selectedSchool = query_results.find((school) => parseInt(school.id) === id);
        if (selectedSchool) {
            document.getElementById("edit_school_id").value = selectedSchool.school_id;
            document.getElementById("edit_student_id").value = selectedSchool.student_id;
            document.getElementById("edit_short_term_goals").value = selectedSchool.short_term_goals;
            document.getElementById("edit_goals_achieved").value = selectedSchool.goals_achieved;
            document.getElementById("edit_remarks").value = selectedSchool.remarks;
        } else {
            console.error("Selected record not found!");
        }
    }

    function hideReportPopup() {
        document.getElementById("reportPopupOverlay").style.display = "none";
    }

    function showViewPopup() {
    document.getElementById('view_student_id').value = ''; // Clear any previous student ID
    document.getElementById("viewPopupOverlay").style.display = "block";
    displayTable(); // Display the table content in the view popup
}

function fetchStudentReport(studentId) {
    const data = new URLSearchParams();
    data.append('action', 'fetch_student_report');
    data.append('student_id', studentId);

    fetch(csr_ajax_object.ajax_url, {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Display the fetched student report data
            const reportData = data.report_data;
            let reportContent = '<table><thead><tr><th>School ID</th><th>Student ID</th><th>Short Term Goals</th><th>Goals Achieved</th><th>Remarks</th></tr></thead><tbody>';

            reportData.forEach(schoolData => {
                reportContent += '<tr>';
                reportContent += `<td>${schoolData.school_id}</td>`;
                reportContent += `<td>${schoolData.student_id}</td>`;
                reportContent += `<td>${schoolData.short_term_goals}</td>`;
                reportContent += `<td>${schoolData.goals_achieved}</td>`;
                reportContent += `<td>${schoolData.remarks}</td>`;
                reportContent += '<td>';
                reportContent += `<button onclick="editPopup(${schoolData.id})">Edit</button>`;
                reportContent += '<form method="post" action="">';
                reportContent += `<input type="hidden" name="delete_id" value="${schoolData.id}">`;
                reportContent += `${wp_nonce_field('delete_nonce', 'delete_nonce', true, false)}`;
                reportContent += '<button type="submit" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</button>';
                reportContent += '</form>';
                reportContent += '</td>';
                reportContent += '</tr>';
            });

            reportContent += '</tbody></table>';
            document.getElementById('viewPopupContent').innerHTML = reportContent;
        } else {
            document.getElementById('viewPopupContent').innerHTML = `<p>${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error('Error fetching student report:', error);
    });
}

    function showViewPopup() {
        document.getElementById('view_student_id').value = ''; // Clear any previous student ID
        document.getElementById("viewPopupOverlay").style.display = "block";
        fetchStudentReport('');
    }

    function hideViewPopup() {
        document.getElementById('viewPopupOverlay').style.display = 'none';
    }

    function displayTable() {
        const tableContent = document.getElementById("studentReportTable").outerHTML;

        // Set the retrieved content as the innerHTML of the view popup
        document.getElementById("viewPopupContent").innerHTML = tableContent;
    }
</script>


<?php
} else {
    // Redirect to the login page if the user is not logged in
    wp_redirect(home_url('/therapist-login/'));
    exit;
}
?>