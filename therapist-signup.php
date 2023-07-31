<?php
/*
Template Name: Therapist Signup
*/

// Check if the form is submitted
if ( isset( $_POST['therapist_signup'] ) ) {
	// Sanitize and validate form inputs
	$school_id = sanitize_text_field( $_POST['school_id'] );
    $name = sanitize_text_field( $_POST['namep'] );
	$dept = sanitize_text_field( $_POST['dept'] );
	$age = absint( $_POST['age'] ); // Make sure age is a positive integer
    $gender = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
	$address = sanitize_textarea_field( $_POST['address'] );
	$password = wp_strip_all_tags( $_POST['password'] );
	$email = sanitize_email( $_POST['email'] );
    $phone = sanitize_text_field( $_POST['phone'] );
	
    $errors = array();

	if ( empty( $gender ) ) {
        $errors[] = 'Gender is required.';
    }

	if ( empty( $password ) ) {
		$errors[] = 'Password is required.';
	} elseif ( ! is_strong_password( $password ) ) {
		$errors[] = 'Password must contain at least 8 characters, including uppercase letters, lowercase letters, numbers, and special characters.';
	}

	if ( empty( $email ) || ! is_email( $email ) ) {
		$errors[] = 'Please enter a valid email address.';
	}

    if ( empty( $phone ) ) {
		$errors[] = 'Phone number is required.';
	} elseif ( ! preg_match( '/^\+?[0-9]+/', $phone ) ) {
		$errors[] = 'Please enter a valid phone number.';
	}

    if ( empty( $name ) ) {
		$errors[] = 'Name is required.';
	}

	if ( empty( $dept ) ) {
		$errors[] = 'Department is required.';
	}

	if ( empty( $age ) || ! is_int( $age ) || $age <= 0 ) {
		$errors[] = 'Please enter a valid age.';
	}

	if ( empty( $address ) ) {
		$errors[] = 'Address is required.';
	}
	// Check if username or email is already taken
	global $wpdb;
	$table_name = 'tsm_therapist';

	$existing_user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE email = %s", $email ) );

	if ( $existing_user ) {	

		if ( $existing_user->email === $email ) {
			$errors[] = 'Email is already taken.';
		}
	}

	// Display error messages or proceed with data insertion
	if ( ! empty( $errors ) ) {
		foreach ( $errors as $error ) {
			echo '<div class="error-message">' . esc_html( $error ) . '</div>';
		}
	} else {
		// Continue with the data insertion process

		// Generate a secure password hash
		$hashed_password = password_hash( $password, PASSWORD_DEFAULT );

		// Insert data into the tsm_therapist table
		$insert_data = array(
			'school_id' => $school_id,
			'password' => $hashed_password,
			'mail_id' => $email,
            'ph_no' => $phone,
			'gender' => $gender,
			'name' => $name,
			'dept' => $dept,
			'age' => $age,
			'address' => $address,
			'created_at' => current_time( 'mysql' ),
		);

		$insert_success = $wpdb->insert( $table_name, $insert_data );

		if ( $insert_success ) {
			// Redirect to the login page
			wp_redirect( home_url( '/therapist-login/' ) );
			exit;
		} else {
			echo '<div class="error-message">Error inserting data into the database. Details: ' . $wpdb->last_error . '</div>';
		}
	}
}
?>

<!-- HTML code for the signup form -->

<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f1f1f1;
    }

    .signup-box {
        display: flex;
        justify-content: center;
        align-items: center;
		margin-top: 5%;
    }

    .signup-form {
        width: 360px;
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    input[type="text"],
    input[type="password"],
	input[type="number"],
	textarea,
    input[type="email"] {
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

    p {
        margin-top: 20px;
        text-align: center;
    }
	.row{
		display: flex;
	}
</style>

<div class="signup-box">
    <div class="signup-form">
		<form method="post" action="<?php echo esc_url( home_url( '/therapist-signup/' ) ); ?>">

			<label for="school_id">School ID:</label>
			<input type="text" name="school_id" id="school_id" required><br>

			<label for="name">Name:</label>
			<input type="text" name="namep" id="name" required><br>

			<label for="dept">Department:</label>
			<input type="text" name="dept" id="dept" required><br>
			<div class="row">
				<label for="gender">Gender:</label><br>
				<input type="radio" name="gender" value="Male" id="option1" required><br>
				<label for="option1">Male</label>
						
				<input type="radio" name="gender" value="Female" id="option2" required><br>				
				<label for="option2">Female</label>
						
				<input type="radio" name="gender" value="Other" id="option3" required><br>
				<label for="option3">Other</label>
			</div>
			<label for="age">Age:</label>
			<input type="number" name="age" id="age" required><br>

			<label for="phone">Phone No:</label>
			<input type="number" name="phone" id="phone" required><br>

			<label for="address">Address:</label>
			<textarea name="address" id="address" required></textarea><br>

			<label for="email">Email:</label>
			<input type="email" name="email" id="email" required><br>

			<label for="password">Password:</label>
			<input type="password" name="password" id="password" required><br>

			<input type="hidden" name="therapist_signup" value="1">
			<input type="submit" value="Register">
			<p>Already have an account? Login <a href="<?php echo esc_url( home_url( '/therapist-login/' ) ); ?>">here</a></p>

		</form>
	</div>
</div>


<?php
/**
 * Check if a password meets the required strength.
 *
 * @param string $password The password to check.
 * @param int    $min_length Minimum length requirement.
 *
 * @return bool True if the password meets the strength requirements, false otherwise.
 */
function is_strong_password( $password, $min_length = 8 ) {
	// Check if the password meets the minimum length requirement
	if ( strlen( $password ) < $min_length ) {
		return false;
	}

	// Additional password strength checks
	// You can customize these checks based on your requirements

	// Check for at least one lowercase letter
	if ( ! preg_match( '/[a-z]/', $password ) ) {
		return false;
	}

	// Check for at least one uppercase letter
	if ( ! preg_match( '/[A-Z]/', $password ) ) {
		return false;
	}

	// Check for at least one digit
	if ( ! preg_match( '/\d/', $password ) ) {
		return false;
	}

	// Check for at least one special character
	if ( ! preg_match( '/[^a-zA-Z\d]/', $password ) ) {
		return false;
	}

	return true;
}
?>