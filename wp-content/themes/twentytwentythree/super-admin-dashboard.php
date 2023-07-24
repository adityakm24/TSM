<?php
/*
Template Name: Super Admin Dashboard
*/
global $wpdb;
$table_name = 'tsm_school';

// Check if the user is logged in
if (is_user_logged_in()) {
	// Get the current user object
	$current_user = wp_get_current_user();
	$table_name = 'tsm_school';

	// Initialize form field variables
	$school_name = '';
	$subdomain = '';
	$principal = '';
	$ph_no = '';
	$mail_id = '';
	$address = '';

	if (isset($_POST['super_admin_logout'])) {
		// Verify the logout nonce
		if (wp_verify_nonce($_POST['super_admin_logout_nonce'], 'super_admin_logout')) {
			// Perform the logout
			wp_logout();

			// Destroy the session
			session_destroy();

			// Redirect to the login page
			wp_redirect(home_url('/super-admin-login/'));
			exit;
		}
	}

	// Custom password strength check function
	function is_strong_password($password, $min_length = 8) {
		// Check if the password meets the minimum length requirement
		if (strlen($password) < $min_length) {
			return false;
		}

		// Additional password strength checks
		// You can customize these checks based on your requirements

		// Check for at least one lowercase letter
		if (!preg_match('/[a-z]/', $password)) {
			return false;
		}

		// Check for at least one uppercase letter
		if (!preg_match('/[A-Z]/', $password)) {
			return false;
		}

		// Check for at least one digit
		if (!preg_match('/\d/', $password)) {
			return false;
		}

		// Check for at least one special character
		if (!preg_match('/[^a-zA-Z\d]/', $password)) {
			return false;
		}

		return true;
	}

	// Check if the form is submitted for adding a new school
	if (isset($_POST['upload'])) {
		global $wpdb;

		// Sanitize and validate form inputs
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$school_name = isset($_POST['school_name']) ? sanitize_text_field($_POST['school_name']) : '';
		$subdomain = isset($_POST['subdomain']) ? sanitize_text_field($_POST['subdomain']) : '';
		$principal = isset($_POST['principal']) ? sanitize_text_field($_POST['principal']) : '';
		$ph_no = isset($_POST['ph_no']) ? sanitize_text_field($_POST['ph_no']) : '';
		$mail_id = isset($_POST['mail_id']) ? sanitize_email($_POST['mail_id']) : '';
		$address = isset($_POST['address']) ? wp_kses_post($_POST['address']) : '';

		// Check if the subdomain is empty, and if so, generate a unique one
		if (empty($subdomain)) {
			$subdomain = strtolower(str_replace(' ', '', $school_name));
		}

		if (empty($school_name)) {
			echo '<div class="error-message">School Name is required.</div>';
		} elseif (empty($subdomain)) {
			echo '<div class="error-message">Subdomain is required.</div>';
		} elseif (empty($principal)) {
			echo '<div class="error-message">Principal is required.</div>';
		} elseif (empty($ph_no)) {
			echo '<div class="error-message">Phone Number is required.</div>';
		} elseif (empty($mail_id) || !is_email($mail_id)) {
			echo '<div class="error-message">Please enter a valid email address.</div>';
		} elseif (empty($address)) {
			echo '<div class="error-message">Address is required.</div>';
		} elseif (empty($password)) {
			echo '<div class="error-message">Password is required.</div>';
		} elseif (!is_strong_password($password)) {
			echo '<div class="error-message">Password must contain at least 8 characters, including uppercase letters, lowercase letters, numbers, and special characters.</div>';
		} else {
			// Check if the subdomain and email ID already exist in the database
			$subdomain_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE subdomain = %s", $subdomain));
			$email_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE mail_id = %s", $mail_id));

			if ($subdomain_exists) {
				echo '<div class="error-message">Subdomain already exists. Please choose a different one.</div>';
			} elseif ($email_exists) {
				echo '<div class="error-message">Email ID already exists. Please use a different one.</div>';
			} else {
				// Insert the sanitized data into the database
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);

				$data = array(
					'admin_id' => absint($current_user->ID),
					'school_name' => $school_name,
					'subdomain' => $subdomain,
					'principal' => $principal,
					'ph_no' => $ph_no,
					'mail_id' => $mail_id,
					'address' => $address,
					'password' => $hashed_password,
				);

				$wpdb->insert($table_name, $data);

				// Display a success message or take appropriate action
				echo '<div class="success-message">Data has been uploaded successfully.</div>';

				// Clear the form fields after successful data upload
				$school_name = '';
				$subdomain = '';
				$principal = '';
				$ph_no = '';
				$mail_id = '';
				$address = '';
			}
		}
	} elseif (isset($_POST['update'])) {
		// Handle form submission for updating data
		$update_id = absint($_POST['update_id']);
		$update_school_name = isset($_POST['edit_school_name']) ? sanitize_text_field($_POST['edit_school_name']) : '';
		$update_subdomain = isset($_POST['edit_subdomain']) ? sanitize_text_field($_POST['edit_subdomain']) : '';
		$update_principal = isset($_POST['edit_principal']) ? sanitize_text_field($_POST['edit_principal']) : '';
		$update_ph_no = isset($_POST['edit_ph_no']) ? sanitize_text_field($_POST['edit_ph_no']) : '';
		$update_mail_id = isset($_POST['edit_mail_id']) ? sanitize_email($_POST['edit_mail_id']) : '';
		$update_address = isset($_POST['edit_address']) ? wp_kses_post($_POST['edit_address']) : '';

		// Check if the subdomain and email ID already exist in the database for other records
		$subdomain_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE subdomain = %s AND id != %d", $update_subdomain, $update_id));
		$email_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE mail_id = %s AND id != %d", $update_mail_id, $update_id));

		if ($subdomain_exists) {
			echo '<div class="error-message">Subdomain already exists. Please choose a different one.</div>';
		} elseif ($email_exists) {
			echo '<div class="error-message">Email ID already exists. Please use a different one.</div>';
		} else {
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
        <button onclick="showPopup()">Add School</button>
        <div class="popup-overlay" id="popupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">School Data Upload</h2>
                <form method="post" action="">
                    <label for="school_name">Name:</label>
                    <input type="text" name="school_name" required autocomplete="off">

                    <label for="subdomain">Subdomain:</label>
                    <input type="text" name="subdomain" required autocomplete="off">

                    <label for="principal">Principal:</label>
                    <input type="text" name="principal" required autocomplete="off">

                    <label for="ph_no">Phone Number:</label>
                    <input type="tel" name="ph_no" required autocomplete="off">

                    <label for="mail_id">Email:</label>
                    <input type="email" name="mail_id" required autocomplete="off">

                    <label for="address">Address:</label>
                    <textarea name="address" required autocomplete="off"></textarea>

                    <label for="password">Password:</label>
                    <input type="password" name="password" required autocomplete="off">

                    <input type="submit" name="upload" value="Upload">
                    <button type="button" onclick="hidePopup()">Cancel</button>
                </form>
            </div>
        </div>


        <!-- Edit School Data Popup -->
        <div class="popup-overlay" id="editPopupOverlay">
            <div class="popup-container">
                <h2 class="entry-title">Edit School Data</h2>
                <form method="post" action="">
                    <input type="hidden" id="edit_id" name="update_id" value="">
                    <label for="edit_school_name">Name:</label>
                    <input type="text" name="edit_school_name" id="edit_school_name" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_subdomain">Subdomain:</label>
                    <input type="text" name="edit_subdomain" id="edit_subdomain" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_principal">Principal:</label>
                    <input type="text" name="edit_principal" id="edit_principal" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_ph_no">Phone Number:</label>
                    <input type="tel" name="edit_ph_no" id="edit_ph_no" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_mail_id">Email:</label>
                    <input type="email" name="edit_mail_id" id="edit_mail_id" required autocomplete="on" oninput="this.placeholder = this.value">

                    <label for="edit_address">Address:</label>
                    <textarea name="edit_address" id="edit_address" required autocomplete="on" oninput="this.placeholder = this.value"></textarea>

                    <input type="submit" name="update" value="Update">
                    <button type="button" onclick="hideEditPopup()">Cancel</button>
                </form>
            </div>
        </div>
        <!-- Display Table -->
		<?php if (!empty($query_results)) : ?>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Subdomain</th>
                    <th>Principal</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ($query_results as $school_data) : ?>
                    <tr>
                        <td><?php echo esc_html($school_data->school_name); ?></td>
                        <td><?php echo esc_html($school_data->subdomain); ?></td>
                        <td><?php echo esc_html($school_data->principal); ?></td>
                        <td><?php echo esc_html($school_data->ph_no); ?></td>
                        <td><?php echo esc_html($school_data->mail_id); ?></td>
                        <td><?php echo esc_html($school_data->address); ?></td>
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
            <p>No school data found.</p>
		<?php endif; ?>

        <!-- Logout Form -->
        <form method="post" action="">
			<?php wp_nonce_field('super_admin_logout', 'super_admin_logout_nonce'); ?>
            <input type="submit" name="super_admin_logout" value="Logout">
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

	<?php
} else {
	// Redirect to the login page if the user is not logged in
	wp_redirect(home_url('/super-admin-login/'));
	exit;
}