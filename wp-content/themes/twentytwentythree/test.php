<?php
/*
Template Name: Add Students Admin
*/
global $wpdb;

// Check if the user is logged in
if (is_user_logged_in() && $_COOKIE['id'] != NULL) {
    $table_name = 'tsm_student';
    $school_id = $_COOKIE['id'];
    $uname =  '';
    $dept =  '';
    $gender =  '';
    $ph_no =  '';
    $age =  '';
    $mail_id = '';
    $addresspr =  '';
    $addressp = '';
    $password = '';


    $current_user = wp_get_current_user();

    if (isset($_POST['admin_logout'])) {
        // Verify the logout nonce
        if (wp_verify_nonce($_POST['admin_logout_nonce'], 'admin_logout')) {
            // Perform the logout
            wp_logout();

            // Destroy the session
            session_destroy();

            // Redirect to the login page
            wp_redirect(home_url('/admin-login/'));
            exit;
        }
    }


    if (isset($_POST['submit_student'])) {
        $school_id = $_COOKIE['id'];
        $regno = isset($_POST['regno']) ? sanitize_text_field($_POST['regno']) : '';
        $uname = isset($_POST['uname']) ? sanitize_text_field($_POST['uname']) : '';
        $dob = isset($_POST['dob']) ? sanitize_text_field($_POST['dob']) : '';
        $mail_id = isset($_POST['mail_id']) ? sanitize_email($_POST['mail_id']) : '';
        $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
        $ph_no = isset($_POST['ph_no']) ? sanitize_text_field($_POST['ph_no']) : '';
        $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
        $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';
        $ph_res = isset($_POST['ph_res']) ? sanitize_text_field($_POST['ph_res']) : '';
        $addresspr = isset($_POST['addresspr']) ? sanitize_text_field($_POST['addresspr']) : '';
        $addressp = isset($_POST['addressp']) ? sanitize_text_field($_POST['addressp']) : '';
        $informant = isset($_POST['informant']) ? sanitize_text_field($_POST['informant']) : '';
        $religion = isset($_POST['religion']) ? sanitize_text_field($_POST['religion']) : '';
        $mother_tongue = isset($_POST['mother_tongue']) ? sanitize_text_field($_POST['mother_tongue']) : '';
        $eval = isset($_POST['eval']) ? sanitize_text_field($_POST['eval']) : '';
        $fname = isset($_POST['fname']) ? sanitize_text_field($_POST['fname']) : '';
        $mname = isset($_POST['mname']) ? sanitize_text_field($_POST['mname']) : '';
        $focc = isset($_POST['focc']) ? sanitize_text_field($_POST['focc']) : '';
        $mocc = isset($_POST['mocc']) ? sanitize_text_field($_POST['mocc']) : '';
        $famincom = isset($_POST['famincom']) ? sanitize_text_field($_POST['famincom']) : '';
        $caretaker = isset($_POST['caretaker']) ? sanitize_text_field($_POST['caretaker']) : 0;
        $referee = isset($_POST['referee']) ? sanitize_text_field($_POST['referee']) : 0;
        $fed = isset($_POST['fed']) ? sanitize_text_field($_POST['fed']) : 0;
        $med = isset($_POST['med']) ? sanitize_text_field($_POST['med']) : 0;


        $activation = 0;


        if (empty($regno)) {
            echo '<div class="error-message">Registration number is required.</div>';
        } elseif (empty($uname)) {
            echo '<div class="error-message">Name is required.</div>';
        } elseif (empty($dob)) {
            echo '<div class="error-message">Date of Birth is required.</div>';
        } elseif (empty($mail_id)) {
            echo '<div class="error-message">Email is required.</div>';
        } elseif (!is_email($mail_id)) {
            echo '<div class="error-message">Invalid Email address.</div>';
        } elseif (empty($gender)) {
            echo '<div class="error-message">Gender is required.</div>';
        } elseif (empty($ph_no)) {
            echo '<div class="error-message">Phone Number is required.</div>';
        } elseif (!preg_match('/^\d{10}$/', $ph_no)) {
            echo '<div class="error-message">Phone Number must be 10 digits.</div>';
        } elseif (empty($age)) {
            echo '<div class="error-message">Age is required.</div>';
        } elseif (!preg_match('/^\d{10}$/', $ph_res)) {
            echo '<div class="error-message">Permanent Phone Number is required.</div>';
        } elseif (empty($addresspr)) {
            echo '<div class="error-message">Permanent Address is required.</div>';
        } elseif (empty($addressp)) {
            echo '<div class="error-message">Present Address is required.</div>';
        } elseif (empty($informant)) {
            echo '<div class="error-message">Informant is required.</div>';
        } elseif (empty($religion)) {
            echo '<div class="error-message">Religion is required.</div>';
        } elseif (empty($mother_tongue)) {
            echo '<div class="error-message">Mother Tongue is required.</div>';
        } elseif (empty($eval)) {
            echo '<div class="error-message">Date of Evaluation is required.</div>';
        } elseif (empty($fname)) {
            echo '<div class="error-message">Father\'s Name is required.</div>';
        } elseif (empty($mname)) {
            echo '<div class="error-message">Mother\'s Name is required.</div>';
        } elseif (empty($focc)) {
            echo '<div class="error-message">Father\'s Occupation is required.</div>';
        } elseif (empty($fed)) {
            echo '<div class="error-message">Father\'s Education is required.</div>';
        } elseif (empty($med)) {
            echo '<div class="error-message">Mother\'s Education is required.</div>';
        } elseif (empty($mocc)) {
            echo '<div class="error-message">Mother\'s Occupation is required.</div>';
        } elseif (empty($famincom)) {
            echo '<div class="error-message">Annual Family Income is required.</div>';
        } elseif (empty($caretaker)) {
            echo '<div class="error-message">Primary Care Taker is required.</div>';
        } elseif (empty($referee)) {
            echo '<div class="error-message">Refered By is required.</div>';
        } else {
            // Insert the data into the database
            $sql = "INSERT INTO tsm_students (school_id, reg_no, uname, dob, mail_id, gender, ph_no, ph_res, address_present, address_permanent, informant, religion, mother_tongue, evaluation_date, father_name, mother_name, father_education, mother_education, father_occupation, mother_occupation, annual_family_income, primary_caretaker, reference, created_at) 
        VALUES ('$school_id', '$regno', '$uname', '$dob', '$mail_id', '$gender', '$ph_no', '$ph_res', '$addressp', '$addresspr', '$informant', '$religion', '$mother_tongue', '$eval', '$fname', '$mname', '$fed', '$med', '$focc', '$mocc', '$famincom', '$caretaker', '$referee', current_time())";

            $result = $wpdb->query($sql);
            if ($result) {
                echo '<div class="success-message">Data has been uploaded successfully.</div>';
                wp_redirect(home_url('/admin-dashboard/'));
                exit;
            } else {
                $error_message = $wpdb->last_error;
                echo '<div class="error-message">Failed to insert data into the database. Error: ' . $error_message . '</div>';
            }

            exit;
        }
    } elseif (isset($_POST['update'])) {
        $update_school_id = $_COOKIE['id'];
        $update_id = absint($_POST['update_id']);
        $update_regno = isset($_POST['update_regno']) ? sanitize_text_field($_POST['update_regno']) : '';
        $update_uname = isset($_POST['update_uname']) ? sanitize_text_field($_POST['update_uname']) : '';
        $update_dob = isset($_POST['update_dob']) ? sanitize_text_field($_POST['update_dob']) : '';
        $update_mail_id = isset($_POST['update_mail_id']) ? sanitize_email($_POST['update_mail_id']) : '';
        $update_gender = isset($_POST['update_gender']) ? sanitize_text_field($_POST['update_gender']) : '';
        $update_ph_no = isset($_POST['update_ph_no']) ? sanitize_text_field($_POST['update_ph_no']) : '';
        $update_age = isset($_POST['update_age']) ? intval($_POST['update_age']) : 0;
        $update_address = isset($_POST['update_address']) ? sanitize_textarea_field($_POST['update_address']) : '';
        $update_ph_res = isset($_POST['update_ph_res']) ? sanitize_text_field($_POST['update_ph_res']) : '';
        $update_addresspr = isset($_POST['update_addresspr']) ? sanitize_text_field($_POST['update_addresspr']) : '';
        $update_addressp = isset($_POST['update_addressp']) ? sanitize_text_field($_POST['update_addressp']) : '';
        $update_informant = isset($_POST['update_informant']) ? sanitize_text_field($_POST['update_informant']) : '';
        $update_religion = isset($_POST['update_religion']) ? sanitize_text_field($_POST['update_religion']) : '';
        $update_mother_tongue = isset($_POST['update_mother_tongue']) ? sanitize_text_field($_POST['update_mother_tongue']) : '';
        $update_eval = isset($_POST['update_eval']) ? sanitize_text_field($_POST['update_eval']) : '';
        $update_fname = isset($_POST['update_fname']) ? sanitize_text_field($_POST['update_fname']) : '';
        $update_mname = isset($_POST['update_mname']) ? sanitize_text_field($_POST['update_mname']) : '';
        $update_focc = isset($_POST['update_focc']) ? sanitize_text_field($_POST['update_focc']) : '';
        $update_mocc = isset($_POST['update_mocc']) ? sanitize_text_field($_POST['update_mocc']) : '';
        $update_famincom = isset($_POST['update_famincom']) ? sanitize_text_field($_POST['update_famincom']) : '';
        $update_caretaker = isset($_POST['update_caretaker']) ? sanitize_text_field($_POST['update_caretaker']) : 0;
        $update_referee = isset($_POST['update_referee']) ? sanitize_text_field($_POST['update_referee']) : 0;
        $update_fed = isset($_POST['update_fed']) ? sanitize_text_field($_POST['update_fed']) : 0;
        $update_med = isset($_POST['update_med']) ? sanitize_text_field($_POST['update_med']) : 0;

        // Check if the Roll No is already exist in the database for other records
        $roll_no_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE roll_no = %s", $update_regno));

        if ($subdomain_exists) {
            echo '<div class="error-message">Roll No already exists. Please choose a different one.</div>';
        }
            else {
            // Perform the update query
            $data = array(
                'school_name' => $update_school_name,
                'subdomain' => $update_subdomain,
                'principal' => $update_principal,
                'ph_no' => $update_ph_no,
                'mail_id' => $update_mail_id,
                'address' => $update_address,
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

    $query_results = $wpdb->get_results("SELECT * FROM tsm_students");
?>

    <style>
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
            position: relative;
            /* Position the profile icon and dropdown */
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
    </style>
    <!-- Make sure to include Font Awesome CSS in the <head> of your HTML file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="topnav">
        <div class="left">
            <span class="logo">Special School Management</span>
        </div>
        <div class="right">
            <div class="profile">
                <i class="fas fa-user-circle profile-icon" style="font-size: 24px;"></i>
                <div class="dropdown-content">
                    <a href="#">Profile</a>
                    <a href="#">Settings</a>
                    <form method="post" action="">
                        <?php wp_nonce_field('super_admin_logout', 'super_admin_logout_nonce'); ?>
                        <input class="logout" type="submit" name="super_admin_logout" value="Logout">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <h1>Welcome, <?php echo esc_html($current_user->user_login); ?>!</h1>

        <!-- Add Student Form -->
        <button onclick="showPopup()">Add School</button>
        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">School Student Data Upload</h2>
                <form method="post" action="">
                    <label for="RegNO">Reg NO:</label>
                    <input type="number" name="regno" id="regno" required>

                    <label for="name">Name:</label>
                    <input type="text" name="uname" id="uname" required>

                    <label for="dob">Date Of Birth:</label>
                    <input type="date" name="dob" id="dob" required>

                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" required>

                    <label for="gender">Gender:</label>
                    <input type="text" name="gender" id="gender" required>

                    <label for="mail_id">Email:</label>
                    <input type="email" name="mail_id" id="mail_id" required>

                    <label for="addresspr">Address Permenent:</label>
                    <textarea name="addresspr" id="addresspr" required></textarea>

                    <label for="addressp">Address Present:</label>
                    <textarea name="addressp" id="addressp" required></textarea>

                    <label for="ph_no">Phone Number:</label>
                    <input type="text" name="ph_no" id="ph_no" required>

                    <label for="ph_res">Phone Number (Residential):</label>
                    <input type="text" name="ph_res" id="ph_res" required>

                    <label for="informant">Infortmant:</label>
                    <input type="text" name="informant" id="informant" required>

                    <label for="religion">Religion:</label>
                    <input type="text" name="religion" id="religion" required>

                    <label for="mother_tongue">Mother Tongue:</label>
                    <input type="text" name="mother_tongue" id="mother_tongue" required>

                    <label for="eval">Date Of Evaluation:</label>
                    <input type="date" name="eval" id="eval" required>

                    <label for="fname">Father's Name:</label>
                    <input type="text" name="fname" id="fname" required>

                    <label for="mname">Mother's Name:</label>
                    <input type="text" name="mname" id="mname" required>

                    <label for="fed">Father's Education:</label>
                    <input type="text" name="fed" id="fed" required>

                    <label for="med">Mother's Education:</label>
                    <input type="text" name="med" id="med" required>

                    <label for="focc">Father's Occupation:</label>
                    <input type="text" name="focc" id="focc" required>

                    <label for="mocc">Mother's Occupation:</label>
                    <input type="text" name="mocc" id="mocc" required>

                    <label for="famincom">Annual Family Income</label>
                    <input type="text" name="famincom" id="famincom" required>

                    <label for="caretaker">Primary Care Taker:</label>
                    <input type="text" name="caretaker" id="caretaker" required>

                    <label for="referee">Refered By</label>
                    <input type="text" name="referee" id="referee" required>

                    <input class="submit" type="submit" name="submit_student" value="Submit">
                </form>
            </div>
        </div>


        <!-- Edit Student Data Popup -->
        <div class="popup-overlay" id="editPopupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">Edit School Data</h2>
                <div class="popup-overlay" id="popupOverlay">
                        <form method="post" action="">
                            <label for="regno">Reg NO:</label>
                            <input type="number" name="update_regno" id="regno" required>

                            <label for="uname">Name:</label>
                            <input type="text" name="update_uname" id="uname" required>

                            <label for="dob">Date Of Birth:</label>
                            <input type="date" name="dob" id="dob" required>

                            <label for="age">Age:</label>
                            <input type="number" name="age" id="age" required>

                            <label for="gender">Gender:</label>
                            <input type="text" name="gender" id="gender" required>

                            <label for="mail_id">Email:</label>
                            <input type="email" name="update_mail_id" id="mail_id" required>

                            <label for="addresspr">Address Permenent:</label>
                            <textarea name="update_addresspr" id="addresspr" required></textarea>

                            <label for="addressp">Address Present:</label>
                            <textarea name="update_addressp" id="addressp" required></textarea>

                            <label for="ph_no">Phone Number:</label>
                            <input type="text" name="update_ph_no" id="ph_no" required>

                            <label for="ph_res">Phone Number (Residential):</label>
                            <input type="text" name="update_ph_res" id="ph_res" required>

                            <label for="informant">Infortmant:</label>
                            <input type="text" name="update_informant" id="informant" required>

                            <label for="religion">Religion:</label>
                            <input type="text" name="update_religion" id="religion" required>

                            <label for="mother_tongue">Mother Tongue:</label>
                            <input type="text" name="update_mother_tongue" id="mother_tongue" required>

                            <label for="eval">Date Of Evaluation:</label>
                            <input type="date" name="update_eval" id="eval" required>

                            <label for="fname">Father's Name:</label>
                            <input type="text" name="update_fname" id="fname" required>

                            <label for="mname">Mother's Name:</label>
                            <input type="text" name="update_mname" id="mname" required>

                            <label for="fed">Father's Education:</label>
                            <input type="text" name="update_fed" id="fed" required>

                            <label for="med">Mother's Education:</label>
                            <input type="text" name="update_med" id="med" required>

                            <label for="focc">Father's Occupation:</label>
                            <input type="text" name="update_focc" id="focc" required>

                            <label for="mocc">Mother's Occupation:</label>
                            <input type="text" name="update_mocc" id="mocc" required>

                            <label for="famincom">Annual Family Income</label>
                            <input type="text" name="update_famincom" id="famincom" required>

                            <label for="caretaker">Primary Care Taker:</label>
                            <input type="text" name="update_caretaker" id="caretaker" required>

                            <label for="referee">Refered By</label>
                            <input type="text" name="update_referee" id="referee" required>

                            <input class="submit" type="submit" name="submit_student" value="Submit">
                        </form>
            </div>
        </div>
        <!-- Display Table -->
        <?php if (!empty($query_results)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Reg NO</th>
                        <th>Name</th>
                        <th>Principal</th>
                        <th>Date Of Birth</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Phone Number</th>
                        <th>Phone Number (Residential)</th>
                        <th>Infortmant</th>
                        <th>Religion</th>
                        <th>Mother Tongue</th>
                        <th>Date Of Evaluation</th>
                        <th>Father's Name</th>
                        <th>Mother's Name</th>
                        <th>Father's Education</th>
                        <th>Mother's Education</th>
                        <th>Father's Occupation</th>
                        <th>Mother's Occupation</th>
                        <th>Annual Family Incomee</th>
                        <th>Primary Care Taker</th>
                        <th>Refered by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($query_results as $school_data) : ?>
                        <tr>
                            <td><?php echo esc_html($student_data->reg_no); ?></td>
                            <td><?php echo esc_html($student_data->uname); ?></td>
                            <td><?php echo esc_html($student_data->dob); ?></td>
                            <td><?php echo esc_html($student_data->gender); ?></td>
                            <td><?php echo esc_html($student_data->ph_no); ?></td>
                            <td><?php echo esc_html($student_data->age); ?></td>
                            <td><?php echo esc_html($student_data->mail_id); ?></td>
                            <td><?php echo esc_html($student_data->address_permanent); ?></td>
                            <td><?php echo esc_html($student_data->address_present); ?></td>
                            <td><?php echo esc_html($student_data->ph_res); ?></td>
                            <td><?php echo esc_html($student_data->informant); ?></td>
                            <td><?php echo esc_html($student_data->religion); ?></td>
                            <td><?php echo esc_html($student_data->mother_tongue); ?></td>
                            <td><?php echo esc_html($student_data->evaluation_date); ?></td>
                            <td><?php echo esc_html($student_data->father_name); ?></td>
                            <td><?php echo esc_html($student_data->mother_name); ?></td>
                            <td><?php echo esc_html($student_data->father_education); ?></td>
                            <td><?php echo esc_html($student_data->mother_education); ?></td>
                            <td><?php echo esc_html($student_data->father_occupation); ?></td>
                            <td><?php echo esc_html($student_data->mother_occupation); ?></td>
                            <td><?php echo esc_html($student_data->annual_family_income); ?></td>
                            <td><?php echo esc_html($student_data->primary_caretaker); ?></td>
                            <td><?php echo esc_html($student_data->reference); ?></td>
                            <td><?php echo esc_html($student_data->created_at); ?></td>
                            <td>
                                <button onclick="editPopup(<?php echo $school_data->id; ?>, <?php $query_results ?>)">Edit</button>
                                <form method="post" action="">
                                    <input type="hidden" name="delete_id" value="<?php echo $school_data->id; ?>">
                                    <?php wp_nonce_field('delete_nonce', 'delete_nonce'); ?>
                                    <button class="submit" type="submit" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No school data found.</p>
        <?php endif; ?>

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

        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>

<?php
} else {
    // Redirect to the login page if the user is not logged in
    wp_redirect(home_url('/admin-login/'));
    exit;
}
