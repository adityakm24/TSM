<?php
/*
Template Name: Add Teacher Admin
*/

global $wpdb;
$table_name = 'tsm_teacher';

if (is_user_logged_in() && $_COOKIE['id'] != NULL) {
    $school_id = $_COOKIE['id'];
    $uname =  '';
    $dept =  '';
    $gender =  '';
    $ph_no =  '';
    $age =  '';
    $mail_id = '';
    $address =  '';
    $password = '';
    $activation = 0;

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


    if (isset($_POST['submit_teacher'])) {
        // Sanitize and validate form input
        $school_id = $_COOKIE['id'];
        $uname = isset($_POST['uname']) ? sanitize_text_field($_POST['uname']) : '';
        $dept = isset($_POST['dept']) ? sanitize_text_field($_POST['dept']) : '';
        $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
        $ph_no = isset($_POST['ph_no']) ? sanitize_text_field($_POST['ph_no']) : '';
        $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
        $mail_id = isset($_POST['mail_id']) ? sanitize_email($_POST['mail_id']) : '';
        $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';
        $password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
        $activation = 0;


        if (empty($uname)) {
            echo '<div class="error-message">Name is required.</div>';
        } elseif (empty($dept)) {
            echo '<div class="error-message">Department is required.</div>';
        } elseif (empty($gender)) {
            echo '<div class="error-message">Gender is required.</div>';
        } elseif (empty($ph_no)) {
            echo '<div class="error-message">Phone Number is required.</div>';
        } elseif (!preg_match('/^\d{10}$/', $ph_no)) {
            echo '<div class="error-message">Phone Number must be 10 digits.</div>';
        } elseif (empty($age)) {
            echo '<div class="error-message">Age is required.1</div>';
        } elseif (empty($mail_id)) {
            echo '<div class="error-message">Email is required.</div>';
        } elseif (!is_email($mail_id)) {
            echo '<div class="error-message">Invalid Email address.1</div>';
        } elseif (empty($address)) {
            echo '<div class="error-message">Address is required.</div>';
        } elseif (empty($password)) {
            echo '<div class="error-message">Password is required.</div>';
        } else {

            // Insert the data into the database
            $sql = "INSERT INTO tsm_teacher (school_id, name, dept, gender, ph_no, age, mail_id, address, password, activation, created_at) VALUES ('$school_id','$uname', '$dept', '$gender', '$ph_no', '$age', '$mail_id', '$address', '$password', 0, current_time())";
            $result = $wpdb->query($sql);
            if ($result) {
                echo '<div class="success-message">Data has been uploaded successfully.</div>';
                wp_redirect(home_url('/admin-dashboard/'));
                exit;
            } else {
                echo '<div class="error-message">Failed to insert data into the database.</div>';
            }

            exit;
        }
    } elseif (isset($_POST['update'])) {
        $update_school_id = $_COOKIE['id'];
        $update_id = absint($_POST['update_id']);
        $uname = isset($_POST['update_uname']) ? sanitize_text_field($_POST['update_uname']) : '';
        $dept = isset($_POST['update_dept']) ? sanitize_text_field($_POST['update_dept']) : '';
        $gender = isset($_POST['update_gender']) ? sanitize_text_field($_POST['update_gender']) : '';
        $ph_no = isset($_POST['update_ph_no']) ? sanitize_text_field($_POST['update_ph_no']) : '';
        $age = isset($_POST['update_age']) ? intval($_POST['update_age']) : 0;
        $mail_id = isset($_POST['update_mail_id']) ? sanitize_email($_POST['update_mail_id']) : '';
        $address = isset($_POST['update_address']) ? sanitize_textarea_field($_POST['update_address']) : '';

        $mail_id_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE mail_id = %s", $mail_id));

        if ($mail_id_exists > 1) {
            echo '<div class="error-message">Mail ID already exists. Please choose a different one.</div>';
        } else {
            // Perform the update query
            $data = array(
                'school_id' => $update_school_id,
                'name' => $uname,
                'dept' => $dept,
                'gender' => $gender,
                'ph_no' => $ph_no,
                'age' => $age,
                'mail_id' => $mail_id,
                'address' => $address,
            );

            $wpdb->update($table_name, $data, array('id' => $update_id));

            // Display a success message or take appropriate action
            echo '<div class="success-message">Data has been updated successfully.</div>';
        }
    } elseif (isset($_POST['delete_id'])) {
        $delete_id = absint($_POST['delete_id']);


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
    }
    $query_results = $wpdb->get_results("SELECT * FROM $table_name");
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

        /* Updated styles for the popup container */
        .popup-container {
            display: block;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1000;
            max-width: 80%;
            width: 80%;
            max-height: 80%;
            overflow-y: auto;
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
            max-width: 100%;
            /* Set a maximum width to prevent overflow */
            overflow-x: auto;
            /* Enable horizontal scrolling if necessary */
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            white-space: nowrap;
            /* Prevent text from wrapping */
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

        .go-home-button {
            display: inline-block;
            padding: 10px 20px;
            /* Button background color */
            color: blue;
            /* Button text color */
            text-decoration: none;
            border-radius: 5px;
            margin: 20px;
        }

        .go-home-button:hover {
            color: grey;
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
                        <?php wp_nonce_field('admin_logout', 'admin_logout_nonce'); ?>
                        <input class="logout" type="submit" name="admin_logout" value="Logout">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <a href="/tsm/admin" class="go-home-button">Go Home</a>
    <div class="container">

        <h1>Welcome, <?php echo esc_html($current_user->user_login); ?>!</h1>

        <!-- Add Teacher Form -->
        <button onclick="showPopup()">Add Teacher</button>
        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">School Teacher Data Upload</h2>
                <form method="post" action="">
                    <label for=" name">Name:</label>
                    <input type="text" name="uname" id="uname" required>

                    <label for="dept">Department:</label>
                    <input type="text" name="dept" id="dept" required>

                    <label for="gender">Gender:</label>
                    <input type="text" name="gender" id="gender" required>

                    <label for="ph_no">Phone Number:</label>
                    <input type="text" name="ph_no" id="ph_no" required>

                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" required>

                    <label for="mail_id">Email:</label>
                    <input type="email" name="mail_id" id="mail_id" required>

                    <label for="address">Address:</label>
                    <textarea name="address" id="address" required></textarea>

                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                    <input class="submit" type="submit" name="submit_teacher" value="Submit">
                </form>
            </div>
        </div>

        <!-- Edit Teacher Data Popup -->
        <div class="popup-overlay" id="editPopupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">Edit Teacher Data</h2>
                <form method="post" action="">
                    <input type="hidden" id="update_id" name="update_id" value="">
                    <label for="update_uname">Name:</label>
                    <input type="text" name="update_uname" id="update_uname" required>

                    <label for="update_dept">Department:</label>
                    <input type="text" name="update_dept" id="update_dept" required>

                    <label for="update_gender">Gender:</label>
                    <input type="text" name="update_gender" id="update_gender" required>

                    <label for="update_ph_no">Phone Number:</label>
                    <input type="text" name="update_ph_no" id="update_ph_no" required>

                    <label for="update_age">Age:</label>
                    <input type="number" name="update_age" id="update_age" required>


                    <label for="address">Address</label>
                    <textarea name="update_address" id="update_address" required autocomplete="on" oninput="this.placeholder = this.value"></textarea>


                    <label for="update_mail_id">Email:</label>
                    <input type="email" name="update_mail_id" id="update_mail_id" required>
                    <button class="submit" type="submit" name="update" value="Submit">Update</button>
                    <button class="submit" type="button" onclick="hideEditPopup()">Cancel</button>
                </form>
            </div>
        </div>
        <!-- Display Table -->

        <?php if (!empty($query_results)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Gender</th>
                        <th>Phone Number</th>
                        <th>Age</th>
                        <th>Email ID</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($query_results as $teacher_data) : ?>
                        <tr>
                            <td><?php echo esc_html($teacher_data->name); ?></td>
                            <td><?php echo esc_html($teacher_data->dept); ?></td>
                            <td><?php echo esc_html($teacher_data->gender); ?></td>
                            <td><?php echo esc_html($teacher_data->ph_no); ?></td>
                            <td><?php echo esc_html($teacher_data->age); ?></td>
                            <td><?php echo esc_html($teacher_data->mail_id); ?></td>
                            <td><?php echo esc_html(substr($teacher_data->address, 0, 50)); ?></td>


                            <td>
                                <button onclick="editPopup(<?php echo $teacher_data->id; ?>, <?php $query_results ?>)">Edit</button><br>
                                <button onclick="editPopup(<?php echo $teacher_data->id; ?>, <?php $query_results ?>)">View Profile</button>
                                <form method="post" action="">
                                    <input type="hidden" name="delete_id" value="<?php echo $teacher_data->id; ?>">
                                    <?php wp_nonce_field('delete_nonce', 'delete_nonce'); ?>
                                    <button class="submit" type="submit" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php else : ?>
            <p>No teacher data found.</p>
        <?php endif; ?>

    </div>
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
            const selectedTeacher = query_results.find((teacher) => parseInt(teacher.id) === id);
            console.log("Selected Teacher Data:", selectedTeacher);
            if (selectedTeacher) {
                document.getElementById("update_uname").value = selectedTeacher.name;
                document.getElementById("update_dept").value = selectedTeacher.dept;
                document.getElementById("update_gender").value = selectedTeacher.gender;
                document.getElementById("update_ph_no").value = selectedTeacher.ph_no;
                document.getElementById("update_age").value = selectedTeacher.age;
                document.getElementById("update_address").value = selectedTeacher.address;
                document.getElementById("update_mail_id").value = selectedTeacher.mail_id;
            } else {
                console.error("Selected Teacher data not found!");
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
